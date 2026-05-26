<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // FITUR DELETE IMAGE LAMA & DIGANTI IMAGE BARU

        // simpan path lama
        $oldImage = $user->image;

        $user->fill($request->validated());

        // Upload image baru
        if ($request->hasFile('image')) {

            // hapus foto lama
            if ($oldImage && Storage::disk('public')->exists($oldImage)) {
                Storage::disk('public')->delete($oldImage);
            }

            // simpan foto baru
            $path = $request->file('image')->store('profiles', 'public');

            $user->image = $path;
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')
            ->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag(
            'userDeletion',
            [
                'password' => ['required', 'current_password'],
            ],
            // Ngatur message error
            [
                'password.current_password' => 'Password yang dimasukkan salah',
                'password.required' => 'Password wajib diisi'
            ]
        );

        $user = $request->user();

        Auth::logout();

        //jika user hapus akun, maka foto profile kehapus
        if ($user->image && Storage::disk('public')->exists($user->image)) {
            Storage::disk('public')->delete($user->image);
        }

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function destroyImage(Request $request): RedirectResponse
    {
        $user = $request->user();

        // hapus file dari storage
        if ($user->image && Storage::disk('public')->exists($user->image)) {
            Storage::disk('public')->delete($user->image);
        }

        // kosongkan kolom image di database
        $user->image = null;
        $user->save();

        return Redirect::route('profile.edit')
            ->with('status', 'photo-deleted');
    }
}
