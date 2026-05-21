<?php
// app/Http/Controllers/Guru/TaskController.php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function store(Request $request, Project $project)
    {
        // Pastikan guru adalah PIC project ini
        abort_if($project->pic_guru_id !== auth()->id(), 403);

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'priority'    => 'required|in:low,medium,high',
            'deadline'    => 'nullable|date',
        ]);

        $validated['project_id'] = $project->id;
        $validated['status']     = 'todo';

        Task::create($validated);

        return back()->with('success', 'Task berhasil ditambahkan.');
    }

    public function destroy(Task $task)
    {
        abort_if($task->project->pic_guru_id !== auth()->id(), 403);
        $project = $task->project;
        $task->delete();
        $project->recalculateProgress();
        return back()->with('success', 'Task dihapus.');
    }
}