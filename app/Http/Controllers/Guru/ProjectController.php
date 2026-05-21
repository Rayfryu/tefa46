<?php
// app/Http/Controllers/Guru/ProjectController.php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Project;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::where('pic_guru_id', auth()->id())
            ->with(['division', 'members', 'tasks'])
            ->latest()
            ->paginate(10);

        return view('guru.projects.index', compact('projects'));
    }

    public function show(Project $project)
    {
        abort_if($project->pic_guru_id !== auth()->id(), 403);

        $project->load(['tasks.assignedTo', 'tasks.latestProgress', 'members']);

        return view('guru.projects.show', compact('project'));
    }
}