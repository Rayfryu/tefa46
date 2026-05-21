{{-- resources/views/errors/403.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>403 — Akses Ditolak</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900 text-white flex items-center justify-center min-h-screen">
    <div class="text-center">
        <h1 class="text-8xl font-bold text-indigo-500">403</h1>
        <p class="text-2xl mt-4">Akses Ditolak</p>
        <p class="text-slate-400 mt-2">Kamu tidak memiliki izin untuk mengakses halaman ini.</p>
        <a href="{{ url()->previous() }}" 
           class="mt-6 inline-block bg-indigo-600 hover:bg-indigo-700 px-6 py-3 rounded-lg transition">
            ← Kembali
        </a>
    </div>
</body>
</html>