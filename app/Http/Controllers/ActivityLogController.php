<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        // Get logs for Kaprodi
        $kaprodiLogs = ActivityLog::with('user')
            ->where('actor_role', 'Kaprodi')
            ->latest()
            ->paginate(15, ['*'], 'kaprodi_page')
            ->withQueryString();

        // Get logs for Dosen
        $dosenLogs = ActivityLog::with('user')
            ->where('actor_role', 'Dosen')
            ->latest()
            ->paginate(15, ['*'], 'dosen_page')
            ->withQueryString();

        return view('activities.index', compact('kaprodiLogs', 'dosenLogs'));
    }

    public function destroy(ActivityLog $activity)
    {
        if (!auth()->user()->hasRole('BAAK')) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        $activity->delete();

        return redirect()->back()->with('success', 'Log aktivitas berhasil dihapus.');
    }

    public function clear(Request $request)
    {
        if (!auth()->user()->hasRole('BAAK')) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        $request->validate([
            'filter' => 'required|in:hour,day,week,month,year,all',
        ]);

        $filter = $request->filter;
        $query = ActivityLog::query();

        switch ($filter) {
            case 'hour':
                $query->where('created_at', '<', now()->subHour());
                $description = 'lebih dari 1 jam yang lalu';
                break;
            case 'day':
                $query->where('created_at', '<', now()->subDay());
                $description = 'lebih dari 1 hari yang lalu';
                break;
            case 'week':
                $query->where('created_at', '<', now()->subWeek());
                $description = 'lebih dari 1 minggu yang lalu';
                break;
            case 'month':
                $query->where('created_at', '<', now()->subMonth());
                $description = 'lebih dari 1 bulan yang lalu';
                break;
            case 'year':
                $query->where('created_at', '<', now()->subYear());
                $description = 'lebih dari 1 tahun yang lalu';
                break;
            case 'all':
            default:
                $description = 'semua data';
                break;
        }

        if ($filter === 'all') {
            ActivityLog::truncate();
        } else {
            $query->delete();
        }

        return redirect()->back()->with('success', "Log aktivitas ({$description}) berhasil dibersihkan.");
    }
}
