<?php
// app/Http/Controllers/Guru/ReviewController.php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\TaskProgress;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $pendingReviews = TaskProgress::whereHas('task.project', function ($q) {
                                $q->where('pic_guru_id', auth()->id());
                            })
                            ->doesntHave('review')
                            ->with(['task.project', 'user', 'task.files'])
                            ->latest()
                            ->paginate(10);

        return view('guru.reviews.index', compact('pendingReviews'));
    }

    public function store(Request $request, TaskProgress $progress)
    {
        // Pastikan guru yang review adalah PIC project
        abort_if(
            $progress->task->project->pic_guru_id !== auth()->id(),
            403,
            'Kamu bukan PIC project ini.'
        );

        $request->validate([
            'status' => 'required|in:approved,revision',
            'note'   => 'nullable|string',
        ]);

        // Cek sudah pernah direview belum
        if ($progress->review) {
            return back()->with('error', 'Progress ini sudah direview.');
        }

        Review::create([
            'task_progress_id' => $progress->id,
            'reviewer_id'      => auth()->id(),
            'status'           => $request->status,
            'note'             => $request->note,
        ]);

        // Update status task berdasarkan hasil review
        $newTaskStatus = $request->status === 'approved' ? 'done' : 'revision';
        $progress->task->update(['status' => $newTaskStatus]);

        // Recalculate progress project
        $progress->task->project->recalculateProgress();

        $msg = $request->status === 'approved'
            ? 'Progress disetujui! Task ditandai selesai. ✅'
            : 'Progress dikembalikan untuk revisi.';

        return back()->with('success', $msg);
    }
}