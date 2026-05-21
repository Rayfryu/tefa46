<?php
// app/Http/Controllers/Admin/TaskController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function store(Request $request, Project $project)
    {
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

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'priority'    => 'required|in:low,medium,high',
            'status'      => 'required|string',
            'deadline'    => 'nullable|date',
        ]);

        $task->update($validated);
        $task->project->recalculateProgress();

        return back()->with('success', 'Task berhasil diupdate.');
    }

    public function destroy(Task $task)
    {
        $project = $task->project;
        $task->delete();
        $project->recalculateProgress();

        return back()->with('success', 'Task berhasil dihapus.');
    }
}