<?php
// app/Http/Controllers/Guru/DeliverableController.php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectDeliverable;
use Illuminate\Http\Request;

class DeliverableController extends Controller
{
    public function store(Request $request, Project $project)
    {
        abort_if($project->pic_guru_id !== auth()->id(), 403);

        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'type'        => 'required|in:file,link,image',
            'file'        => 'required_if:type,file,image|file|max:102400', // max 100MB
            'link_url'    => 'required_if:type,link|nullable|url',
            'is_final'    => 'boolean',
        ]);

        $data = [
            'project_id'  => $project->id,
            'uploaded_by' => auth()->id(),
            'title'       => $request->title,
            'description' => $request->description,
            'type'        => $request->type,
            'is_final'    => $request->boolean('is_final'),
        ];

        if ($request->type === 'link') {
            $data['link_url'] = $request->link_url;
        } else {
            $file = $request->file('file');
            $path = $file->store('project-deliverables/' . $project->id, 'public');
            $data['file_path']     = $path;
            $data['original_name'] = $file->getClientOriginalName();
            $data['file_size']     = $file->getSize();
        }

        ProjectDeliverable::create($data);

        return back()->with('success', 'Hasil project berhasil diupload.');
    }

    public function destroy(ProjectDeliverable $deliverable)
    {
        abort_if($deliverable->project->pic_guru_id !== auth()->id(), 403);

        if ($deliverable->file_path) {
            \Storage::disk('public')->delete($deliverable->file_path);
        }

        $deliverable->delete();

        return back()->with('success', 'File berhasil dihapus.');
    }

    public function markComplete(Request $request, Project $project)
    {
        abort_if($project->pic_guru_id !== auth()->id(), 403);
        abort_if($project->deliverables()->where('is_final', true)->count() === 0, 422,
            'Upload minimal satu hasil final sebelum menyelesaikan project.');

        // Cek semua task sudah selesai
        $undoneTasks = $project->tasks()->whereNotIn('status', ['done'])->count();
        if ($undoneTasks > 0) {
            return back()->with('error', "Masih ada {$undoneTasks} task yang belum selesai.");
        }

        $project->update([
            'status'   => 'waiting_approval',
            'progress' => 100,
        ]);

        return back()->with('success', 'Project disubmit untuk persetujuan admin! ✅');
    }
}