<?php
// app/Http/Controllers/Siswa/TaskController.php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskProgress;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::where('assigned_to', auth()->id())
            ->with(['project', 'latestProgress'])
            ->orderByRaw("FIELD(status, 'in_progress', 'revision', 'todo', 'review', 'done')")
            ->paginate(10);

        return view('siswa.tasks.index', compact('tasks'));
    }

    public function show(Task $task)
    {
        abort_if($task->assigned_to !== auth()->id(), 403);

        $task->load(['project.picGuru', 'progresses.user', 'progresses.review.reviewer']);

        return view('siswa.tasks.show', compact('task'));
    }

    public function start(Task $task)
    {
        abort_if($task->assigned_to !== auth()->id(), 403);
        abort_if($task->status->value !== 'todo', 403);

        $task->update(['status' => 'in_progress']);

        return back()->with('success', 'Task dimulai! Semangat mengerjakan. 💪');
    }

    public function uploadProgress(Request $request, Task $task)
    {
        abort_if($task->assigned_to !== auth()->id(), 403);

        $request->validate([
            'description'   => 'required|string|min:10',
            'status_update' => 'nullable|string|max:100',
            'files.*'       => 'nullable|file|max:10240', // max 10MB per file
        ]);

        $progress = TaskProgress::create([
            'task_id'       => $task->id,
            'user_id'       => auth()->id(),
            'description'   => $request->description,
            'status_update' => $request->status_update,
        ]);

        // Handle file uploads
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('task-files/' . $task->id, 'public');

                $task->files()->create([
                    'task_progress_id' => $progress->id,
                    'uploaded_by'      => auth()->id(),
                    'filename'         => basename($path),
                    'original_name'    => $file->getClientOriginalName(),
                    'file_path'        => $path,
                    'file_type'        => $file->getClientMimeType(),
                    'file_size'        => $file->getSize(),
                ]);
            }
        }

        // Update task status ke review
        $task->update(['status' => 'review']);

        return back()->with('success', 'Progress berhasil diupload! Menunggu review guru.');
    }
}