<?php

namespace App\Services;

use App\Models\DynamicEntity;
use App\Models\DynamicField;
use App\Models\DynamicRecord;
use App\Models\ProgramStudi;
use Illuminate\Support\Facades\DB;

class DashboardAggregationService
{
    /**
     * Get chart data for all entities with aggregatable fields.
     */
    public function getChartData(): array
    {
        $charts = [];

        $entities = DynamicEntity::active()
            ->with(['fields' => fn($q) => $q->where('is_aggregatable', true)])
            ->withCount('records')
            ->whereHas('records')
            ->get();

        foreach ($entities as $entity) {
            foreach ($entity->fields as $field) {
                $chartEntry = [
                    'entity_name' => $entity->name,
                    'field_name' => $field->name,
                    'field_slug' => $field->slug,
                    'entity_slug' => $entity->slug,
                    'root_category' => $entity->root_category,
                    'type' => $this->getChartType($field),
                    'data' => $this->aggregateFieldData($entity, $field),
                ];
                $charts[] = $chartEntry;
            }

            // If no aggregatable fields, create a simple count chart
            if ($entity->fields->where('is_aggregatable', true)->isEmpty()) {
                $charts[] = [
                    'entity_name' => $entity->name,
                    'field_name' => 'Total Data',
                    'field_slug' => 'total',
                    'entity_slug' => $entity->slug,
                    'root_category' => $entity->root_category,
                    'type' => 'bar',
                    'data' => $this->getEntityCountByProdi($entity),
                ];
            }
        }

        return $charts;
    }

    /**
     * Aggregate data for a specific field of an entity.
     */
    private function aggregateFieldData(DynamicEntity $entity, DynamicField $field): array
    {
        $records = $entity->records()->get();
        $aggregated = [];

        foreach ($records as $record) {
            $value = $record->getFieldValue($field->slug);
            if ($value !== null && $value !== '') {
                $key = (string) $value;
                $aggregated[$key] = ($aggregated[$key] ?? 0) + 1;
            }
        }

        arsort($aggregated);

        return [
            'labels' => array_keys($aggregated),
            'values' => array_values($aggregated),
        ];
    }

    /**
     * Get record count per program studi for an entity.
     */
    private function getEntityCountByProdi(DynamicEntity $entity): array
    {
        $prodiCounts = DynamicRecord::where('entity_id', $entity->id)
            ->select('program_studi_id', DB::raw('COUNT(*) as count'))
            ->groupBy('program_studi_id')
            ->get();

        $labels = [];
        $values = [];

        foreach ($prodiCounts as $pc) {
            $prodi = ProgramStudi::find($pc->program_studi_id);
            $labels[] = $prodi ? $prodi->code : 'Umum';
            $values[] = $pc->count;
        }

        return compact('labels', 'values');
    }

    /**
     * Get chart type based on field type.
     */
    private function getChartType(DynamicField $field): string
    {
        return match ($field->type) {
            'select' => 'donut',
            'number' => 'bar',
            'date' => 'area',
            default => 'bar',
        };
    }

    /**
     * Get overall distribution per program studi.
     */
    public function getProdiDistribution(): array
    {
        $prodiList = ProgramStudi::where('is_active', true)->get();
        $labels = [];
        $dosenCounts = [];
        $mahasiswaCounts = [];

        foreach ($prodiList as $prodi) {
            $labels[] = $prodi->code;

            $dosenCounts[] = DynamicRecord::where('program_studi_id', $prodi->id)
                ->whereHas('entity', fn($q) => $q->where('root_category', 'dosen'))
                ->count();

            $mahasiswaCounts[] = DynamicRecord::where('program_studi_id', $prodi->id)
                ->whereHas('entity', fn($q) => $q->where('root_category', 'mahasiswa'))
                ->count();
        }

        return [
            'labels' => $labels,
            'dosen' => $dosenCounts,
            'mahasiswa' => $mahasiswaCounts,
        ];
    }
}
