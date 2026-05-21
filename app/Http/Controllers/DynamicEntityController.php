<?php

namespace App\Http\Controllers;

use App\Models\DynamicEntity;
use App\Models\DynamicField;
use App\Models\ActivityLog;
use App\Services\ExportService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class DynamicEntityController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->get('category');

        $entities = DynamicEntity::with(['fields', 'parent', 'creator'])
            ->withCount('records')
            ->when($category, fn($q) => $q->where('root_category', $category))
            ->orderBy('root_category')
            ->orderBy('sort_order')
            ->paginate(20);

        return view('entities.index', compact('entities', 'category'));
    }

    public function create()
    {
        return view('entities.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'root_category' => ['required', Rule::in(['dosen', 'mahasiswa'])],
            'parent_id' => 'prohibited',
            'icon' => 'nullable|string|max:50',
            'fields' => 'required|array|min:1',
            'fields.*.name' => 'required|string|max:255',
            'fields.*.type' => ['required', Rule::in(['text', 'textarea', 'number', 'date', 'select', 'file', 'email', 'phone', 'url'])],
            'fields.*.is_required' => 'boolean',
            'fields.*.is_filterable' => 'boolean',
            'fields.*.is_aggregatable' => 'boolean',
            'fields.*.show_in_table' => 'boolean',
            'fields.*.options' => 'nullable|array',
            'fields.*.options.choices' => 'nullable|string',
            'fields.*.options.max_length' => 'nullable|integer|min:1',
            'fields.*.options.min' => 'nullable|numeric',
            'fields.*.options.max' => 'nullable|numeric',
        ]);

        $entity = DynamicEntity::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'root_category' => $request->root_category,
            'parent_id' => null,
            'created_by' => auth()->id(),
            'icon' => $request->icon ?? 'folder',
        ]);

        if (auth()->user()->hasRole('Kaprodi')) {
            ActivityLog::create([
                'user_id' => auth()->id(),
                'actor_name' => auth()->user()->name,
                'actor_role' => 'Kaprodi',
                'action' => 'create_category',
                'description' => "Membuat kategori baru \"{$entity->name}\"",
            ]);
        }

        // Create fields
        foreach ($request->fields as $index => $fieldData) {
            $options = null;
            if (!empty($fieldData['options'])) {
                $options = $fieldData['options'];
                // Parse comma-separated choices for select fields
                if (isset($options['choices']) && is_string($options['choices'])) {
                    $options['choices'] = array_map('trim', explode(',', $options['choices']));
                }
            }

            $entity->fields()->create([
                'name' => $fieldData['name'],
                'slug' => Str::slug($fieldData['name'], '_'),
                'type' => $fieldData['type'],
                'options' => $options,
                'is_required' => $fieldData['is_required'] ?? false,
                'is_filterable' => $fieldData['is_filterable'] ?? false,
                'is_aggregatable' => $fieldData['is_aggregatable'] ?? false,
                'show_in_table' => $fieldData['show_in_table'] ?? true,
                'sort_order' => $index,
            ]);
        }

        return redirect()
            ->route('entities.show', $entity)
            ->with('success', "Kategori data \"{$entity->name}\" berhasil dibuat dengan " . count($request->fields) . " field.");
    }

    public function show(DynamicEntity $entity)
    {
        $entity->load(['fields', 'records.creator', 'records.programStudi', 'parent', 'children.records']);

        $records = $entity->records()
            ->with(['creator', 'programStudi'])
            ->latest()
            ->paginate(20);

        $tableFields = $entity->getTableFields();

        return view('entities.show', compact('entity', 'records', 'tableFields'));
    }

    public function edit(DynamicEntity $entity)
    {
        $entity->load('fields');
        $parentEntities = DynamicEntity::active()
            ->rootOnly()
            ->where('id', '!=', $entity->id)
            ->get();

        return view('entities.edit', compact('entity', 'parentEntities'));
    }

    public function update(Request $request, DynamicEntity $entity)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'root_category' => ['required', Rule::in(['dosen', 'mahasiswa'])],
            'icon' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ]);

        $entity->update([
            'name' => $request->name,
            'description' => $request->description,
            'root_category' => $request->root_category,
            'icon' => $request->icon ?? $entity->icon,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()
            ->route('entities.show', $entity)
            ->with('success', "Kategori data \"{$entity->name}\" berhasil diperbarui.");
    }

    public function destroy(DynamicEntity $entity)
    {
        $name = $entity->name;

        if (auth()->user()->hasRole('Kaprodi')) {
            ActivityLog::create([
                'user_id' => auth()->id(),
                'actor_name' => auth()->user()->name,
                'actor_role' => 'Kaprodi',
                'action' => 'delete_category',
                'description' => "Menghapus kategori \"{$name}\"",
            ]);
        }

        $entity->delete();

        return redirect()
            ->route('dashboard')
            ->with('success', "Kategori data \"{$name}\" beserta seluruh datanya berhasil dihapus.");
    }

    /**
     * Export entity records to Excel.
     */
    public function exportExcel(DynamicEntity $entity, ExportService $exportService)
    {
        return $exportService->exportToExcel($entity);
    }

    /**
     * Export entity records to PDF.
     */
    public function exportPdf(DynamicEntity $entity, ExportService $exportService)
    {
        return $exportService->exportToPdf($entity);
    }

    /**
     * Get chart data for entity as JSON API.
     */
    public function getChartData(DynamicEntity $entity, ExportService $exportService)
    {
        $charts = $exportService->getEntityChartData($entity);
        
        return response()->json([
            'entity_id' => $entity->id,
            'entity_name' => $entity->name,
            'charts' => $charts,
        ]);
    }
}
