<x-layouts::auth :title="__('User Registration - TaboNet Cantilan')">
    <div class="flex flex-col gap-6" x-data="{ role: '{{ old('role', 'farmer') }}' }">
        <div class="text-center space-y-2 pb-1">
            <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-emerald-100/80 dark:bg-emerald-950/70 border border-emerald-200 dark:border-emerald-800 text-xs font-semibold text-emerald-900 dark:text-emerald-300">
                <span>🌾 Join TaboNet Marketplace</span>
            </div>
            <h1 class="text-2xl font-black tracking-tight text-slate-900 dark:text-white">Create Your Account</h1>
            <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400">Join Cantilan's local agricultural community as a farmer or buyer</p>
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
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                    I am registering as <span class="text-emerald-600 dark:text-emerald-400">*</span>
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <!-- Farmer / Producer -->
                    <label 
                        @click="role = 'farmer'"
                        :class="role === 'farmer' ? 'border-emerald-600 bg-emerald-50/80 dark:bg-emerald-950/40 ring-2 ring-emerald-500' : 'border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/60 hover:border-emerald-300 dark:hover:border-slate-700'"
                        class="relative flex flex-col p-4 rounded-xl border cursor-pointer transition-all shadow-2xs">
                        <input type="radio" name="role" value="farmer" x-model="role" class="sr-only" required>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-lg">🌾</span>
                            <span class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wide">Farmer / Producer</span>
                        </div>
                        <p class="text-[11px] text-slate-600 dark:text-slate-400 leading-normal">Post your harvests, receive direct buyer inquiries, and check fair market rates.</p>
                        <div x-show="role === 'farmer'" class="absolute top-3 right-3 size-2.5 rounded-full bg-emerald-600 dark:bg-emerald-400"></div>
                    </label>

                    <!-- Buyer / Merchant -->
                    <label 
                        @click="role = 'buyer'"
                        :class="role === 'buyer' ? 'border-amber-500 bg-amber-50/80 dark:bg-amber-950/40 ring-2 ring-amber-500' : 'border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/60 hover:border-amber-300 dark:hover:border-slate-700'"
                        class="relative flex flex-col p-4 rounded-xl border cursor-pointer transition-all shadow-2xs">
                        <input type="radio" name="role" value="buyer" x-model="role" class="sr-only" required>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-lg">🛒</span>
                            <span class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wide">Buyer / Consumer</span>
                        </div>
                        <p class="text-[11px] text-slate-600 dark:text-slate-400 leading-normal">Browse and purchase fresh produce directly from local growers at prevailing rates.</p>
                        <div x-show="role === 'buyer'" class="absolute top-3 right-3 size-2.5 rounded-full bg-amber-500"></div>
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
                    <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-1">Used for order updates and SMS notifications</p>
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
            <div x-show="role === 'farmer'" x-transition class="p-4 rounded-xl border border-emerald-100 dark:border-emerald-950/60 bg-emerald-50/40 dark:bg-slate-950/60 space-y-2">
                <div class="flex items-center justify-between">
                    <label class="text-xs font-bold text-slate-700 dark:text-slate-300 flex items-center gap-1.5">
                        <span class="text-sm">🛡️</span>
                        <span>DA-RSBSA Registration ID</span>
                        <span class="text-[10px] text-slate-500 dark:text-slate-400 font-normal">(Optional)</span>
                    </label>
                    <span class="text-[10px] text-emerald-800 dark:text-emerald-300 font-bold bg-emerald-100 dark:bg-emerald-950 px-2 py-0.5 rounded-full border border-emerald-200 dark:border-emerald-800">
                        Verified Badge
                    </span>
                </div>
                <flux:input
                    name="rsbsa_number"
                    :value="old('rsbsa_number')"
                    type="text"
                    placeholder="e.g., 16-68-04-001-XXXXXX"
                />
                <p class="text-[11px] text-slate-600 dark:text-slate-400 leading-normal">
                    If you are registered with the Cantilan Municipal Agriculture Office, enter your RSBSA ID to get an official <strong>Verified Farmer</strong> badge.
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

            <div class="pt-2">
                <flux:button type="submit" variant="primary" class="w-full !bg-emerald-600 hover:!bg-emerald-500 !text-white font-bold py-3 rounded-xl transition-all shadow-md shadow-emerald-600/25" data-test="register-user-button">
                    {{ __('Complete Registration') }}
                </flux:button>
            </div>
        </form>

        <div class="pt-3 border-t border-emerald-100 dark:border-slate-800 text-center text-xs text-slate-600 dark:text-slate-400">
            <span>{{ __('Already registered with TaboNet?') }}</span>
            <flux:link
                :href="$teamInvitation ? route('login', ['invitation' => $teamInvitation['code']]) : route('login')"
                class="!text-emerald-700 dark:!text-emerald-400 hover:!text-emerald-600 dark:hover:!text-emerald-300 font-bold ml-1"
                data-test="team-invitation-login-link"
                wire:navigate
            >
                {{ __('Sign In to Portal') }}
            </flux:link>
        </div>
    </div>
</x-layouts::auth>
