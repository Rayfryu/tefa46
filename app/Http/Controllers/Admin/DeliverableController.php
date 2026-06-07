<?php
// app/Http/Controllers/Admin/DeliverableController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class DeliverableController extends Controller
{
    public function finalize(Request $request, Project $project)
    {
        abort_if($project->status->value !== 'waiting_approval', 403);

        $request->validate([
            'action' => 'required|in:approve,revision',
            'note'   => 'nullable|string',
        ]);

        if ($request->action === 'approve') {
            $project->update([
                'status'   => 'completed',
                'progress' => 100,
            ]);

            // Update order jadi done
            if ($project->order) {
                $project->order->update(['status' => 'done']);
            }

            return back()->with('success', 'Project ditandai selesai! Client bisa mengakses hasil. 🎉');
        }

        // Revision
        $project->update(['status' => 'revision']);

        return back()->with('success', 'Project dikembalikan untuk revisi.');
    }
}