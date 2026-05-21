<?php

namespace App\Http\Controllers;

use App\Models\DynamicEntity;
use App\Models\DynamicField;
use App\Models\DynamicRecord;
use App\Models\DynamicFileUpload;
use App\Models\ProgramStudi;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DynamicRecordController extends Controller
{
    public function create(DynamicEntity $entity)
    {
        $entity->load('fields');
        $programStudiList = ProgramStudi::where('is_active', true)->get();

        return view('records.create', compact('entity', 'programStudiList'));
    }

    public function store(Request $request, DynamicEntity $entity)
    {
        $entity->load('fields');

        // Build dynamic validation rules
        $rules = ['program_studi_id' => 'nullable|exists:program_studi,id'];
        $fieldMap = [];

        foreach ($entity->fields as $field) {
            $fieldKey = 'field_' . $field->slug;
            $fieldMap[$field->slug] = $fieldKey;

            if ($field->type === 'file') {
                $rules[$fieldKey] = $field->getValidationRule();
            } else {
                $rules[$fieldKey] = $field->getValidationRule();
            }
        }

        $validated = $request->validate($rules);

        // Build data JSON
        $data = [];
        foreach ($entity->fields as $field) {
            $fieldKey = 'field_' . $field->slug;

            if ($field->type === 'file') {
                // Handle file separately
                continue;
            }

            $data[$field->slug] = $request->input($fieldKey);
        }

        $record = DynamicRecord::create([
            'entity_id' => $entity->id,
            'data' => $data,
            'created_by' => auth()->id(),
            'program_studi_id' => $request->program_studi_id,
        ]);

        if (auth()->user()->hasAnyRole(['Kaprodi', 'Dosen'])) {
            $role = auth()->user()->hasRole('Kaprodi') ? 'Kaprodi' : 'Dosen';
            ActivityLog::create([
                'user_id' => auth()->id(),
                'actor_name' => auth()->user()->name,
                'actor_role' => $role,
                'action' => 'create_record',
                'description' => "Mengisi data pada kategori \"{$entity->name}\"",
            ]);
        }

        // Handle file uploads
        foreach ($entity->fields as $field) {
            if ($field->type === 'file') {
                $fieldKey = 'field_' . $field->slug;
                if ($request->hasFile($fieldKey)) {
                    $file = $request->file($fieldKey);
                    $path = $file->store('uploads/' . $entity->slug, 'public');

                    DynamicFileUpload::create([
                        'record_id' => $record->id,
                        'field_id' => $field->id,
                        'original_name' => $file->getClientOriginalName(),
                        'stored_path' => $path,
                        'mime_type' => $file->getMimeType(),
                        'file_size' => $file->getSize(),
                    ]);

                    // Store file reference in data
                    $recordData = $record->data;
                    $recordData[$field->slug] = $path;
                    $record->update(['data' => $recordData]);
                }
            }
        }

        return redirect()
            ->route('entities.view', $entity)
            ->with('success', 'Data berhasil ditambahkan.');
    }

    public function show(DynamicEntity $entity, DynamicRecord $record)
    {
        $entity->load('fields');
        $record->load(['creator', 'programStudi', 'fileUploads.field']);

        return view('records.show', compact('entity', 'record'));
    }

    public function edit(DynamicEntity $entity, DynamicRecord $record)
    {
        $entity->load('fields');
        $record->load('fileUploads');
        $programStudiList = ProgramStudi::where('is_active', true)->get();

        return view('records.edit', compact('entity', 'record', 'programStudiList'));
    }

    public function update(Request $request, DynamicEntity $entity, DynamicRecord $record)
    {
        $entity->load('fields');

        // Build dynamic validation rules
        $rules = ['program_studi_id' => 'nullable|exists:program_studi,id'];

        foreach ($entity->fields as $field) {
            $fieldKey = 'field_' . $field->slug;
            $validationRules = $field->getValidationRule();

            // For file fields on update, make them optional
            if ($field->type === 'file') {
                $validationRules = array_map(fn($r) => $r === 'required' ? 'nullable' : $r, $validationRules);
            }

            $rules[$fieldKey] = $validationRules;
        }

        $request->validate($rules);

        // Build updated data JSON
        $data = $record->data ?? [];
        foreach ($entity->fields as $field) {
            $fieldKey = 'field_' . $field->slug;

            if ($field->type === 'file') {
                if ($request->hasFile($fieldKey)) {
                    $file = $request->file($fieldKey);

                    // Delete old file if exists
                    $oldUpload = $record->fileUploads()->where('field_id', $field->id)->first();
                    if ($oldUpload) {
                        Storage::disk('public')->delete($oldUpload->stored_path);
                        $oldUpload->delete();
                    }

                    $path = $file->store('uploads/' . $entity->slug, 'public');

                    DynamicFileUpload::create([
                        'record_id' => $record->id,
                        'field_id' => $field->id,
                        'original_name' => $file->getClientOriginalName(),
                        'stored_path' => $path,
                        'mime_type' => $file->getMimeType(),
                        'file_size' => $file->getSize(),
                    ]);

                    $data[$field->slug] = $path;
                }
            } else {
                $data[$field->slug] = $request->input($fieldKey);
            }
        }

        $record->update([
            'data' => $data,
            'program_studi_id' => $request->program_studi_id,
        ]);

        return redirect()
            ->route('entities.view', $entity)
            ->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(DynamicEntity $entity, DynamicRecord $record)
    {
        // Delete associated files
        foreach ($record->fileUploads as $fileUpload) {
            Storage::disk('public')->delete($fileUpload->stored_path);
        }

        $record->delete();

        return redirect()
            ->route('entities.view', $entity)
            ->with('success', 'Data berhasil dihapus.');
    }
}
