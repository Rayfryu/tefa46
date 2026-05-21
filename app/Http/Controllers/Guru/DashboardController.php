<?php
// app/Http/Controllers/Guru/DashboardController.php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskProgress;

class DashboardController extends Controller
{
    public function index()
    {
        $guru = auth()->user();

        $stats = [
            'total_projects'    => Project::where('pic_guru_id', $guru->id)->count(),
            'active_projects'   => Project::where('pic_guru_id', $guru->id)->where('status', 'active')->count(),
            'pending_reviews'   => TaskProgress::whereHas('task.project', function ($q) use ($guru) {
                                        $q->where('pic_guru_id', $guru->id);
                                    })->doesntHave('review')->count(),
            'completed_projects'=> Project::where('pic_guru_id', $guru->id)->where('status', 'completed')->count(),
        ];

        $my_projects = Project::where('pic_guru_id', $guru->id)
            ->with(['order', 'members'])
            ->latest()
            ->take(5)
            ->get();

        $pending_reviews = TaskProgress::whereHas('task.project', function ($q) use ($guru) {
                                $q->where('pic_guru_id', $guru->id);
                            })
                            ->doesntHave('review')
                            ->with(['task.project', 'user'])
                            ->latest()
                            ->take(5)
                            ->get();

        return view('guru.dashboard', compact('stats', 'my_projects', 'pending_reviews'));
    }
}