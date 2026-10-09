<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-emerald-50/20 dark:bg-slate-950 text-slate-800 dark:text-slate-100">
        <flux:sidebar sticky collapsible="mobile" class="border-e border-emerald-100 dark:border-slate-800 bg-white dark:bg-slate-900">
            <flux:sidebar.header>
                <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
                <flux:sidebar.collapse class="lg:hidden" />
            </flux:sidebar.header>

            <flux:sidebar.nav>
                <flux:sidebar.group :heading="__('Marketplace Operations')" class="grid">
                    <flux:sidebar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
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
                    <flux:sidebar.group :heading="__('Municipal Administration')" class="grid mt-2">
                        <flux:sidebar.item icon="shield-check" :href="route('trade-inquiries')" :current="request()->routeIs('trade-inquiries')" wire:navigate>
                            {{ __('Accreditation & Audit') }}
                        </flux:sidebar.item>
                    </flux:sidebar.group>
                @endif
            </flux:sidebar.nav>

            <flux:spacer />

            <flux:sidebar.nav>
                <flux:sidebar.group :heading="__('External Resources')" class="grid">
                    <flux:sidebar.item icon="globe-alt" :href="route('home')">
                        {{ __('Public Municipal Portal') }}
                    </flux:sidebar.item>

                    <flux:sidebar.item icon="building-library" href="https://www.nemsu.edu.ph" target="_blank">
                        {{ __('NEMSU Portal') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>
            </flux:sidebar.nav>

            <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <flux:avatar
                                    :name="auth()->user()->name"
                                    :initials="auth()->user()->initials()"
                                />

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                    <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                            {{ __('Settings') }}
                        </flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item
                            as="button"
                            type="submit"
                            icon="arrow-right-start-on-rectangle"
                            class="w-full cursor-pointer"
                            data-test="logout-button"
                        >
                            {{ __('Log out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

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
