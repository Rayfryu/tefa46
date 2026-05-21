<?php
// app/Http/Controllers/Admin/UserController.php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Division;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('division')->latest();

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Search by name or email
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users     = $query->paginate(10)->withQueryString();
        $divisions = Division::where('is_active', true)->get();

        $stats = [
            'total'  => User::count(),
            'admin'  => User::where('role', 'admin')->count(),
            'guru'   => User::where('role', 'guru')->count(),
            'siswa'  => User::where('role', 'siswa')->count(),
            'client' => User::where('role', 'client')->count(),
        ];

        return view('admin.users.index', compact('users', 'divisions', 'stats'));
    }

    public function create()
    {
        $divisions = Division::where('is_active', true)->get();
        return view('admin.users.create', compact('divisions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'password'    => 'required|string|min:8|confirmed',
            'role'        => ['required', Rule::enum(UserRole::class)],
            'phone'       => 'nullable|string|max:20',
            'division_id' => 'nullable|exists:divisions,id',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function show(User $user)
    {
        $user->load('division');
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $divisions = Division::where('is_active', true)->get();
        return view('admin.users.edit', compact('user', 'divisions'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'role'        => ['required', Rule::enum(UserRole::class)],
            'phone'       => 'nullable|string|max:20',
            'division_id' => 'nullable|exists:divisions,id',
        ]);

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri.');
        }

        $user->delete();

        return back()->with('success', 'Pengguna berhasil dihapus.');
    }

    public function toggleStatus(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak bisa menonaktifkan akun sendiri.');
        }

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Akun pengguna berhasil {$status}.");
    }

    public function resetPassword(User $user)
    {
        $user->update(['password' => Hash::make('password')]);

        return back()->with('success', 'Password berhasil direset ke "password".');
    }
}