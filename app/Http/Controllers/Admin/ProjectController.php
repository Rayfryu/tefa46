<?php
// app/Http/Controllers/Admin/ProjectController.php

namespace App\Http\Controllers\Admin;

use App\Enums\ProjectStatus;
use App\Http\Controllers\Controller;
use App\Models\Division;
use App\Models\Order;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Project::with(['order.client', 'picGuru', 'division', 'members'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $projects = $query->paginate(10)->withQueryString();

        $stats = [
            'total'     => Project::count(),
            'active'    => Project::where('status', 'active')->count(),
            'revision'  => Project::where('status', 'revision')->count(),
            'completed' => Project::where('status', 'completed')->count(),
        ];

        return view('admin.projects.index', compact('projects', 'stats'));
    }

    public function create()
    {
        // Hanya order yang sudah diapprove & belum punya project
        $orders    = Order::where('status', 'approved')
                          ->doesntHave('project')
                          ->with('client')
                          ->get();
        $gurus     = User::where('role', 'guru')->where('is_active', true)->get();
        $divisions = Division::where('is_active', true)->get();

        return view('admin.projects.create', compact('orders', 'gurus', 'divisions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'order_id'    => 'nullable|exists:orders,id',
            'division_id' => 'nullable|exists:divisions,id',
            'pic_guru_id' => 'nullable|exists:users,id',
            'start_date'  => 'nullable|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
        ]);

        $validated['status']   = ProjectStatus::Active;
        $validated['progress'] = 0;

        $project = Project::create($validated);

        // Update order status ke in_progress
        if ($project->order_id) {
            Order::find($project->order_id)?->update(['status' => 'in_progress']);
        }

        return redirect()->route('admin.projects.show', $project)
            ->with('success', 'Project berhasil dibuat.');
    }

    public function show(Project $project)
    {
        $project->load([
            'order.client', 'picGuru', 'division',
            'members', 'tasks.assignedTo', 'tasks.latestProgress',
        ]);

        $availableSiswa = User::where('role', 'siswa')
            ->where('is_active', true)
            ->whereNotIn('id', $project->members->pluck('id'))
            ->get();

        return view('admin.projects.show', compact('project', 'availableSiswa'));
    }

    public function edit(Project $project)
    {
        $gurus     = User::where('role', 'guru')->where('is_active', true)->get();
        $divisions = Division::where('is_active', true)->get();
        return view('admin.projects.edit', compact('project', 'gurus', 'divisions'));
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'division_id' => 'nullable|exists:divisions,id',
            'pic_guru_id' => 'nullable|exists:users,id',
            'status'      => 'required|string',
            'start_date'  => 'nullable|date',
            'end_date'    => 'nullable|date',
        ]);

        $project->update($validated);

        return back()->with('success', 'Project berhasil diupdate.');
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('admin.projects.index')
            ->with('success', 'Project berhasil dihapus.');
    }

    public function addMember(Request $request, Project $project)
    {
        $request->validate([
            'student_id'      => 'required|exists:users,id',
            'role_in_project' => 'nullable|string|max:100',
        ]);

        // Cek sudah jadi member atau belum
        if ($project->members()->where('student_id', $request->student_id)->exists()) {
            return back()->with('error', 'Siswa sudah menjadi anggota project ini.');
        }

        $project->members()->attach($request->student_id, [
            'role_in_project' => $request->role_in_project,
            'joined_at'       => now(),
        ]);

        return back()->with('success', 'Siswa berhasil ditambahkan ke project.');
    }

    public function removeMember(Project $project, User $user)
    {
        $project->members()->detach($user->id);
        return back()->with('success', 'Siswa berhasil dikeluarkan dari project.');
    }

    public function updateProgress(Request $request, Project $project)
    {
        $request->validate(['progress' => 'required|integer|min:0|max:100']);
        $project->update(['progress' => $request->progress]);
        return back()->with('success', 'Progress berhasil diupdate.');
    }
}