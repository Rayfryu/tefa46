{{-- resources/views/layouts/partials/flash.blade.php --}}
@if(session('success'))
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
     x-transition:leave="transition ease-in duration-300"
     x-transition:leave-start="opacity-100 translate-y-0"
     x-transition:leave-end="opacity-0 -translate-y-2"
     class="mb-6 flex items-center gap-3 px-4 py-3 rounded-xl
            bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm shadow-sm">
    <svg class="w-5 h-5 flex-shrink-0 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    {{ session('success') }}
    <button @click="show = false" class="ml-auto text-emerald-400 hover:text-emerald-600">✕</button>
</div>
@endif

@if(session('error'))
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
     x-transition:leave="transition ease-in duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="mb-6 flex items-center gap-3 px-4 py-3 rounded-xl
            bg-red-50 border border-red-200 text-red-700 text-sm shadow-sm">
    <svg class="w-5 h-5 flex-shrink-0 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    {{ session('error') }}
    <button @click="show = false" class="ml-auto text-red-400 hover:text-red-600">✕</button>
</div>
@endif

@if($errors->any())
<div class="mb-6 px-4 py-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm shadow-sm">
    <ul class="space-y-1">
        @foreach($errors->all() as $error)
        <li class="flex items-center gap-2">
            <span class="w-1 h-1 rounded-full bg-red-400 flex-shrink-0"></span>
            {{ $error }}
        </li>
        @endforeach
    </ul>
</div>
@endif