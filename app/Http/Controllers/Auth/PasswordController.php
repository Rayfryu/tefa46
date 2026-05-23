<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ], 
        // Ngatur message error
        [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'current_password.current_password' => 'Password yang dimasukkan salah.',
            'password.required' => 'Password baru wajib diisi.',
            'password.confirmed' => 'Tidak sesuai dengan kolom konfirmasi.',
            'password.min' => 'Password minimal 8 karakter.'
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'password-updated');
    }
}
