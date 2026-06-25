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
            ->latest()
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
            ->latest()
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
        ]);

        $user = auth()->user();

        // BAAK langsung simpan + geocode
        if ($user->hasRole('BAAK')) {
            $alumni = Alumni::create([
                'nama'             => $request->nama,
                'nama_perusahaan'  => $request->nama_perusahaan,
                'posisi'           => $request->posisi,
                'lokasi'           => $request->lokasi,
                'program_studi_id' => $request->program_studi_id,
                'created_by'       => $user->id,
            ]);

            // Geocode asynchronously (best-effort)
            try {
                app(GeocodingService::class)->geocodeAlumni($alumni);
            } catch (\Exception $e) {
                // Geocoding failure is non-critical
            }

            ActivityLog::create([
                'user_id'     => $user->id,
                'actor_name'  => $user->name,
                'actor_role'  => 'BAAK',
                'action'      => 'create_alumni',
                'description' => "Menambahkan data alumni baru \"{$alumni->nama}\" di \"{$alumni->nama_perusahaan}\"",
            ]);

            return redirect()
                ->route('alumni.index')
                ->with('success', "Data alumni \"{$alumni->nama}\" berhasil ditambahkan.");
        }

        // Kaprodi / Dosen — ajukan permintaan
        $role = $user->hasRole('Kaprodi') ? 'Kaprodi' : 'Dosen';

        DataApprovalRequest::create([
            'type'           => 'alumni',
            'action'         => 'create',
            'status'         => 'pending',
            'payload'        => [
                'nama'             => $request->nama,
                'nama_perusahaan'  => $request->nama_perusahaan,
                'posisi'           => $request->posisi,
                'lokasi'           => $request->lokasi,
                'program_studi_id' => $request->program_studi_id,
                'created_by'       => $user->id,
            ],
            'requester_id'   => $user->id,
            'requester_name' => $user->name,
            'requester_role' => $role,
        ]);

        ActivityLog::create([
            'user_id'     => $user->id,
            'actor_name'  => $user->name,
            'actor_role'  => $role,
            'action'      => 'request_create_alumni',
            'description' => "Mengajukan permintaan tambah data alumni \"{$request->nama}\" — menunggu persetujuan BAAK",
        ]);

        return redirect()
            ->route('alumni.index')
            ->with('info', "Permintaan tambah data alumni \"{$request->nama}\" telah dikirim dan menunggu persetujuan BAAK.");
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
        ]);

        $alumni->update([
            'nama'             => $request->nama,
            'nama_perusahaan'  => $request->nama_perusahaan,
            'posisi'           => $request->posisi,
            'lokasi'           => $request->lokasi,
            'program_studi_id' => $request->program_studi_id,
        ]);

        ActivityLog::create([
            'user_id'     => auth()->id(),
            'actor_name'  => auth()->user()->name,
            'actor_role'  => auth()->user()->roles->first()->name ?? 'User',
            'action'      => 'update_alumni',
            'description' => "Memperbarui data alumni \"{$alumni->nama}\"",
        ]);

        return redirect()
            ->route('alumni.index')
            ->with('success', "Data alumni \"{$alumni->nama}\" berhasil diperbarui.");
    }

    public function destroy(Alumni $alumni)
    {
        $user = auth()->user();
        $nama = $alumni->nama;

        // BAAK langsung hapus
        if ($user->hasRole('BAAK')) {
            $alumni->delete();

            ActivityLog::create([
                'user_id'     => $user->id,
                'actor_name'  => $user->name,
                'actor_role'  => 'BAAK',
                'action'      => 'delete_alumni',
                'description' => "Menghapus data alumni \"{$nama}\"",
            ]);

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
}
