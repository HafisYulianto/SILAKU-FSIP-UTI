<?php

namespace App\Http\Controllers;

use App\Models\DynamicEntity;
use App\Models\DynamicRecord;
use App\Models\User;
use App\Models\ProgramStudi;
use App\Services\DashboardAggregationService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected DashboardAggregationService $aggregationService;

    public function __construct(DashboardAggregationService $aggregationService)
    {
        $this->aggregationService = $aggregationService;
    }

    public function index(Request $request)
    {
        $user = $request->user();

        // Core stats
        $stats = [
            'total_dosen' => DynamicRecord::whereHas('entity', fn($q) => $q->where('root_category', 'dosen'))->count(),
            'total_mahasiswa' => DynamicRecord::whereHas('entity', fn($q) => $q->where('root_category', 'mahasiswa'))->count(),
            'total_alumni' => \App\Models\Alumni::count(),
            'total_entities' => DynamicEntity::active()->count(),
            'total_prodi' => ProgramStudi::where('is_active', true)->count(),
        ];

        // Get all active entities grouped by category
        $dosenEntities = DynamicEntity::active()
            ->byCategory('dosen')
            ->withCount('records')
            ->orderBy('sort_order')
            ->get();

        $mahasiswaEntities = DynamicEntity::active()
            ->byCategory('mahasiswa')
            ->withCount('records')
            ->orderBy('sort_order')
            ->get();

        $alumniEntities = \App\Models\Alumni::with('programStudi')->latest()->get();

        // Get aggregation data for charts
        $chartData = $this->aggregationService->getChartData();

        // Recent records
        $recentRecords = DynamicRecord::with(['entity', 'creator', 'programStudi'])
            ->latest()
            ->limit(10)
            ->get();

        // Program Studi distribution
        $prodiDistribution = $this->aggregationService->getProdiDistribution();

        return view('dashboard.index', compact(
            'stats',
            'dosenEntities',
            'mahasiswaEntities',
            'alumniEntities',
            'chartData',
            'recentRecords',
            'prodiDistribution'
        ));
    }

    /**
     * Pimpinan browse page: view all entities and their records by category (read-only).
     */
    public function pimpinanBrowse(Request $request, string $category)
    {
        $entities = DynamicEntity::active()
            ->byCategory($category)
            ->withCount('records')
            ->with('fields')
            ->orderBy('sort_order')
            ->get();

        // Get total record count for this category
        $totalRecords = DynamicRecord::whereHas('entity', fn($q) => $q->where('root_category', $category))->count();

        // Get selected entity's data if specified
        $selectedEntity = null;
        $records = collect();
        $tableFields = collect();

        if ($request->has('entity_id')) {
            $selectedEntity = DynamicEntity::with('fields')->findOrFail($request->entity_id);
            $tableFields = $selectedEntity->getTableFields();
            $records = $selectedEntity->records()
                ->with(['creator', 'programStudi'])
                ->latest()
                ->paginate(20);
        }

        return view('dashboard.pimpinan-browse', compact(
            'category',
            'entities',
            'totalRecords',
            'selectedEntity',
            'records',
            'tableFields'
        ));
    }

    public function alumniMap(Request $request)
    {
        $mapRecords = \App\Models\Alumni::with('programStudi')->orderBy('nama', 'asc')->get();
        return view('dashboard.map', compact('mapRecords'));
    }

    public function print(Request $request)
    {
        // Core stats
        $stats = [
            'total_dosen' => DynamicRecord::whereHas('entity', fn($q) => $q->where('root_category', 'dosen'))->count(),
            'total_mahasiswa' => DynamicRecord::whereHas('entity', fn($q) => $q->where('root_category', 'mahasiswa'))->count(),
            'total_alumni' => \App\Models\Alumni::count(),
            'total_entities' => DynamicEntity::active()->count(),
            'total_prodi' => ProgramStudi::where('is_active', true)->count(),
        ];

        // Get all active entities grouped by category
        $dosenEntities = DynamicEntity::active()
            ->byCategory('dosen')
            ->withCount('records')
            ->orderBy('sort_order')
            ->get();

        $mahasiswaEntities = DynamicEntity::active()
            ->byCategory('mahasiswa')
            ->withCount('records')
            ->orderBy('sort_order')
            ->get();

        $alumniEntities = \App\Models\Alumni::with('programStudi')->latest()->get();

        // Get aggregation data for charts
        $chartData = $this->aggregationService->getChartData();

        // Recent records
        $recentRecords = DynamicRecord::with(['entity', 'creator', 'programStudi'])
            ->latest()
            ->limit(10)
            ->get();

        // Program Studi distribution
        $prodiDistribution = $this->aggregationService->getProdiDistribution();

        return view('dashboard.print', compact(
            'stats',
            'dosenEntities',
            'mahasiswaEntities',
            'alumniEntities',
            'chartData',
            'recentRecords',
            'prodiDistribution'
        ));
    }
}
