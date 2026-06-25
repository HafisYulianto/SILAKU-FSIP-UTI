<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use App\Models\ProgramStudi;
use App\Models\ActivityLog;
use App\Models\DataApprovalRequest;
use App\Services\GeocodingService;
use Illuminate\Http\Request;

class AlumniController extends Controller
{
    public function index(Request $request)
    {
        $prodiId = $request->get('program_studi_id');

        $alumnis = Alumni::with(['programStudi', 'creator'])
            ->when($prodiId, fn($q) => $q->where('program_studi_id', $prodiId))
            ->orderBy('nama', 'asc')
            ->paginate(20);

        $programStudis = ProgramStudi::where('is_active', true)->get();

        // Pending delete requests for this user (so we can show "Menunggu" badge)
        $pendingDeleteIds = [];
        if (auth()->user()->hasAnyRole(['Kaprodi', 'Dosen'])) {
            $pendingDeleteIds = DataApprovalRequest::where('type', 'alumni')
                ->where('action', 'delete')
                ->where('status', 'pending')
                ->where('requester_id', auth()->id())
                ->pluck('alumni_id')
                ->toArray();
        }

        return view('alumni.index', compact('alumnis', 'programStudis', 'prodiId', 'pendingDeleteIds'));
    }

    public function exportExcel(Request $request, \App\Services\ExportService $exportService)
    {
        $prodiId = $request->get('program_studi_id');

        $alumnis = Alumni::with(['programStudi', 'creator'])
            ->when($prodiId, fn($q) => $q->where('program_studi_id', $prodiId))
            ->orderBy('nama', 'asc')
            ->get();

        return $exportService->exportAlumniToExcel($alumnis);
    }

    public function exportPdf(Request $request, \App\Services\ExportService $exportService)
    {
        $prodiId = $request->get('program_studi_id');

        $alumnis = Alumni::with(['programStudi', 'creator'])
            ->when($prodiId, fn($q) => $q->where('program_studi_id', $prodiId))
            ->latest()
            ->get();

        return $exportService->exportAlumniToPdf($alumnis);
    }

    public function create()
    {
        $programStudis = ProgramStudi::where('is_active', true)->get();
        return view('alumni.create', compact('programStudis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'             => 'required|string|max:255',
            'nama_perusahaan'  => 'required|string|max:255',
            'posisi'           => 'required|string|max:255',
            'lokasi'           => 'required|string|max:255',
            'program_studi_id' => 'nullable|exists:program_studi,id',
            'lat'              => 'nullable|numeric',
            'lng'              => 'nullable|numeric',
        ]);

        $user = auth()->user();
        $role = $user->roles->first()?->name ?? 'User';

        // Simpan langsung untuk semua role (BAAK, Kaprodi, Dosen)
        $alumni = Alumni::create([
            'nama'             => $request->nama,
            'nama_perusahaan'  => $request->nama_perusahaan,
            'posisi'           => $request->posisi,
            'lokasi'           => $request->lokasi,
            'program_studi_id' => $request->program_studi_id,
            'lat'              => $request->lat,
            'lng'              => $request->lng,
            'created_by'       => $user->id,
        ]);

        // Geocode asynchronously (best-effort) only if no coordinate provided
        if (empty($alumni->lat) || empty($alumni->lng)) {
            try {
                app(GeocodingService::class)->geocodeAlumni($alumni);
            } catch (\Exception $e) {
                // Geocoding failure is non-critical
            }
        }

        // Log hanya untuk Kaprodi dan Dosen
        if (in_array($role, ['Kaprodi', 'Dosen'])) {
            ActivityLog::create([
                'user_id'     => $user->id,
                'actor_name'  => $user->name,
                'actor_role'  => $role,
                'action'      => 'create_alumni',
                'description' => "Menambahkan data alumni \"{$alumni->nama}\"",
            ]);
        }

        return redirect()
            ->route('alumni.index')
            ->with('success', "Data alumni \"{$alumni->nama}\" berhasil ditambahkan.");
    }

    public function show(Alumni $alumni)
    {
        return view('alumni.show', compact('alumni'));
    }

    public function edit(Alumni $alumni)
    {
        $programStudis = ProgramStudi::where('is_active', true)->get();
        return view('alumni.edit', compact('alumni', 'programStudis'));
    }

