{{-- resources/views/vendor/pagination/custom.blade.php --}}
@if ($paginator->hasPages())
<div class="flex items-center justify-between text-sm">
    <p class="text-slate-400 text-xs">
        Menampilkan {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }}
        dari {{ $paginator->total() }} data
    </p>
    <div class="flex items-center gap-1">
        @if ($paginator->onFirstPage())
        <span class="px-3 py-1.5 rounded-lg text-surface-300 cursor-not-allowed text-xs">← Prev</span>
        @else
        <a href="{{ $paginator->previousPageUrl() }}"
           class="px-3 py-1.5 rounded-lg text-slate-500 hover:text-slate-800
                  hover:bg-surface-100 transition-colors text-xs">
            ← Prev
        </a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
            <span class="px-2 py-1.5 text-surface-300 text-xs">{{ $element }}</span>
            @endif
            @if (is_array($element))
                @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                <span class="px-3 py-1.5 rounded-lg bg-brand-500 text-white font-semibold text-xs
                             shadow-sm shadow-brand-500/30">
                    {{ $page }}
                </span>
                @else
                <a href="{{ $url }}"
                   class="px-3 py-1.5 rounded-lg text-slate-500 hover:text-slate-800
                          hover:bg-surface-100 transition-colors text-xs">
                    {{ $page }}
                </a>
                @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}"
           class="px-3 py-1.5 rounded-lg text-slate-500 hover:text-slate-800
                  hover:bg-surface-100 transition-colors text-xs">
            Next →
        </a>
        @else
        <span class="px-3 py-1.5 rounded-lg text-surface-300 cursor-not-allowed text-xs">Next →</span>
        @endif
    </div>
</div>
@endif