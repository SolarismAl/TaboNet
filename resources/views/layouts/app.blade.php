<x-layouts::app.sidebar :title="$title ?? null">
    <flux:main>
        {{ $slot }}
    </flux:main>

    <x-theme-toggle />
</x-layouts::app.sidebar>
