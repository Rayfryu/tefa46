<?php
// app/Http/Middleware/RoleMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $userRole = auth()->user()->role->value;

        if (!in_array($userRole, $roles)) {
            abort(403, 'Akses ditolak. Kamu tidak memiliki izin untuk halaman ini.');
        }

        // Cek apakah user aktif
        if (!auth()->user()->is_active) {
            auth()->logout();
            return redirect()->route('login')
                ->withErrors(['email' => 'Akun kamu telah dinonaktifkan.']);
        }

        return $next($request);
    }
}