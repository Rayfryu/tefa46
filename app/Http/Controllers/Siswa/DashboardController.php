<?php
// app/Http/Controllers/Siswa/DashboardController.php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;

class DashboardController extends Controller
{
    public function index()
    {
        $siswa = auth()->user();

        $stats = [
            'total_projects'  => $siswa->projectsAsSiswa()->count(),
            'active_projects' => $siswa->projectsAsSiswa()->where('status', 'active')->count(),
            'total_tasks'     => Task::where('assigned_to', $siswa->id)->count(),
            'done_tasks'      => Task::where('assigned_to', $siswa->id)->where('status', 'done')->count(),
            'pending_tasks'   => Task::where('assigned_to', $siswa->id)
                                     ->whereIn('status', ['todo', 'in_progress', 'revision'])
                                     ->count(),
        ];

        $my_tasks = Task::where('assigned_to', $siswa->id)
            ->whereIn('status', ['todo', 'in_progress', 'revision'])
            ->with('project')
            ->orderBy('deadline')
            ->take(5)
            ->get();

        $my_projects = $siswa->projectsAsSiswa()
            ->with('picGuru')
            ->latest()
            ->take(5)
            ->get();

        return view('siswa.dashboard', compact('stats', 'my_tasks', 'my_projects'));
    }
}