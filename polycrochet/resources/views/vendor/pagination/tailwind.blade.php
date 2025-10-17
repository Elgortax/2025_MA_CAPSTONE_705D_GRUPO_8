@if ($paginator->hasPages())
  <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="mt-8 flex items-center justify-center gap-4">
    {{-- Previous Page Link --}}
    @if ($paginator->onFirstPage())
      <span class="inline-flex items-center rounded-full border border-rose-100 bg-white/80 px-5 py-2 text-sm font-semibold text-rose-200 shadow-sm">
        {{ __('Anterior') }}
      </span>
    @else
      <a
        class="inline-flex items-center rounded-full bg-gradient-to-r from-rose-400 to-amber-300 px-5 py-2 text-sm font-semibold text-white shadow shadow-rose-200/60 transition hover:from-rose-500 hover:to-amber-400"
        href="{{ $paginator->previousPageUrl() }}"
        rel="prev"
      >
        {{ __('Anterior') }}
      </a>
    @endif

    {{-- Pagination Elements --}}
    <div class="hidden space-x-2 text-xs font-semibold text-slate-500 sm:flex">
      @foreach ($elements as $element)
        @if (is_string($element))
          <span>{{ $element }}</span>
        @endif

        @if (is_array($element))
          @foreach ($element as $page => $url)
            @if ($page == $paginator->currentPage())
              <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-rose-500 text-white shadow">{{ $page }}</span>
            @else
              <a
                class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-rose-100 bg-white/90 text-rose-500 transition hover:border-rose-300 hover:text-rose-600"
                href="{{ $url }}"
              >
                {{ $page }}
              </a>
            @endif
          @endforeach
        @endif
      @endforeach
    </div>

    {{-- Next Page Link --}}
    @if ($paginator->hasMorePages())
      <a
        class="inline-flex items-center rounded-full bg-gradient-to-r from-amber-300 to-rose-400 px-5 py-2 text-sm font-semibold text-white shadow shadow-rose-200/60 transition hover:from-amber-400 hover:to-rose-500"
        href="{{ $paginator->nextPageUrl() }}"
        rel="next"
      >
        {{ __('Siguiente') }}
      </a>
    @else
      <span class="inline-flex items-center rounded-full border border-rose-100 bg-white/80 px-5 py-2 text-sm font-semibold text-rose-200 shadow-sm">
        {{ __('Siguiente') }}
      </span>
    @endif
  </nav>
@endif
