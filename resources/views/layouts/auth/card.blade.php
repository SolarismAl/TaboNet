<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-emerald-50/30 dark:bg-slate-950 text-slate-800 dark:text-slate-100 antialiased font-sans relative selection:bg-emerald-600 selection:text-white transition-colors duration-200">
        <!-- Vibrant Agricultural Background Glows -->
        <div class="fixed inset-0 pointer-events-none -z-10 overflow-hidden">
            <div class="absolute -top-32 -right-32 w-96 h-96 rounded-full bg-amber-400/15 dark:bg-amber-500/10 blur-3xl pointer-events-none"></div>
            <div class="absolute -top-24 -left-24 w-[30rem] h-[30rem] rounded-full bg-emerald-500/15 dark:bg-emerald-600/10 blur-3xl pointer-events-none"></div>
            <div class="absolute bottom-0 right-1/4 w-80 h-80 rounded-full bg-teal-400/10 dark:bg-teal-500/10 blur-3xl pointer-events-none"></div>
        </div>

        <div class="flex min-h-screen flex-col items-center justify-between p-4 sm:p-6 md:p-8">
            <!-- Institutional Navigation Header -->
            <header class="w-full max-w-2xl flex items-center justify-between pb-6 pt-2 border-b border-emerald-100 dark:border-slate-800/80">
                <a href="{{ route('home') }}" class="flex items-center gap-3.5 group" wire:navigate>
                    <div class="relative">
                        <img src="{{ asset('img/cantilan_logo.png') }}" alt="Municipality of Cantilan Seal" class="h-12 w-12 object-contain drop-shadow transition-transform group-hover:scale-105">
                        <span class="absolute -bottom-1 -right-1 flex h-4 w-4 items-center justify-center rounded-full bg-emerald-600 text-white text-[9px] font-bold shadow-xs">🌾</span>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-base font-black tracking-tight text-slate-900 dark:text-white uppercase">Tabo<span class="text-emerald-600 dark:text-emerald-500">Net</span></span>
                            <span class="text-[10px] uppercase font-bold tracking-wider px-2 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">Cantilan</span>
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Agricultural Marketplace & Price Monitoring</p>
                    </div>
                </a>

                <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white dark:bg-slate-900 border border-emerald-100 dark:border-slate-800 text-xs font-semibold text-slate-700 dark:text-slate-300 hover:text-emerald-700 dark:hover:text-emerald-300 hover:border-emerald-200 transition-colors shadow-2xs" wire:navigate>
                    <svg class="size-4 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span>Public Portal</span>
                </a>
            </header>

            <!-- Card Container -->
            <main class="w-full max-w-xl my-8">
                <div class="rounded-3xl border border-emerald-100 dark:border-slate-800 bg-white/95 dark:bg-slate-900/95 shadow-xl shadow-emerald-950/5 dark:shadow-black/40 backdrop-blur-md p-6 sm:p-8 text-slate-800 dark:text-slate-100">
                    {{ $slot }}
                </div>
            </main>

            <!-- Institutional Government & Academic Footer -->
            <footer class="w-full max-w-2xl pt-6 pb-2 border-t border-emerald-100 dark:border-slate-800/80 text-center text-xs text-slate-500 dark:text-slate-400 space-y-1">
                <p class="font-semibold text-slate-700 dark:text-slate-300">Republic of the Philippines • Municipality of Cantilan • Surigao del Sur 8317</p>
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
