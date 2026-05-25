<?php
// app/Http/Controllers/Siswa/ProjectController.php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Project;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = auth()->user()
            ->projectsAsSiswa()
            ->with(['picGuru', 'division', 'tasks'])
            ->latest()
            ->paginate(10);

        return view('siswa.projects.index', compact('projects'));
    }

    public function show(Project $project)
    {
        // Pastikan siswa adalah anggota project ini
        abort_if(
            !$project->members()->where('student_id', auth()->id())->exists(),
            403,
            'Kamu bukan anggota project ini.'
        );

        $project->load([
            'picGuru',
            'division',
            'members',
            'tasks' => fn($q) => $q->where('assigned_to', auth()->id()),
            'tasks.latestProgress',
        ]);

        return view('siswa.projects.show', compact('project'));
    }
}