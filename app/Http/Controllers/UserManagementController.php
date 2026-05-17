<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $users = User::with(['roles', 'programStudi'])
            ->when($request->search, function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
                  ->orWhere('nip', 'like', "%{$request->search}%");
            })
            ->when($request->role, function ($q) use ($request) {
                $q->whereHas('roles', fn($r) => $r->where('name', $request->role));
            })
            ->latest()
            ->paginate(20);

        $roles = Role::all();

        return view('users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $roles = Role::whereIn('name', ['Kaprodi', 'Dosen', 'Wakil Dekan'])->get();
        $programStudiList = ProgramStudi::where('is_active', true)->get();

        return view('users.create', compact('roles', 'programStudiList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Password::min(8)],
            'nip' => 'nullable|string|max:30|unique:users,nip',
            'role' => ['required', Rule::in(['Kaprodi', 'Dosen', 'Wakil Dekan'])],
            'program_studi_id' => 'nullable|exists:program_studi,id',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'plain_password' => $request->password,
            'nip' => $request->nip,
            'program_studi_id' => $request->program_studi_id,
            'is_active' => true,
        ]);

        $user->assignRole($request->role);

        return redirect()
            ->route('users.index')
            ->with('success', "Akun \"{$user->name}\" dengan role {$request->role} berhasil dibuat.");
    }

    public function show(User $user)
    {
        $user->load(['roles', 'programStudi']);
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = Role::whereIn('name', ['Kaprodi', 'Dosen', 'Wakil Dekan'])->get();
        $programStudiList = ProgramStudi::where('is_active', true)->get();

        return view('users.edit', compact('user', 'roles', 'programStudiList'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'confirmed', Password::min(8)],
            'nip' => ['nullable', 'string', 'max:30', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', Rule::in(['Kaprodi', 'Dosen', 'Wakil Dekan'])],
            'program_studi_id' => 'nullable|exists:program_studi,id',
            'is_active' => 'boolean',
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'nip' => $request->nip,
            'program_studi_id' => $request->program_studi_id,
            'is_active' => $request->boolean('is_active', true),
        ];

        if ($request->filled('password')) {
            $userData['password'] = $request->password;
            $userData['plain_password'] = $request->password;
        }

        $user->update($userData);
        $user->syncRoles([$request->role]);

        return redirect()
            ->route('users.index')
            ->with('success', "Data pengguna \"{$user->name}\" berhasil diperbarui.");
    }

    public function destroy(User $user)
    {
        if ($user->hasRole('BAAK') || $user->hasRole('Pimpinan')) {
            return back()->with('error', 'Tidak dapat menghapus akun BAAK/Pimpinan.');
        }

        $name = $user->name;
        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', "Pengguna \"{$name}\" berhasil dihapus.");
    }

    public function toggleActive(User $user)
    {
        if ($user->hasRole('BAAK') || $user->hasRole('Pimpinan')) {
            return back()->with('error', 'Tidak dapat menonaktifkan akun BAAK/Pimpinan.');
        }

        $user->update(['is_active' => !$user->is_active]);
        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Pengguna \"{$user->name}\" berhasil {$status}.");
    }
}
