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
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

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

        $totalColumns = count($tableFields) + 4;
        $lastColumnLetter = $this->getColumnLetter($totalColumns);

        // 1. Center & Merge Category Name as Title
        $sheet->getRowDimension('2')->setRowHeight(35);
        $sheet->mergeCells("A2:{$lastColumnLetter}2");
        $sheet->setCellValue('A2', strtoupper($entity->name));
        $sheet->getStyle('A2')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
                'color' => ['rgb' => '1E5A4A']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ]
        ]);
        
        // Bottom border under title as a horizontal rule line
        $sheet->getStyle("A2:{$lastColumnLetter}2")->applyFromArray([
            'borders' => [
                'bottom' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                    'color' => ['rgb' => '1E5A4A'],
                ],
            ],
        ]);

        // 2. Metadata Info
        // Deskripsi
        $sheet->setCellValue('A4', 'Deskripsi:');
        $sheet->getStyle('A4')->getFont()->setBold(true);
        $sheet->setCellValue('B4', $entity->description ?? '-');
        
        // Kategori
        $sheet->setCellValue('A5', 'Kategori:');
        $sheet->getStyle('A5')->getFont()->setBold(true);
        $sheet->setCellValue('B5', ucfirst($entity->root_category));
        
        // Total Records
        $sheet->setCellValue('A6', 'Total Records:');
        $sheet->getStyle('A6')->getFont()->setBold(true);
        $sheet->setCellValue('B6', $records->count());
        
        // Tanggal Export
        $sheet->setCellValue('A7', 'Tanggal Export:');
        $sheet->getStyle('A7')->getFont()->setBold(true);
        $sheet->setCellValue('B7', now()->timezone('Asia/Jakarta')->format('d M Y H:i:s'));

        // 3. Set Table Headers (Row 9)
        $headerRow = 9;
        $sheet->getRowDimension($headerRow)->setRowHeight(25);
        $column = 1;
        
        // Col 1: No
        $sheet->setCellValue($this->getColumnLetter($column) . $headerRow, 'No');
        $column++;
        
        // Dynamic fields
        foreach ($tableFields as $field) {
            $sheet->setCellValue($this->getColumnLetter($column) . $headerRow, $field->name);
            $column++;
        }
        
        // Col Dibuat Oleh
        $sheet->setCellValue($this->getColumnLetter($column) . $headerRow, 'Dibuat Oleh');
        $column++;
        
        // Col Program Studi
        $sheet->setCellValue($this->getColumnLetter($column) . $headerRow, 'Program Studi');
        $column++;
        
        // Col Tanggal Dibuat
        $sheet->setCellValue($this->getColumnLetter($column) . $headerRow, 'Tanggal Dibuat');

        // Style the headers (Dark Green, White Text, Left/Center Aligned, Bold)
        $headerRange = "A{$headerRow}:{$lastColumnLetter}{$headerRow}";
        $sheet->getStyle($headerRange)->applyFromArray([
            'font' => [
                'bold' => true, 
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID, 
                'startColor' => ['rgb' => '1E5A4A']
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ]
        ]);
        // Specific alignment for table headers
        $sheet->getStyle("A{$headerRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        for ($c = 2; $c <= $totalColumns; $c++) {
            $sheet->getStyle($this->getColumnLetter($c) . $headerRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        }

        // 4. Fill Data Rows (Row 10 onwards)
        $dataRow = 10;
        foreach ($records as $index => $record) {
            $sheet->getRowDimension($dataRow)->setRowHeight(20);
            $column = 1;
            
            // Col 1: No
            $sheet->setCellValue($this->getColumnLetter($column) . $dataRow, $index + 1);
            $column++;
            
            // Dynamic fields
            foreach ($tableFields as $field) {
                $value = $record->getFieldValue($field->slug, '');
                if ($field->type === 'file' && is_string($value)) {
                    $value = url('storage/' . $value);
                }
                
                $sheet->setCellValue($this->getColumnLetter($column) . $dataRow, $value);
                $column++;
            }
            
            // Dibuat Oleh
            $sheet->setCellValue($this->getColumnLetter($column) . $dataRow, $record->creator?->name ?? '-');
            $column++;
            
            // Program Studi
            $sheet->setCellValue($this->getColumnLetter($column) . $dataRow, $record->programStudi?->name ?? 'Umum');
            $column++;
            
            // Tanggal Dibuat
            $sheet->setCellValue($this->getColumnLetter($column) . $dataRow, $record->created_at->format('d/m/Y H:i'));

            // Alternating background colors & thin borders
            $rowRange = "A{$dataRow}:{$lastColumnLetter}{$dataRow}";
            if (($index % 2) !== 0) {
                $sheet->getStyle($rowRange)->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F9F9F9'],
                    ],
                ]);
            }
            
            // Add thin borders to the row cells
            $sheet->getStyle($rowRange)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'DDDDDD'],
                    ],
                ],
            ]);
            
            // Alignments
            $sheet->getStyle("A{$dataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            for ($c = 2; $c <= $totalColumns; $c++) {
                $sheet->getStyle($this->getColumnLetter($c) . $dataRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            }

            $dataRow++;
        }

        // 5. Add Footer
        $footerRow = $dataRow + 1;
        $sheet->getRowDimension($footerRow)->setRowHeight(30);
        $sheet->mergeCells("A{$footerRow}:{$lastColumnLetter}{$footerRow}");
        $sheet->setCellValue("A{$footerRow}", 'Dokumen ini dihasilkan oleh SILAKU FSIP - Sistem Pelaporan IKU');
        $sheet->getStyle("A{$footerRow}")->applyFromArray([
            'font' => [
                'italic' => true,
                'size' => 10,
                'color' => ['rgb' => '999999']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'top' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'DDDDDD'],
                ],
            ],
        ]);

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

    /**
     * Export Alumni records to Excel file (.xlsx).
     */
    public function exportAlumniToExcel($alumnis, string $fileName = null)
    {
        $fileName = $fileName ?? "alumni_" . date('Y-m-d_His') . '.xlsx';

        // Create spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Alumni');

        $totalColumns = 8;
        $lastColumnLetter = 'H';

        // 1. Center & Merge Category Name as Title
        $sheet->getRowDimension('2')->setRowHeight(35);
        $sheet->mergeCells("A2:{$lastColumnLetter}2");
        $sheet->setCellValue('A2', 'DATA ALUMNI');
        $sheet->getStyle('A2')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
                'color' => ['rgb' => '1E5A4A']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ]
        ]);
        
        // Bottom border under title as a horizontal rule line
        $sheet->getStyle("A2:{$lastColumnLetter}2")->applyFromArray([
            'borders' => [
                'bottom' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                    'color' => ['rgb' => '1E5A4A'],
                ],
            ],
        ]);

        // 2. Metadata Info
        // Kategori
        $sheet->setCellValue('A4', 'Kategori:');
        $sheet->getStyle('A4')->getFont()->setBold(true);
        $sheet->setCellValue('B4', 'Alumni');
        
        // Total Records
        $sheet->setCellValue('A5', 'Total Records:');
        $sheet->getStyle('A5')->getFont()->setBold(true);
        $sheet->setCellValue('B5', $alumnis->count());
        
        // Tanggal Export
        $sheet->setCellValue('A6', 'Tanggal Export:');
        $sheet->getStyle('A6')->getFont()->setBold(true);
        $sheet->setCellValue('B6', now()->timezone('Asia/Jakarta')->format('d M Y H:i:s'));

        // 3. Set Table Headers (Row 8)
        $headerRow = 8;
        $sheet->getRowDimension($headerRow)->setRowHeight(25);
        
        $headers = [
            'No',
            'Nama',
            'Nama Perusahaan',
            'Posisi',
            'Lokasi',
            'Program Studi',
            'Diinput Oleh',
            'Tanggal Dibuat'
        ];

        foreach ($headers as $index => $header) {
            $colLetter = $this->getColumnLetter($index + 1);
            $sheet->setCellValue($colLetter . $headerRow, $header);
        }

        // Style the headers (Dark Green, White Text, Left/Center Aligned, Bold)
        $headerRange = "A{$headerRow}:{$lastColumnLetter}{$headerRow}";
        $sheet->getStyle($headerRange)->applyFromArray([
            'font' => [
                'bold' => true, 
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID, 
                'startColor' => ['rgb' => '1E5A4A']
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ]
        ]);
        
        // Specific alignment for table headers
        $sheet->getStyle("A{$headerRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        for ($c = 2; $c <= $totalColumns; $c++) {
            $sheet->getStyle($this->getColumnLetter($c) . $headerRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        }

        // 4. Fill Data Rows (Row 9 onwards)
        $dataRow = 9;
        foreach ($alumnis as $index => $alumni) {
            $sheet->getRowDimension($dataRow)->setRowHeight(20);
            
            $sheet->setCellValue('A' . $dataRow, $index + 1);
            $sheet->setCellValue('B' . $dataRow, $alumni->nama);
            $sheet->setCellValue('C' . $dataRow, $alumni->nama_perusahaan);
            $sheet->setCellValue('D' . $dataRow, $alumni->posisi);
            $sheet->setCellValue('E' . $dataRow, $alumni->lokasi);
            $sheet->setCellValue('F' . $dataRow, $alumni->programStudi?->name ?? 'Umum');
            $sheet->setCellValue('G' . $dataRow, $alumni->creator?->name ?? '-');
            $sheet->setCellValue('H' . $dataRow, $alumni->created_at->timezone('Asia/Jakarta')->format('d/m/Y H:i'));

            // Alternating background colors & thin borders
            $rowRange = "A{$dataRow}:{$lastColumnLetter}{$dataRow}";
            if (($index % 2) !== 0) {
                $sheet->getStyle($rowRange)->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F9F9F9'],
                    ],
                ]);
            }
            
            // Add thin borders to the row cells
            $sheet->getStyle($rowRange)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'DDDDDD'],
                    ],
                ],
            ]);
            
            // Alignments
            $sheet->getStyle("A{$dataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            for ($c = 2; $c <= $totalColumns; $c++) {
                $sheet->getStyle($this->getColumnLetter($c) . $dataRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            }

            $dataRow++;
        }

        // 5. Add Footer
        $footerRow = $dataRow + 1;
        $sheet->getRowDimension($footerRow)->setRowHeight(30);
        $sheet->mergeCells("A{$footerRow}:{$lastColumnLetter}{$footerRow}");
        $sheet->setCellValue("A{$footerRow}", 'Dokumen ini dihasilkan oleh SILAKU FSIP - Sistem Pelaporan IKU');
        $sheet->getStyle("A{$footerRow}")->applyFromArray([
            'font' => [
                'italic' => true,
                'size' => 10,
                'color' => ['rgb' => '999999']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'top' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'DDDDDD'],
                ],
            ],
        ]);

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
     * Export Alumni records to PDF file.
     */
    public function exportAlumniToPdf($alumnis, string $fileName = null)
    {
        $fileName = $fileName ?? "alumni_" . date('Y-m-d_His') . '.pdf';
        
        $pdf = Pdf::loadView('exports.alumni-pdf', [
            'alumnis' => $alumnis,
        ])->setPaper('a4', 'landscape');

        return $pdf->download($fileName);
    }
}
