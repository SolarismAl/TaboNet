<x-layouts::auth :title="__('Sign In - TaboNet Cantilan')">
    <div class="flex flex-col gap-5">
        <div class="text-center space-y-1.5 pb-1">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-md bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700/80 text-xs font-medium text-slate-700 dark:text-slate-300">
                <svg class="size-3.5 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                <span>Municipal System Access</span>
            </div>
            <h1 class="text-xl font-bold tracking-tight text-slate-900 dark:text-white">TaboNet Account Sign In</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400">Enter your authorized credentials to access your dashboard</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        @if ($teamInvitation)
            <x-team-invitation-alert :invitation="$teamInvitation" :action="__('Log in')" />
        @endif

        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-4">
            @csrf

            <!-- Email Address -->
            <div>
                <flux:input
                    name="email"
                    :label="__('Registered Email Address')"
                    :value="old('email')"
                    type="email"
                    required
                    autofocus
                    autocomplete="email"
                    placeholder="official@example.com"
                />
            </div>

            <!-- Password -->
            <div class="space-y-1">
                <div class="relative">
                    <flux:input
                        name="password"
                        :label="__('Account Password')"
                        type="password"
                        required
                        autocomplete="current-password"
                        :placeholder="__('Enter your password')"
                        viewable
                    />

                    @if (Route::has('password.request'))
                        <flux:link class="absolute top-0 end-0 text-xs text-slate-500 dark:text-slate-400 hover:text-emerald-600 dark:hover:text-emerald-400" :href="route('password.request')" wire:navigate>
                            {{ __('Forgot Password?') }}
                        </flux:link>
                    @endif
                </div>
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between text-xs text-slate-600 dark:text-slate-400">
                <flux:checkbox name="remember" :label="__('Maintain active session')" :checked="old('remember')" />
                <span>Cantilan Spot Market</span>
            </div>

            <div class="pt-1">
                <flux:button variant="primary" type="submit" class="w-full !bg-emerald-700 hover:!bg-emerald-600 !text-white font-medium py-2.5 rounded-lg transition-colors shadow-sm" data-test="login-button">
                    {{ __('Sign In to Portal') }}
                </flux:button>
            </div>
        </form>

        <div class="pt-2 border-t border-slate-200 dark:border-slate-800 text-center text-xs text-slate-600 dark:text-slate-400">
            <span>{{ __("Do not have an account yet?") }}</span>
            <flux:link
                :href="$teamInvitation ? route('register', ['invitation' => $teamInvitation['code']]) : route('register')"
                class="!text-emerald-700 dark:!text-emerald-400 hover:!text-emerald-600 dark:hover:!text-emerald-300 font-medium ml-1"
                data-test="register-link"
                wire:navigate
            >
                {{ __('Register for Account') }}
            </flux:link>
        </div>
    </div>
</x-layouts::auth>
