<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:header container class="border-b border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden mr-2" icon="bars-2" inset="left" />

            <x-app-logo href="{{ route('dashboard') }}" wire:navigate />

            <flux:navbar class="-mb-px max-lg:hidden">
                <flux:navbar.item icon="layout-grid" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                    {{ __('Dashboard') }}
                </flux:navbar.item>
            </flux:navbar>

            <flux:spacer />

            <flux:navbar class="me-1.5 space-x-0.5 rtl:space-x-reverse py-0!">
                <flux:tooltip :content="__('Search')" position="bottom">
                    <flux:navbar.item class="!h-10 [&>div>svg]:size-5" icon="magnifying-glass" href="#" :label="__('Search')" />
                </flux:tooltip>
                <flux:tooltip :content="__('Public Portal')" position="bottom">
                    <flux:navbar.item
                        class="h-10 max-lg:hidden [&>div>svg]:size-5"
                        icon="globe-alt"
                        :href="route('home')"
                        :label="__('Public Portal')"
                    />
                </flux:tooltip>
                <flux:tooltip :content="__('NEMSU Portal')" position="bottom">
                    <flux:navbar.item
                        class="h-10 max-lg:hidden [&>div>svg]:size-5"
                        icon="building-library"
                        href="https://www.nemsu.edu.ph"
                        target="_blank"
                        :label="__('NEMSU Portal')"
                    />
                </flux:tooltip>
            </flux:navbar>

            <x-desktop-user-menu :showTeam="false" />

        </flux:header>

        <!-- Mobile Menu -->
        <flux:sidebar collapsible="mobile" sticky class="lg:hidden border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.header>
                <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
                <flux:sidebar.collapse class="in-data-flux-sidebar-on-desktop:not-in-data-flux-sidebar-collapsed-desktop:-mr-2" />
            </flux:sidebar.header>

            <flux:sidebar.nav>
                <flux:sidebar.group :heading="__('Marketplace Operations')">
                    <flux:sidebar.item icon="layout-grid" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                        @if(auth()->user()->isAdmin())
                            {{ __('Executive Dashboard') }}
                        @elseif(auth()->user()->isFarmer())
                            {{ __('Producer Hub') }}
                        @else
                            {{ __('Buyer Marketplace') }}
                        @endif
                    </flux:sidebar.item>

                    <flux:sidebar.item icon="chart-bar" :href="route('price-index')" :current="request()->routeIs('price-index')" wire:navigate>
                        {{ __('Spot Price Index') }}
                    </flux:sidebar.item>

                    <flux:sidebar.item icon="shopping-bag" :href="route('harvest-registry')" :current="request()->routeIs('harvest-registry')" wire:navigate>
                        {{ __('Harvest Registry') }}
                    </flux:sidebar.item>

                    <flux:sidebar.item icon="chat-bubble-left-right" :href="route('trade-inquiries')" :current="request()->routeIs('trade-inquiries')" wire:navigate>
                        {{ __('Trade Inquiries') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>

                @if(auth()->user()->isAdmin())
                    <flux:sidebar.group :heading="__('Municipal Administration')" class="mt-2">
                        <flux:sidebar.item icon="shield-check" :href="route('trade-inquiries')" :current="request()->routeIs('trade-inquiries')" wire:navigate>
                            {{ __('Accreditation & Audit') }}
                        </flux:sidebar.item>
                    </flux:sidebar.group>
                @endif
            </flux:sidebar.nav>

            <flux:spacer />

            <flux:sidebar.nav>
                <flux:sidebar.group :heading="__('External Resources')">
                    <flux:sidebar.item icon="globe-alt" :href="route('home')">
                        {{ __('Public Portal') }}
                    </flux:sidebar.item>

                    <flux:sidebar.item icon="building-library" href="https://www.nemsu.edu.ph" target="_blank">
                        {{ __('NEMSU Portal') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>
            </flux:sidebar.nav>
        </flux:sidebar>

        {{ $slot }}

        <livewire:create-team-modal />

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
