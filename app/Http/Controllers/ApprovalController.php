<?php

namespace App\Http\Controllers;

use App\Models\DynamicEntity;
use App\Models\DynamicRecord;
use App\Models\DynamicFileUpload;
use App\Models\Alumni;
use App\Models\ActivityLog;
use App\Models\DataApprovalRequest;
use App\Services\GeocodingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ApprovalController extends Controller
{
    /**
     * Display list of pending approval requests (categories + data).
     */
    public function index()
    {
        // Existing entity-level approvals
        $pendingEntities = DynamicEntity::with(['creator', 'fields'])
            ->pending()
            ->orderBy('updated_at', 'desc')
            ->get();

        $rejectedEntities = DynamicEntity::with(['creator', 'fields'])
            ->where('approval_status', 'rejected')
            ->orderBy('updated_at', 'desc')
            ->get();

        // New: data-level approval requests (alumni & records)
        $pendingDataRequests = DataApprovalRequest::with(['requester', 'entity', 'record', 'alumni'])
            ->pending()
            ->orderBy('created_at', 'desc')
            ->get();

        return view('approvals.index', compact(
            'pendingEntities',
            'rejectedEntities',
            'pendingDataRequests'
        ));
    }

    /**
     * Approve a pending entity request.
     */
    public function approve(DynamicEntity $entity)
    {
        if ($entity->approval_status === 'pending') {
            $entity->update([
                'approval_status' => 'approved',
                'rejection_reason' => null,
            ]);

            return redirect()
                ->route('approvals.index')
                ->with('success', "Kategori \"{$entity->name}\" berhasil disetujui dan sekarang aktif.");
        }

        if ($entity->approval_status === 'pending_delete') {
            $name        = $entity->name;
            $creatorName = $entity->creator->name;

            $entity->delete();

            return redirect()
                ->route('approvals.index')
                ->with('success', "Kategori \"{$name}\" berhasil dihapus sesuai permintaan.");
        }

        return redirect()
            ->route('approvals.index')
            ->with('error', 'Permintaan tidak valid.');
    }

    /**
     * Reject a pending entity request.
     */
    public function reject(Request $request, DynamicEntity $entity)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $previousStatus = $entity->approval_status;

        if ($previousStatus === 'pending') {
            $entity->update([
                'approval_status'  => 'rejected',
                'rejection_reason' => $request->rejection_reason,
            ]);

            return redirect()
                ->route('approvals.index')
                ->with('success', "Pembuatan kategori \"{$entity->name}\" berhasil ditolak.");
        }

        if ($previousStatus === 'pending_delete') {
            $entity->update([
                'approval_status'  => 'approved',
                'rejection_reason' => null,
            ]);

            return redirect()
                ->route('approvals.index')
                ->with('success', "Penghapusan kategori \"{$entity->name}\" ditolak. Kategori tetap aktif.");
        }

        return redirect()
            ->route('approvals.index')
            ->with('error', 'Permintaan tidak valid.');
    }

    /**
     * Bulk approve selected data requests.
     */
    public function bulkApprove(Request $request)
    {
        $request->validate([
            'request_ids'   => 'required|array|min:1',
            'request_ids.*' => 'integer|exists:data_approval_requests,id',
        ]);

        $requests = DataApprovalRequest::with(['entity', 'record.fileUploads', 'alumni'])
            ->whereIn('id', $request->request_ids)
            ->where('status', 'pending')
            ->get();

        $approved = 0;

        foreach ($requests as $dataRequest) {
            $this->executeApproval($dataRequest);
            $approved++;
        }

        return redirect()
            ->route('approvals.index')
            ->with('success', "{$approved} permintaan data berhasil disetujui.");
    }

    /**
     * Bulk reject selected data requests.
     */
    public function bulkReject(Request $request)
    {
        $request->validate([
            'request_ids'      => 'required|array|min:1',
            'request_ids.*'    => 'integer|exists:data_approval_requests,id',
            'rejection_reason' => 'required|string|max:500',
        ]);

        $requests = DataApprovalRequest::whereIn('id', $request->request_ids)
            ->where('status', 'pending')
            ->get();

        $rejected = 0;

        foreach ($requests as $dataRequest) {
            $dataRequest->update([
                'status'           => 'rejected',
                'rejection_reason' => $request->rejection_reason,
            ]);
            $rejected++;
        }

        return redirect()
            ->route('approvals.index')
            ->with('success', "{$rejected} permintaan data berhasil ditolak.");
    }

    /**
     * Execute a single approval request (create or delete).
     */
    private function executeApproval(DataApprovalRequest $dataRequest): void
    {
        if ($dataRequest->action === 'create') {
            if ($dataRequest->type === 'alumni') {
                $payload = $dataRequest->payload;
                $alumni = Alumni::create([
                    'nama'             => $payload['nama'],
                    'nama_perusahaan'  => $payload['nama_perusahaan'],
                    'posisi'           => $payload['posisi'],
                    'lokasi'           => $payload['lokasi'],
                    'program_studi_id' => $payload['program_studi_id'] ?? null,
                    'lat'              => $payload['lat'] ?? null,
                    'lng'              => $payload['lng'] ?? null,
                    'created_by'       => $payload['created_by'],
                ]);
                // Geocode the new alumni's location if not provided manually
                if (empty($alumni->lat) || empty($alumni->lng)) {
                    try {
                        app(GeocodingService::class)->geocodeAlumni($alumni);
                    } catch (\Exception $e) {
                        // Non-critical
                    }
                }
            } elseif ($dataRequest->type === 'record' && $dataRequest->entity_id) {
                $payload = $dataRequest->payload;
                $data = collect($payload)->reject(fn($v, $k) => str_starts_with($k, '_'))->toArray();

                DynamicRecord::create([
                    'entity_id'        => $dataRequest->entity_id,
                    'data'             => $data,
                    'created_by'       => $payload['_created_by'] ?? auth()->id(),
                    'program_studi_id' => $payload['_program_studi_id'] ?? null,
                ]);
            }
        } elseif ($dataRequest->action === 'delete') {
            if ($dataRequest->type === 'alumni' && $dataRequest->alumni) {
                $dataRequest->alumni->delete();
            } elseif ($dataRequest->type === 'record' && $dataRequest->record) {
                foreach ($dataRequest->record->fileUploads as $fileUpload) {
                    Storage::disk('public')->delete($fileUpload->stored_path);
                }
                $dataRequest->record->delete();
            }
        }

        $dataRequest->update(['status' => 'approved']);
    }
}
