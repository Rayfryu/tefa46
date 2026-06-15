{{-- resources/views/errors/403.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>403 — Akses Ditolak</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-surface-50 text-slate-700 flex items-center justify-center min-h-screen"
      style="background:#f8fafc">
    <div class="text-center">
        <div class="w-20 h-20 rounded-2xl bg-red-50 border border-red-100 flex items-center
                    justify-center mx-auto mb-6">
            <span class="text-4xl">🚫</span>
        </div>
        <h1 class="text-7xl font-bold text-brand-500" style="font-family:sans-serif">403</h1>
        <p class="text-xl font-semibold text-slate-800 mt-3">Akses Ditolak</p>
        <p class="text-slate-400 mt-2 text-sm">Kamu tidak memiliki izin untuk mengakses halaman ini.</p>
        <a href="{{ url()->previous() }}"
           class="mt-6 inline-block bg-brand-500 hover:bg-brand-600 text-white
                  px-6 py-3 rounded-xl text-sm font-semibold transition-colors
                  shadow-lg shadow-brand-500/25">
            ← Kembali
        </a>
    </div>
</body>
</html>