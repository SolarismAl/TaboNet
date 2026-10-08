<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>
    {{ filled($title ?? null) ? $title.' - '.config('app.name', 'TaboNet') : config('app.name', 'TaboNet') }}
</title>

<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
<link rel="icon" href="/favicon.ico" sizes="any">
<link rel="icon" href="/favicon.svg" type="image/svg+xml">
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">

<!-- Inline Theme Persistence (Executes before render to eliminate FOUC and persist across wire:navigate) -->
<script>
    (function () {
        function getPreferredTheme() {
            const saved = localStorage.getItem('flux.appearance');
            if (saved === 'dark' || saved === 'light') {
                return saved;
            }
            return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
        }

        function applyTheme() {
            const theme = getPreferredTheme();
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }

        // Apply immediately
        applyTheme();

        // Persist when Livewire switches pages via wire:navigate
        document.addEventListener('livewire:navigated', applyTheme);
        document.addEventListener('DOMContentLoaded', applyTheme);
    })();
</script>

@fonts

@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxAppearance
