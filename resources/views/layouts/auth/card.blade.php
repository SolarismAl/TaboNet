<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-slate-100 dark:bg-slate-950 text-slate-800 dark:text-slate-100 antialiased font-sans relative selection:bg-emerald-600 selection:text-white transition-colors duration-200">
        <!-- Subtle Institutional Background -->
        <div class="fixed inset-0 pointer-events-none -z-10 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-slate-200/60 via-slate-100 to-slate-200 dark:from-slate-900 dark:via-slate-950 dark:to-black"></div>

        <div class="flex min-h-screen flex-col items-center justify-between p-4 sm:p-6 md:p-8">
            <!-- Institutional Navigation Header -->
            <header class="w-full max-w-2xl flex items-center justify-between pb-6 pt-2 border-b border-slate-300 dark:border-slate-800/80">
                <a href="{{ route('home') }}" class="flex items-center gap-3.5 group" wire:navigate>
                    <img src="{{ asset('img/cantilan_logo.png') }}" alt="Municipality of Cantilan Seal" class="h-12 w-12 object-contain drop-shadow">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-base font-bold tracking-tight text-slate-900 dark:text-white uppercase">TaboNet</span>
                            <span class="text-[10px] uppercase font-semibold tracking-wider px-2 py-0.5 rounded bg-emerald-100 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-400 border border-emerald-300 dark:border-emerald-800/80">Official Portal</span>
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Municipality of Cantilan, Surigao del Sur</p>
                    </div>
                </a>

                <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 text-xs font-medium text-slate-600 dark:text-slate-400 hover:text-emerald-600 dark:hover:text-white transition-colors" wire:navigate>
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span>Return to Portal</span>
                </a>
            </header>

            <!-- Formal Card Container -->
            <main class="w-full max-w-xl my-8">
                <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/90 shadow-xl backdrop-blur-sm p-6 sm:p-8 text-slate-800 dark:text-slate-100">
                    {{ $slot }}
                </div>
            </main>

            <!-- Institutional Government & Academic Footer -->
            <footer class="w-full max-w-2xl pt-6 pb-2 border-t border-slate-300 dark:border-slate-800/80 text-center text-xs text-slate-500 dark:text-slate-400 space-y-1">
                <p class="font-medium text-slate-700 dark:text-slate-300">Republic of the Philippines • Municipality of Cantilan • Surigao del Sur 8317</p>
                <p class="text-[11px] text-slate-500 dark:text-slate-400">In Partnership with North Eastern Mindanao State University (NEMSU) Cantilan Campus</p>
            </footer>
        </div>

        <x-theme-toggle />

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
