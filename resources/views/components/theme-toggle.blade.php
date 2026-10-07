<div 
    x-data="{
        isDark: false,
        init() {
            this.syncState();
            
            // Watch for DOM class mutations
            const observer = new MutationObserver(() => this.syncState());
            observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });

            // Watch for Livewire page navigation
            document.addEventListener('livewire:navigated', () => this.syncState());
        },
        syncState() {
            this.isDark = document.documentElement.classList.contains('dark');
        },
        toggleTheme() {
            this.isDark = !this.isDark;
            const target = this.isDark ? 'dark' : 'light';

            // 1. Persist directly in localStorage
            localStorage.setItem('flux.appearance', target);

            // 2. Immediately toggle class on root documentElement
            if (this.isDark) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }

            // 3. Sync with Flux UI appearance engine if available
            if (window.Flux && typeof window.Flux.applyAppearance === 'function') {
                window.Flux.applyAppearance(target);
            }
        }
    }"
    class="fixed bottom-5 right-5 sm:bottom-6 sm:right-6 z-50 print:hidden"
>
    <button 
        type="button"
        @click="toggleTheme()"
        class="group flex h-12 w-12 items-center justify-center rounded-full border border-slate-300 dark:border-slate-700 bg-white/95 dark:bg-slate-900/95 text-slate-700 dark:text-slate-200 shadow-xl backdrop-blur-md transition-all duration-200 hover:scale-105 hover:border-emerald-500 dark:hover:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/50"
        :title="isDark ? 'Switch to Light Mode' : 'Switch to Dark Mode'"
        aria-label="Toggle theme mode"
    >
        <!-- Sun Icon (Active in Dark Mode -> click for Light) -->
        <svg 
            x-show="isDark" 
            x-cloak
            class="size-5.5 text-amber-400 transition-transform duration-300 group-hover:rotate-45" 
            fill="none" 
            viewBox="0 0 24 24" 
            stroke="currentColor"
        >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
        </svg>

        <!-- Moon Icon (Active in Light Mode -> click for Dark) -->
        <svg 
            x-show="!isDark" 
            x-cloak
            class="size-5.5 text-slate-700 transition-transform duration-300 group-hover:-rotate-12" 
            fill="none" 
            viewBox="0 0 24 24" 
            stroke="currentColor"
        >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
        </svg>
    </button>
</div>
