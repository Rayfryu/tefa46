<?php
// app/Http/Controllers/Client/DeliverableController.php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Project;

class DeliverableController extends Controller
{
    public function index()
    {
        $projects = Project::whereHas('order', function ($q) {
                        $q->where('client_id', auth()->id());
                    })
                    ->with(['order', 'finalDeliverables'])
                    ->latest()
                    ->paginate(10);

        return view('client.projects.index', compact('projects'));
    }

    public function show(Project $project)
    {
        // Pastikan project milik client ini
        abort_if($project->order?->client_id !== auth()->id(), 403);
        abort_if($project->status->value !== 'completed', 403, 'Hasil project belum tersedia.');

        $project->load(['deliverables.uploadedBy', 'picGuru', 'order']);

        return view('client.projects.show', compact('project'));
    }
}