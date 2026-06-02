<?php

namespace App\Http\Controllers;

use App\Models\DynamicEntity;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    /**
     * Display list of pending approval requests.
     */
    public function index()
    {
        $pendingEntities = DynamicEntity::with(['creator', 'fields'])
            ->pending()
            ->orderBy('updated_at', 'desc')
            ->get();

        $rejectedEntities = DynamicEntity::with(['creator', 'fields'])
            ->where('approval_status', 'rejected')
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('approvals.index', compact('pendingEntities', 'rejectedEntities'));
    }

    /**
     * Approve a pending entity request.
     */
    public function approve(DynamicEntity $entity)
    {
        if ($entity->approval_status === 'pending') {
            // Approve creation
            $entity->update([
                'approval_status' => 'approved',
                'rejection_reason' => null,
            ]);

            ActivityLog::create([
                'user_id' => auth()->id(),
                'actor_name' => auth()->user()->name,
                'actor_role' => 'BAAK',
                'action' => 'approve_category',
                'description' => "Menyetujui pembuatan kategori \"{$entity->name}\" yang diajukan oleh {$entity->creator->name}",
            ]);

            return redirect()
                ->route('approvals.index')
                ->with('success', "Kategori \"{$entity->name}\" berhasil disetujui dan sekarang aktif.");
        }

        if ($entity->approval_status === 'pending_delete') {
            // Approve deletion
            $name = $entity->name;
            $creatorName = $entity->creator->name;

            ActivityLog::create([
                'user_id' => auth()->id(),
                'actor_name' => auth()->user()->name,
                'actor_role' => 'BAAK',
                'action' => 'approve_delete_category',
                'description' => "Menyetujui penghapusan kategori \"{$name}\" yang diajukan oleh {$creatorName}",
            ]);

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
            // Reject creation
            $entity->update([
                'approval_status' => 'rejected',
                'rejection_reason' => $request->rejection_reason,
            ]);

            ActivityLog::create([
                'user_id' => auth()->id(),
                'actor_name' => auth()->user()->name,
                'actor_role' => 'BAAK',
                'action' => 'reject_category',
                'description' => "Menolak pembuatan kategori \"{$entity->name}\" — Alasan: {$request->rejection_reason}",
            ]);

            return redirect()
                ->route('approvals.index')
                ->with('success', "Pembuatan kategori \"{$entity->name}\" berhasil ditolak.");
        }

        if ($previousStatus === 'pending_delete') {
            // Reject deletion — restore to approved
            $entity->update([
                'approval_status' => 'approved',
                'rejection_reason' => null,
            ]);

            ActivityLog::create([
                'user_id' => auth()->id(),
                'actor_name' => auth()->user()->name,
                'actor_role' => 'BAAK',
                'action' => 'reject_delete_category',
                'description' => "Menolak penghapusan kategori \"{$entity->name}\" — Alasan: {$request->rejection_reason}",
            ]);

            return redirect()
                ->route('approvals.index')
                ->with('success', "Penghapusan kategori \"{$entity->name}\" ditolak. Kategori tetap aktif.");
        }

        return redirect()
            ->route('approvals.index')
            ->with('error', 'Permintaan tidak valid.');
    }
}
