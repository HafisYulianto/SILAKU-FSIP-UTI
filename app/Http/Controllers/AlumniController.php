<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use App\Models\ProgramStudi;
use App\Models\ActivityLog;
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

        return view('alumni.index', compact('alumnis', 'programStudis', 'prodiId'));
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
            'nama' => 'required|string|max:255',
            'nama_perusahaan' => 'required|string|max:255',
            'posisi' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'program_studi_id' => 'nullable|exists:program_studi,id',
        ]);

        $alumni = Alumni::create([
            'nama' => $request->nama,
            'nama_perusahaan' => $request->nama_perusahaan,
            'posisi' => $request->posisi,
            'lokasi' => $request->lokasi,
            'program_studi_id' => $request->program_studi_id,
            'created_by' => auth()->id(),
        ]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'actor_name' => auth()->user()->name,
            'actor_role' => auth()->user()->roles->first()->name ?? 'User',
            'action' => 'create_alumni',
            'description' => "Menambahkan data alumni baru \"{$alumni->nama}\" di \"{$alumni->nama_perusahaan}\"",
        ]);

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
            'nama' => 'required|string|max:255',
            'nama_perusahaan' => 'required|string|max:255',
            'posisi' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'program_studi_id' => 'nullable|exists:program_studi,id',
        ]);

        $alumni->update([
            'nama' => $request->nama,
            'nama_perusahaan' => $request->nama_perusahaan,
            'posisi' => $request->posisi,
            'lokasi' => $request->lokasi,
            'program_studi_id' => $request->program_studi_id,
        ]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'actor_name' => auth()->user()->name,
            'actor_role' => auth()->user()->roles->first()->name ?? 'User',
            'action' => 'update_alumni',
            'description' => "Memperbarui data alumni \"{$alumni->nama}\"",
        ]);

        return redirect()
            ->route('alumni.index')
            ->with('success', "Data alumni \"{$alumni->nama}\" berhasil diperbarui.");
    }

    public function destroy(Alumni $alumni)
    {
        $nama = $alumni->nama;
        $alumni->delete();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'actor_name' => auth()->user()->name,
            'actor_role' => auth()->user()->roles->first()->name ?? 'User',
            'action' => 'delete_alumni',
            'description' => "Menghapus data alumni \"{$nama}\"",
        ]);

        return redirect()
            ->route('alumni.index')
            ->with('success', "Data alumni \"{$nama}\" berhasil dihapus.");
    }
}
