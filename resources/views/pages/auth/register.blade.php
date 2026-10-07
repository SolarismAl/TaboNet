<x-layouts::auth :title="__('User Registration - TaboNet Cantilan')">
    <div class="flex flex-col gap-6" x-data="{ role: '{{ old('role', 'farmer') }}' }">
        <div class="text-center space-y-1.5 pb-2">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-md bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700/80 text-xs font-medium text-slate-700 dark:text-slate-300">
                <svg class="size-3.5 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
                <span>Municipal User Accreditation</span>
            </div>
            <h1 class="text-xl font-bold tracking-tight text-slate-900 dark:text-white">Create TaboNet Account</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400">Register as a producer or buyer in the Municipality of Cantilan</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        @if ($teamInvitation)
            <x-team-invitation-alert :invitation="$teamInvitation" :action="__('Register')" />
        @endif

        <form method="POST" action="{{ route('register.store') }}" class="flex flex-col gap-5">
            @csrf

            <!-- Stakeholder Classification -->
            <div class="space-y-2">
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                    Account Classification <span class="text-emerald-600 dark:text-emerald-400">*</span>
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <!-- Farmer / Producer -->
                    <label 
                        @click="role = 'farmer'"
                        :class="role === 'farmer' ? 'border-emerald-600 bg-emerald-50 dark:bg-emerald-950/30 ring-1 ring-emerald-500' : 'border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/60 hover:border-slate-300 dark:hover:border-slate-700'"
                        class="relative flex flex-col p-3.5 rounded-lg border cursor-pointer transition-colors shadow-2xs">
                        <input type="radio" name="role" value="farmer" x-model="role" class="sr-only" required>
                        <div class="flex items-center gap-2 mb-1">
                            <svg class="size-4 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wide">Farmer / Producer</span>
                        </div>
                        <p class="text-[11px] text-slate-600 dark:text-slate-400 leading-normal">Publish agricultural listings, track commodity trends, and receive inquiries.</p>
                        <div x-show="role === 'farmer'" class="absolute top-2.5 right-2.5 size-2 rounded-full bg-emerald-600 dark:bg-emerald-400"></div>
                    </label>

                    <!-- Buyer / Merchant -->
                    <label 
                        @click="role = 'buyer'"
                        :class="role === 'buyer' ? 'border-emerald-600 bg-emerald-50 dark:bg-emerald-950/30 ring-1 ring-emerald-500' : 'border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/60 hover:border-slate-300 dark:hover:border-slate-700'"
                        class="relative flex flex-col p-3.5 rounded-lg border cursor-pointer transition-colors shadow-2xs">
                        <input type="radio" name="role" value="buyer" x-model="role" class="sr-only" required>
                        <div class="flex items-center gap-2 mb-1">
                            <svg class="size-4 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                            <span class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wide">Buyer / Consumer</span>
                        </div>
                        <p class="text-[11px] text-slate-600 dark:text-slate-400 leading-normal">Procure farm-fresh crops directly from local farmers at prevailing prices.</p>
                        <div x-show="role === 'buyer'" class="absolute top-2.5 right-2.5 size-2 rounded-full bg-emerald-600 dark:bg-emerald-400"></div>
                    </label>
                </div>
                @error('role')
                    <p class="text-xs text-rose-500 dark:text-rose-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Full Name -->
            <div>
                <flux:input
                    name="name"
                    :label="__('Full Legal Name')"
                    :value="old('name')"
                    type="text"
                    required
                    autofocus
                    autocomplete="name"
                    placeholder="e.g., Juan C. Dela Cruz"
                />
            </div>

            <!-- Email Address -->
            <div>
                <flux:input
                    name="email"
                    :label="__('Email Address')"
                    :value="old('email')"
                    type="email"
                    required
                    autocomplete="email"
                    placeholder="name@domain.com"
                />
            </div>

            <!-- Contact & Barangay (2 Columns) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Mobile Number -->
                <div>
                    <flux:input
                        name="phone_number"
                        :label="__('Contact Mobile Number')"
                        :value="old('phone_number')"
                        type="tel"
                        required
                        placeholder="0912 345 6789"
                    />
                    <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-1">Official trade and SMS inquiry contact</p>
                </div>

                <!-- Cantilan Barangay -->
                <div class="space-y-1">
                    <label class="block text-xs font-medium text-slate-700 dark:text-slate-300">
                        Municipal Barangay <span class="text-emerald-600 dark:text-emerald-400">*</span>
                    </label>
                    <select 
                        name="barangay" 
                        required
                        class="w-full h-10 px-3 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-sm text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500 shadow-2xs">
                        <option value="" disabled {{ old('barangay') ? '' : 'selected' }}>Select Barangay (Cantilan)</option>
                        @foreach (\App\Models\User::BARANGAYS as $barangay)
                            <option value="{{ $barangay }}" {{ old('barangay') === $barangay ? 'selected' : '' }}>
                                Brgy. {{ $barangay }}
                            </option>
                        @endforeach
                    </select>
                    @error('barangay')
                        <p class="text-xs text-rose-500 dark:text-rose-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- DA-RSBSA Accreditation (Farmer Only) -->
            <div x-show="role === 'farmer'" x-transition class="p-3.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-950/60 space-y-2">
                <div class="flex items-center justify-between">
                    <label class="text-xs font-semibold text-slate-700 dark:text-slate-300 flex items-center gap-1.5">
                        <svg class="size-3.5 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        <span>DA-RSBSA Registration ID</span>
                        <span class="text-[10px] text-slate-500 dark:text-slate-400 font-normal">(Optional)</span>
                    </label>
                    <span class="text-[10px] text-emerald-800 dark:text-emerald-400 font-mono bg-emerald-100 dark:bg-emerald-950 px-2 py-0.5 rounded border border-emerald-300 dark:border-emerald-800/80 font-medium">Accreditation</span>
                </div>
                <flux:input
                    name="rsbsa_number"
                    :value="old('rsbsa_number')"
                    type="text"
                    placeholder="e.g., 16-68-04-001-XXXXXX"
                />
                <p class="text-[11px] text-slate-600 dark:text-slate-400 leading-normal">
                    Registry System for Basic Sectors in Agriculture (RSBSA) enables verified producer status under administrative review (UC-07).
                </p>
            </div>

            <!-- Password Fields -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <flux:input
                    name="password"
                    :label="__('Account Password')"
                    type="password"
                    required
                    autocomplete="new-password"
                    :placeholder="__('Min. 8 characters')"
                    viewable
                />

                <flux:input
                    name="password_confirmation"
                    :label="__('Confirm Password')"
                    type="password"
                    required
                    autocomplete="new-password"
                    :placeholder="__('Re-enter password')"
                    viewable
                />
            </div>

            <div class="pt-1">
                <flux:button type="submit" variant="primary" class="w-full !bg-emerald-700 hover:!bg-emerald-600 !text-white font-medium py-2.5 rounded-lg transition-colors shadow-sm" data-test="register-user-button">
                    {{ __('Complete Registration') }}
                </flux:button>
            </div>
        </form>

        <div class="pt-2 border-t border-slate-200 dark:border-slate-800 text-center text-xs text-slate-600 dark:text-slate-400">
            <span>{{ __('Already registered with TaboNet?') }}</span>
            <flux:link
                :href="$teamInvitation ? route('login', ['invitation' => $teamInvitation['code']]) : route('login')"
                class="!text-emerald-700 dark:!text-emerald-400 hover:!text-emerald-600 dark:hover:!text-emerald-300 font-medium ml-1"
                data-test="team-invitation-login-link"
                wire:navigate
            >
                {{ __('Sign In to Portal') }}
            </flux:link>
        </div>
    </div>
</x-layouts::auth>
