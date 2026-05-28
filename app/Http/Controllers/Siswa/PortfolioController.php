<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PortfolioController extends Controller
{
    /**
     * Menampilkan semua portfolio siswa login
     */
    public function index()
    {
        $portfolios = Portfolio::where('student_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('siswa.portfolios.index', compact('portfolios'));
    }

    /**
     * Form create portfolio
     */
    public function create()
    {
        return view('siswa.portfolios.create');
    }

    /**
     * Simpan portfolio baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string',

            'thumbnail'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'skills'         => 'nullable|array',
            'skills.*'       => 'string|max:50',

            'links'          => 'nullable|array',
            'links.*'        => 'nullable|url',

            'type'           => 'nullable|string|max:100',

            'is_published'   => 'nullable|boolean',
        ]);

        // upload thumbnail
        if ($request->hasFile('thumbnail')) {

            $validated['thumbnail'] = $request
                ->file('thumbnail')
                ->store('portfolios', 'public');
        }

        // siswa login
        $validated['student_id'] = auth()->id();

        // publish status
        $validated['is_published'] = $request->boolean('is_published');

        // published_at otomatis
        if ($validated['is_published']) {
            $validated['published_at'] = now();
        }

        Portfolio::create($validated);

        return redirect()
            ->route('siswa.portfolios.index')
            ->with('success', 'Portfolio berhasil dibuat');
    }

    /**
     * Menampilkan detail portfolio
     */
    public function show(Portfolio $portfolio)
    {
        // keamanan:
        // hanya portfolio milik siswa yang login
        if ($portfolio->student_id !== auth()->id()) {
            abort(403);
        }

        return view('siswa.portfolios.show', compact('portfolio'));
    }

    /**
     * Form edit portfolio
     */
    public function edit(Portfolio $portfolio)
    {
        // keamanan
        if ($portfolio->student_id !== auth()->id()) {
            abort(403);
        }

        return view('siswa.portfolios.edit', compact('portfolio'));
    }

    /**
     * Update portfolio
     */
    public function update(Request $request, Portfolio $portfolio)
    {
        // keamanan
        if ($portfolio->student_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'thumbnail'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'skills'       => 'nullable|array',
            'skills.*'     => 'string|max:50',
            'links'        => 'nullable|array',
            'links.*'      => 'nullable|url',
            'type'         => 'nullable|string|max:100',
            'is_published' => 'nullable|boolean',
        ]);

        // upload thumbnail baru
        if ($request->hasFile('thumbnail')) {

            // hapus thumbnail lama
            if (
                $portfolio->thumbnail &&
                Storage::disk('public')->exists($portfolio->thumbnail)
            ) {
                Storage::disk('public')->delete($portfolio->thumbnail);
            }

            // upload thumbnail baru
            $validated['thumbnail'] = $request
                ->file('thumbnail')
                ->store('portfolios', 'public');
        }

        // status publish
        $validated['is_published'] = $request->boolean('is_published');

        // published_at
        $validated['published_at'] = $validated['is_published']
            ? now()
            : null;

        // skills
        $validated['skills'] = $request->skills ?? [];

        // links
        $validated['links'] = array_filter($request->links ?? []);

        $portfolio->update($validated);

        return redirect()
            ->route('siswa.portfolios.show', $portfolio)
            ->with('success', 'Portfolio berhasil diperbarui');
    }

    /**
     * Hapus portfolio
     */
    public function destroy(Portfolio $portfolio)
    {
        // keamanan
        if ($portfolio->student_id !== auth()->id()) {
            abort(403);
        }

        // hapus thumbnail
        if (
            $portfolio->thumbnail &&
            Storage::disk('public')->exists($portfolio->thumbnail)
        ) {
            Storage::disk('public')->delete($portfolio->thumbnail);
        }

        $portfolio->delete();

        return redirect()
            ->route('siswa.portfolios.index')
            ->with('success', 'Portfolio berhasil dihapus');
    }
}
