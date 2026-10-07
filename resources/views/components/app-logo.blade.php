@props([
    'sidebar' => false,
])

@if($sidebar)
    <flux:sidebar.brand name="TaboNet" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-9 items-center justify-center rounded-lg bg-slate-900 border border-slate-700/80 p-0.5">
            <x-app-logo-icon class="size-7 object-contain" />
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand name="TaboNet" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-9 items-center justify-center rounded-lg bg-slate-900 border border-slate-700/80 p-0.5">
            <x-app-logo-icon class="size-7 object-contain" />
        </x-slot>
    </flux:brand>
@endif
