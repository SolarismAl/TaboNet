<x-layouts::auth :title="__('Sign In - TaboNet Cantilan')">
    <div class="flex flex-col gap-6">
        <div class="text-center space-y-2 pb-1">
            <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-emerald-100/80 dark:bg-emerald-950/70 border border-emerald-200 dark:border-emerald-800 text-xs font-semibold text-emerald-900 dark:text-emerald-300">
                <span>🌱 Welcome Back</span>
            </div>
            <h1 class="text-2xl font-black tracking-tight text-slate-900 dark:text-white">Sign In to TaboNet</h1>
            <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400">Access your farm harvests, orders, and daily market prices</p>
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
                    placeholder="you@example.com"
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
                        <flux:link class="absolute top-0 end-0 text-xs text-slate-500 dark:text-slate-400 hover:text-emerald-600 dark:hover:text-emerald-400 font-medium" :href="route('password.request')" wire:navigate>
                            {{ __('Forgot Password?') }}
                        </flux:link>
                    @endif
                </div>
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between text-xs text-slate-600 dark:text-slate-400">
                <flux:checkbox name="remember" :label="__('Keep me signed in')" :checked="old('remember')" />
                <span class="text-emerald-700 dark:text-emerald-400 font-medium">Cantilan Marketplace</span>
            </div>

            <div class="pt-2">
                <flux:button variant="primary" type="submit" class="w-full !bg-emerald-600 hover:!bg-emerald-500 !text-white font-bold py-3 rounded-xl transition-all shadow-md shadow-emerald-600/25" data-test="login-button">
                    {{ __('Sign In to Portal') }}
                </flux:button>
            </div>
        </form>

        <div class="pt-3 border-t border-emerald-100 dark:border-slate-800 text-center text-xs text-slate-600 dark:text-slate-400">
            <span>{{ __("Do not have an account yet?") }}</span>
            <flux:link
                :href="$teamInvitation ? route('register', ['invitation' => $teamInvitation['code']]) : route('register')"
                class="!text-emerald-700 dark:!text-emerald-400 hover:!text-emerald-600 dark:hover:!text-emerald-300 font-bold ml-1"
                data-test="register-link"
                wire:navigate
            >
                {{ __('Register as Farmer or Buyer') }}
            </flux:link>
        </div>
    </div>
</x-layouts::auth>
