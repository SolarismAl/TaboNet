@props([
    'paginator',
    'label' => 'records',
])

@if($paginator && method_exists($paginator, 'total') && $paginator->total() > 0)
    <div class="px-4 py-3 border-t border-slate-200 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-950/40 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
        <div class="text-slate-500 dark:text-slate-400 font-medium">
            Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of {{ $paginator->total() }} {{ $label }}
        </div>

        @if($paginator->hasPages())
            @php
                $window = \Illuminate\Pagination\UrlWindow::make($paginator);
                $elements = array_filter([
                    $window['first'],
                    is_array($window['slider']) ? '...' : null,
                    $window['slider'],
                    is_array($window['last']) ? '...' : null,
                    $window['last'],
                ]);
            @endphp

            <div class="inline-flex items-center gap-1 shrink-0">
                <button
                    type="button"
                    wire:click="previousPage('{{ $paginator->getPageName() }}')"
                    @disabled($paginator->onFirstPage())
                    class="px-2.5 py-1 rounded-md border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 disabled:opacity-40 disabled:cursor-not-allowed transition-colors font-medium flex items-center gap-1 cursor-pointer"
                >
                    <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    <span>Prev</span>
                </button>

                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span class="px-1 text-slate-400 select-none">...</span>
                    @elseif (is_array($element))
                        @foreach ($element as $pageNumber => $url)
                            @if ($pageNumber == $paginator->currentPage())
                                <span class="px-2.5 py-1 rounded-md bg-emerald-700 text-white font-bold text-xs select-none">{{ $pageNumber }}</span>
                            @else
                                <button
                                    type="button"
                                    wire:click="gotoPage({{ $pageNumber }}, '{{ $paginator->getPageName() }}')"
                                    class="px-2.5 py-1 rounded-md border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors font-medium cursor-pointer text-xs"
                                >
                                    {{ $pageNumber }}
                                </button>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                <button
                    type="button"
                    wire:click="nextPage('{{ $paginator->getPageName() }}')"
                    @disabled(! $paginator->hasMorePages())
                    class="px-2.5 py-1 rounded-md border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 disabled:opacity-40 disabled:cursor-not-allowed transition-colors font-medium flex items-center gap-1 cursor-pointer"
                >
                    <span>Next</span>
                    <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        @endif
    </div>
@endif