    public function update(Request $request, Alumni $alumni)
    {
        $request->validate([
            'nama'             => 'required|string|max:255',
            'nama_perusahaan'  => 'required|string|max:255',
            'posisi'           => 'required|string|max:255',
            'lokasi'           => 'required|string|max:255',
            'program_studi_id' => 'nullable|exists:program_studi,id',
            'lat'              => 'nullable|numeric',
            'lng'              => 'nullable|numeric',
        ]);

        $locationChanged = ($alumni->lokasi !== $request->lokasi);
        
        $alumni->update([
            'nama'             => $request->nama,
            'nama_perusahaan'  => $request->nama_perusahaan,
            'posisi'           => $request->posisi,
            'lokasi'           => $request->lokasi,
            'program_studi_id' => $request->program_studi_id,
            'lat'              => $request->lat ?: ($locationChanged ? null : $alumni->lat),
            'lng'              => $request->lng ?: ($locationChanged ? null : $alumni->lng),
        ]);
        
        // Re-geocode if location changed and no manual coord provided
        if ($locationChanged && (empty($alumni->lat) || empty($alumni->lng))) {
            try {
                app(GeocodingService::class)->geocodeAlumni($alumni);
            } catch (\Exception $e) {
                // Non-critical
            }
        }

        $role = auth()->user()->roles->first()?->name ?? 'User';
        // Log hanya untuk Kaprodi/Dosen
        if (in_array($role, ['Kaprodi', 'Dosen'])) {
            ActivityLog::create([
                'user_id'     => auth()->id(),
                'actor_name'  => auth()->user()->name,
                'actor_role'  => $role,
                'action'      => 'update_alumni',
                'description' => "Memperbarui data alumni \"{$alumni->nama}\"",
            ]);
        }

        return redirect()
            ->route('alumni.index')
            ->with('success', "Data alumni \"{$alumni->nama}\" berhasil diperbarui.");
    }

    public function destroy(Alumni $alumni)
    {
        $user = auth()->user();
        $nama = $alumni->nama;

        // BAAK langsung hapus — tidak perlu log
        if ($user->hasRole('BAAK')) {
            $alumni->delete();

            return redirect()
                ->route('alumni.index')
                ->with('success', "Data alumni \"{$nama}\" berhasil dihapus.");
        }

        // Kaprodi / Dosen — cek apakah sudah ada permintaan pending
        $existing = DataApprovalRequest::where('type', 'alumni')
            ->where('action', 'delete')
            ->where('status', 'pending')
            ->where('alumni_id', $alumni->id)
            ->first();

        if ($existing) {
            return redirect()
                ->route('alumni.index')
                ->with('info', "Permintaan hapus alumni \"{$nama}\" sudah ada dan sedang menunggu persetujuan BAAK.");
        }

        $role = $user->hasRole('Kaprodi') ? 'Kaprodi' : 'Dosen';

        DataApprovalRequest::create([
            'type'           => 'alumni',
            'action'         => 'delete',
            'status'         => 'pending',
            'alumni_id'      => $alumni->id,
            'requester_id'   => $user->id,
            'requester_name' => $user->name,
            'requester_role' => $role,
        ]);

        ActivityLog::create([
            'user_id'     => $user->id,
            'actor_name'  => $user->name,
            'actor_role'  => $role,
            'action'      => 'request_delete_alumni',
            'description' => "Mengajukan permintaan hapus data alumni \"{$nama}\" — menunggu persetujuan BAAK",
        ]);

        return redirect()
            ->route('alumni.index')
            ->with('info', "Permintaan hapus alumni \"{$nama}\" telah dikirim dan menunggu persetujuan BAAK.");
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'alumni_ids'   => 'required|array|min:1',
            'alumni_ids.*' => 'integer|exists:alumnis,id',
        ]);

        $user = auth()->user();
        if (!$user->hasRole('BAAK')) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menghapus data massal.');
        }

        $count = count($request->alumni_ids);
        Alumni::whereIn('id', $request->alumni_ids)->delete();

        return redirect()
            ->route('alumni.index')
            ->with('success', "{$count} data alumni berhasil dihapus secara massal.");
    }

    public function destroyAll()
    {
        $user = auth()->user();
        if (!$user->hasRole('BAAK')) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menghapus semua data.');
        }

        $count = Alumni::count();

        // Hapus dulu data persetujuan yang berelasi dengan alumni (FK constraint)
        \App\Models\DataApprovalRequest::where('type', 'alumni')->delete();

        // Kemudian hapus semua alumni
        Alumni::query()->delete();

        return redirect()
            ->route('alumni.index')
            ->with('success', "Seluruh data alumni berhasil dihapus.");
    }

    /**
     * Endpoint API for autocomplete search suggestion.
     */
    public function suggestLocation(Request $request)
    {
        $q = $request->get('q');
        if (empty($q) || strlen($q) < 3) {
            return response()->json([]);
        }

        $suggestions = app(GeocodingService::class)->suggest($q);

        return response()->json($suggestions);
    }
}
