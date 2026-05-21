<?php

namespace App\Services;

use App\Models\DynamicEntity;
use App\Models\DynamicRecord;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ExportService
{
    /**
     * Export records to Excel file (.xlsx).
     */
    public function exportToExcel(DynamicEntity $entity, string $fileName = null)
    {
        $fileName = $fileName ?? "{$entity->slug}_" . date('Y-m-d_His') . '.xlsx';
        
        $records = $entity->records()
            ->with(['creator', 'programStudi'])
            ->get();
        
        $tableFields = $entity->getTableFields();

        // Create spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data');

        // Set column headers
        $row = 1;
        $column = 1;
        foreach ($tableFields as $field) {
            $columnLetter = $this->getColumnLetter($column);
            $sheet->setCellValue($columnLetter . $row, $field->name);
            $column++;
        }
        $columnLetter = $this->getColumnLetter($column);
        $sheet->setCellValue($columnLetter . $row, 'Dibuat Oleh');
        $column++;
        $columnLetter = $this->getColumnLetter($column);
        $sheet->setCellValue($columnLetter . $row, 'Program Studi');
        $column++;
        $columnLetter = $this->getColumnLetter($column);
        $sheet->setCellValue($columnLetter . $row, 'Tanggal Dibuat');

        // Style header row
        $headerRange = 'A1:' . $columnLetter . '1';
        $sheet->getStyle($headerRange)->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1E5A4A']],
        ]);

        // Add data rows
        $row = 2;
        foreach ($records as $record) {
            $column = 1;
            
            foreach ($tableFields as $field) {
                $value = $record->getFieldValue($field->slug, '');
                
                // Handle file fields
                if ($field->type === 'file' && is_string($value)) {
                    $value = url('storage/' . $value);
                }
                
                $columnLetter = $this->getColumnLetter($column);
                $sheet->setCellValue($columnLetter . $row, $value);
                $column++;
            }
            
            $columnLetter = $this->getColumnLetter($column);
            $sheet->setCellValue($columnLetter . $row, $record->creator?->name ?? '-');
            $column++;
            $columnLetter = $this->getColumnLetter($column);
            $sheet->setCellValue($columnLetter . $row, $record->programStudi?->name ?? 'Umum');
            $column++;
            $columnLetter = $this->getColumnLetter($column);
            $sheet->setCellValue($columnLetter . $row, $record->created_at->format('Y-m-d H:i:s'));
            
            $row++;
        }

        // Auto-fit columns
        foreach ($sheet->getColumnIterator() as $col) {
            $sheet->getColumnDimension($col->getColumnIndex())->setAutoSize(true);
        }

        // Write file
        $writer = new Xlsx($spreadsheet);
        
        ob_start();
        $writer->save('php://output');
        $xlsxContent = ob_get_clean();

        return response($xlsxContent, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }

    /**
     * Convert column number to letter (1 = A, 2 = B, etc).
     */
    private function getColumnLetter($columnNumber)
    {
        $letter = '';
        while ($columnNumber > 0) {
            $columnNumber--;
            $letter = chr(65 + ($columnNumber % 26)) . $letter;
            $columnNumber = intdiv($columnNumber, 26);
        }
        return $letter;
    }

    /**
     * Export records to PDF file.
     */
    public function exportToPdf(DynamicEntity $entity, string $fileName = null)
    {
        $fileName = $fileName ?? "{$entity->slug}_" . date('Y-m-d_His') . '.pdf';
        
        $records = $entity->records()
            ->with(['creator', 'programStudi', 'fileUploads.field'])
            ->get();
        
        $tableFields = $entity->getTableFields();
        
        $pdf = Pdf::loadView('exports.pdf', [
            'entity' => $entity,
            'records' => $records,
            'tableFields' => $tableFields,
        ])->setPaper('a4', 'landscape');

        return $pdf->download($fileName);
    }

    /**
     * Get chart-friendly data for an entity.
     */
    public function getEntityChartData(DynamicEntity $entity): array
    {
        $charts = [];
        $records = $entity->records()->get();

        // Get aggregatable fields
        $aggregatableFields = $entity->getAggregatableFields();

        if ($aggregatableFields->count() > 0) {
            foreach ($aggregatableFields as $field) {
                $chartEntry = [
                    'field_name' => $field->name,
                    'field_slug' => $field->slug,
                    'field_type' => $field->type,
                    'chart_type' => $this->getChartType($field),
                    'data' => $this->aggregateFieldData($records, $field),
                ];
                $charts[] = $chartEntry;
            }
        } else {
            // If no aggregatable fields, show record count by program studi
            $charts[] = [
                'field_name' => 'Total Records by Program Studi',
                'field_slug' => 'total_by_prodi',
                'field_type' => 'count',
                'chart_type' => 'bar',
                'data' => $this->getRecordCountByProdi($records),
            ];
        }

        return $charts;
    }

    /**
     * Aggregate data for a specific field.
     */
    private function aggregateFieldData($records, $field): array
    {
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
     * Get record count per program studi.
     */
    private function getRecordCountByProdi($records): array
    {
        $prodiCounts = [];

        foreach ($records as $record) {
            $prodiName = $record->programStudi ? $record->programStudi->name : 'Umum';
            $prodiCounts[$prodiName] = ($prodiCounts[$prodiName] ?? 0) + 1;
        }

        return [
            'labels' => array_keys($prodiCounts),
            'values' => array_values($prodiCounts),
        ];
    }

    /**
     * Get chart type based on field type.
     */
    private function getChartType($field): string
    {
        return match ($field->type) {
            'select' => 'doughnut',
            'number' => 'bar',
            'date' => 'line',
            default => 'bar',
        };
    }
}
