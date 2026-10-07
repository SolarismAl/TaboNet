<div class="flex flex-col gap-6 w-full">
    <!-- ================================================================= -->
    <!-- VIEW MODE: OVERVIEW (Main Role-Adaptive Dashboard)                -->
    <!-- ================================================================= -->
    @if($viewMode === 'overview')
        <!-- Greeting Header & RBAC Identity -->
        <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/90 p-5 sm:p-6 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <img src="{{ asset('img/cantilan_logo.png') }}" alt="Cantilan Official Seal" class="h-11 w-11 object-contain shrink-0 drop-shadow-xs">
                <div class="space-y-0.5">
                    <div class="flex flex-wrap items-center gap-2">
                        <h1 class="text-lg font-bold tracking-tight text-slate-900 dark:text-white">{{ Auth::user()->name }}</h1>
                        @if(Auth::user()->isFarmer())
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-emerald-100 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-800">
                                Smallholder Producer
                            </span>
                            @if(Auth::user()->status === 'verified')
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-teal-100 dark:bg-teal-950 text-teal-800 dark:text-teal-300 border border-teal-300 dark:border-teal-800">
                                    DA-RSBSA Verified
                                </span>
                            @else
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold tracking-wider bg-amber-100 dark:bg-amber-950 text-amber-800 dark:text-amber-300 border border-amber-300 dark:border-amber-800">
                                    Accreditation Pending
                                </span>
                            @endif
                        @elseif(Auth::user()->isAdmin())
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-purple-100 dark:bg-purple-950 text-purple-800 dark:text-purple-300 border border-purple-300 dark:border-purple-800">
                                Municipal Administrator
                            </span>
                        @else
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-blue-100 dark:bg-blue-950 text-blue-800 dark:text-blue-300 border border-blue-300 dark:border-blue-800">
                                Commercial Buyer
                            </span>
                        @endif
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        @if(Auth::user()->isFarmer())
                            Brgy. {{ Auth::user()->barangay ?? 'Cantilan District' }} • Smallholder Producer Hub
                        @elseif(Auth::user()->isAdmin())
                            LGU Cantilan Municipal Hall • Agricultural Oversight & Price Stabilization
                        @else
                            Brgy. {{ Auth::user()->barangay ?? 'Cantilan District' }} • Direct Commercial Trade Portal
                        @endif
                    </p>
                </div>
            </div>

            <!-- Role-Specific Action Controls -->
            <div class="flex flex-wrap items-center gap-2 shrink-0">
                @if(Auth::user()->isFarmer() || Auth::user()->isAdmin())
                    @can('publish-harvest')
                        <button
                            wire:click="openAddProductModal"
                            type="button"
                            class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-emerald-700 hover:bg-emerald-600 text-white text-xs font-semibold uppercase tracking-wider transition-colors shadow-xs cursor-pointer"
                        >
                            <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span>+ Post Harvest Listing</span>
                        </button>
                    @endcan
                @else
                    <a
                        href="{{ route('harvest-registry') }}"
                        wire:navigate
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-emerald-700 hover:bg-emerald-600 text-white text-xs font-semibold uppercase tracking-wider transition-colors shadow-xs"
                    >
                        <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        <span>Browse Harvest Registry</span>
                    </a>
                @endif

                <a href="{{ route('home') }}" class="px-3.5 py-2 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-300 dark:border-slate-700 text-xs font-semibold uppercase tracking-wider transition-colors">
                    Public Portal
                </a>
            </div>
        </div>

        <!-- ROLE-ADAPTIVE 4 KPI INDICATORS -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
            @if(Auth::user()->isFarmer())
                <!-- Farmer KPI 1: My Active Batches -->
                <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/80 space-y-1 shadow-xs">
                    <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                        <span class="font-semibold uppercase tracking-wider text-[10px]">My Active Produce</span>
                        <span class="p-1 rounded bg-emerald-50 dark:bg-emerald-950/70 text-emerald-600 dark:text-emerald-400">
                            <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </span>
                    </div>
                    <div class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white">{{ $this->kpis['my_listings_count'] }} Batches</div>
                    <div class="text-[11px] text-slate-500 dark:text-slate-400">Posted by you</div>
                </div>

                <!-- Farmer KPI 2: Incoming Buyer Pre-Orders -->
                <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/80 space-y-1 shadow-xs">
                    <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                        <span class="font-semibold uppercase tracking-wider text-[10px]">Incoming Pre-Orders</span>
                        <span class="p-1 rounded bg-blue-50 dark:bg-blue-950/70 text-blue-600 dark:text-blue-400">
                            <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                        </span>
                    </div>
                    <div class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white">{{ $this->kpis['inquiries_count'] }} Leads</div>
                    <div class="text-[11px] text-slate-500 dark:text-slate-400">Buyer requests awaiting pickup</div>
                </div>

                <!-- Farmer KPI 3: Estimated Stock Valuation -->
                <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/80 space-y-1 shadow-xs">
                    <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                        <span class="font-semibold uppercase tracking-wider text-[10px]">My Stock Valuation</span>
                        <span class="p-1 rounded bg-teal-50 dark:bg-teal-950/70 text-teal-600 dark:text-teal-400">
                            <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </span>
                    </div>
                    <div class="text-xl sm:text-2xl font-black text-emerald-700 dark:text-emerald-400">₱{{ number_format($this->kpis['my_inventory_value'], 2) }}</div>
                    <div class="text-[11px] text-slate-500 dark:text-slate-400">Estimated total farmgate value</div>
                </div>

                <!-- Farmer KPI 4: Prevailing Rice Spot Rate -->
                <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/80 space-y-1 shadow-xs">
                    <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                        <span class="font-semibold uppercase tracking-wider text-[10px]">Rice Mean (P_avg)</span>
                        <span class="p-1 rounded bg-emerald-50 dark:bg-emerald-950/70 text-emerald-600 dark:text-emerald-400">
                            <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                        </span>
                    </div>
                    <div class="text-xl sm:text-2xl font-black text-emerald-700 dark:text-emerald-400">₱{{ number_format($this->kpis['rice_avg'], 2) }} / kg</div>
                    <div class="text-[11px] text-slate-500 dark:text-slate-400">Cantilan market benchmark</div>
                </div>
            @elseif(Auth::user()->isAdmin())
                <!-- Admin Municipal Oversight KPIs -->
                <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/80 space-y-1 shadow-xs">
                    <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                        <span class="font-semibold uppercase tracking-wider text-[10px]">Harvest Listings</span>
                        <span class="p-1 rounded bg-emerald-50 dark:bg-emerald-950/70 text-emerald-600 dark:text-emerald-400">
                            <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </span>
                    </div>
                    <div class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white">{{ $this->kpis['total_listings'] }} Batches</div>
                    <div class="text-[11px] text-slate-500 dark:text-slate-400">Across Cantilan</div>
                </div>

                <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/80 space-y-1 shadow-xs">
                    <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                        <span class="font-semibold uppercase tracking-wider text-[10px]">Rice Mean (P_avg)</span>
                        <span class="p-1 rounded bg-emerald-50 dark:bg-emerald-950/70 text-emerald-600 dark:text-emerald-400">
                            <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                        </span>
                    </div>
                    <div class="text-xl sm:text-2xl font-black text-emerald-700 dark:text-emerald-400">₱{{ number_format($this->kpis['rice_avg'], 2) }} / kg</div>
                    <div class="text-[11px] text-slate-500 dark:text-slate-400">Spot Benchmark</div>
                </div>

                <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/80 space-y-1 shadow-xs">
                    <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                        <span class="font-semibold uppercase tracking-wider text-[10px]">Producers</span>
                        <span class="p-1 rounded bg-teal-50 dark:bg-teal-950/70 text-teal-600 dark:text-teal-400">
                            <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </span>
                    </div>
                    <div class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white">{{ $this->kpis['producers_count'] }} Registered</div>
                    <div class="text-[11px] text-slate-500 dark:text-slate-400">Smallholders</div>
                </div>

                <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/80 space-y-1 shadow-xs">
                    <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                        <span class="font-semibold uppercase tracking-wider text-[10px]">Trade Inquiries</span>
                        <span class="p-1 rounded bg-blue-50 dark:bg-blue-950/70 text-blue-600 dark:text-blue-400">
                            <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                        </span>
                    </div>
                    <div class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white">{{ $this->kpis['inquiries_count'] }} Leads</div>
                    <div class="text-[11px] text-slate-500 dark:text-slate-400">Municipal dialogues</div>
                </div>
            @else
                <!-- Buyer KPIs -->
                <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/80 space-y-1 shadow-xs">
                    <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                        <span class="font-semibold uppercase tracking-wider text-[10px]">My Pre-Orders</span>
                        <span class="p-1 rounded bg-blue-50 dark:bg-blue-950/70 text-blue-600 dark:text-blue-400">
                            <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                        </span>
                    </div>
                    <div class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white">{{ $this->kpis['inquiries_count'] }} Dispatched</div>
                    <div class="text-[11px] text-slate-500 dark:text-slate-400">Active inquiries placed</div>
                </div>

                <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/80 space-y-1 shadow-xs">
                    <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                        <span class="font-semibold uppercase tracking-wider text-[10px]">Available Batches</span>
                        <span class="p-1 rounded bg-emerald-50 dark:bg-emerald-950/70 text-emerald-600 dark:text-emerald-400">
                            <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </span>
                    </div>
                    <div class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white">{{ $this->kpis['total_listings'] }} Live</div>
                    <div class="text-[11px] text-slate-500 dark:text-slate-400">Ready for purchase</div>
                </div>

                <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/80 space-y-1 shadow-xs">
                    <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                        <span class="font-semibold uppercase tracking-wider text-[10px]">Rice Mean (P_avg)</span>
                        <span class="p-1 rounded bg-emerald-50 dark:bg-emerald-950/70 text-emerald-600 dark:text-emerald-400">
                            <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                        </span>
                    </div>
                    <div class="text-xl sm:text-2xl font-black text-emerald-700 dark:text-emerald-400">₱{{ number_format($this->kpis['rice_avg'], 2) }} / kg</div>
                    <div class="text-[11px] text-slate-500 dark:text-slate-400">Benchmark rate</div>
                </div>

                <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/80 space-y-1 shadow-xs">
                    <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                        <span class="font-semibold uppercase tracking-wider text-[10px]">Local Producers</span>
                        <span class="p-1 rounded bg-teal-50 dark:bg-teal-950/70 text-teal-600 dark:text-teal-400">
                            <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </span>
                    </div>
                    <div class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white">{{ $this->kpis['producers_count'] }} Smallholders</div>
                    <div class="text-[11px] text-slate-500 dark:text-slate-400">Direct farmgate sources</div>
                </div>
            @endif
        </div>

        <!-- ADMIN ONLY: PENDING PRODUCER ACCREDITATION PANEL (RBAC) -->
        @if(Auth::user()->isAdmin() && $this->pendingProducers->isNotEmpty())
            <div class="p-4 rounded-xl border border-amber-300 dark:border-amber-800/80 bg-amber-50/70 dark:bg-amber-950/40 space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-amber-950 dark:text-amber-200">
                        Pending Producer Accreditations ({{ $this->pendingProducers->count() }})
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach ($this->pendingProducers as $pFarmer)
                        <div class="p-3 rounded-lg bg-white dark:bg-slate-900 border border-amber-200 dark:border-amber-800/60 flex items-center justify-between text-xs">
                            <div>
                                <span class="font-bold text-slate-900 dark:text-white block">{{ $pFarmer->name }}</span>
                                <span class="text-[11px] text-slate-500">Brgy. {{ $pFarmer->barangay }}</span>
                            </div>
                            <button
                                wire:click="verifyFarmer({{ $pFarmer->id }})"
                                type="button"
                                class="px-2.5 py-1.5 rounded bg-teal-700 hover:bg-teal-600 text-white text-[10px] font-bold uppercase tracking-wider transition-colors cursor-pointer"
                            >
                                Verify DA-RSBSA
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- 3 NAVIGATION CARDS -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Nav 1: Spot Price Index -->
            <a
                href="{{ route('price-index') }}"
                wire:navigate
                class="group rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/90 p-4 shadow-xs hover:border-emerald-500 dark:hover:border-emerald-500 transition-colors flex items-center justify-between"
            >
                <div class="flex items-center gap-3">
                    <span class="p-2.5 rounded-lg bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-400 group-hover:bg-emerald-700 group-hover:text-white transition-colors">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </span>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">
                            Prevailing Spot Price Index
                        </h3>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400">Official commodity monitoring benchmark</p>
                    </div>
                </div>
                <svg class="size-4 text-slate-400 group-hover:text-emerald-600 group-hover:translate-x-0.5 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>

            <!-- Nav 2: Harvest Registry -->
            <a
                href="{{ route('harvest-registry') }}"
                wire:navigate
                class="group rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/90 p-4 shadow-xs hover:border-emerald-500 dark:hover:border-emerald-500 transition-colors flex items-center justify-between"
            >
                <div class="flex items-center gap-3">
                    <span class="p-2.5 rounded-lg bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-400 group-hover:bg-emerald-700 group-hover:text-white transition-colors">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                    </span>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">
                            Active Harvest Registry
                        </h3>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400">{{ $this->kpis['total_listings'] }} active produce batches</p>
                    </div>
                </div>
                <svg class="size-4 text-slate-400 group-hover:text-emerald-600 group-hover:translate-x-0.5 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>

            <!-- Nav 3: Trade Inquiries -->
            <a
                href="{{ route('trade-inquiries') }}"
                wire:navigate
                class="group rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/90 p-4 shadow-xs hover:border-blue-500 dark:hover:border-blue-500 transition-colors flex items-center justify-between"
            >
                <div class="flex items-center gap-3">
                    <span class="p-2.5 rounded-lg bg-blue-100 dark:bg-blue-950 text-blue-700 dark:text-blue-400 group-hover:bg-blue-700 group-hover:text-white transition-colors">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                        </svg>
                    </span>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                            Trade Inquiries
                        </h3>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400">Direct pre-orders and leads</p>
                    </div>
                </div>
                <svg class="size-4 text-slate-400 group-hover:text-blue-600 group-hover:translate-x-0.5 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        <!-- ============================================================= -->
        <!-- ROLE-TAILORED CONTENT SECTION                                 -->
        <!-- ============================================================= -->

        @if(Auth::user()->isFarmer())
            <!-- ===================== FARMER VIEW ======================= -->
            <!-- 1. Dedicated Farmer's Own Produce Table with Edit & Remove -->
            <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/90 overflow-hidden shadow-xs">
                <div class="p-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900 dark:text-white">My Harvest Listings</h3>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400">Manage your farmgate listings with Edit and Remove actions</p>
                    </div>
                    <button
                        wire:click="openAddProductModal"
                        type="button"
                        class="px-2.5 py-1.5 rounded-lg bg-emerald-700 hover:bg-emerald-600 text-white text-xs font-semibold uppercase tracking-wider transition-colors cursor-pointer inline-flex items-center gap-1"
                    >
                        <svg class="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>+ Post New Harvest</span>
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 dark:bg-slate-950/60 text-slate-500 dark:text-slate-400 uppercase font-semibold text-[10px] tracking-wider border-b border-slate-200 dark:border-slate-800">
                            <tr>
                                <th class="py-2.5 px-4">Produce</th>
                                <th class="py-2.5 px-4">Category</th>
                                <th class="py-2.5 px-4">Stock In Farm</th>
                                <th class="py-2.5 px-4">Farmgate Rate</th>
                                <th class="py-2.5 px-4">Status</th>
                                <th class="py-2.5 px-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-800/80 text-slate-700 dark:text-slate-300">
                            @forelse ($this->myHarvestProducts as $item)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30">
                                    <td class="py-2.5 px-4 font-bold text-slate-900 dark:text-white">
                                        {{ $item->name }}
                                        @if($item->description)
                                            <span class="block text-[11px] text-slate-400 font-normal truncate max-w-xs">{{ $item->description }}</span>
                                        @endif
                                    </td>
                                    <td class="py-2.5 px-4">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                            {{ $item->category }}
                                        </span>
                                    </td>
                                    <td class="py-2.5 px-4 font-mono font-medium">
                                        {{ number_format($item->quantity) }} {{ $item->unit }}
                                    </td>
                                    <td class="py-2.5 px-4 font-bold text-emerald-700 dark:text-emerald-400">
                                        ₱{{ number_format($item->price, 2) }}
                                    </td>
                                    <td class="py-2.5 px-4">
                                        @if($item->status === 'active')
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-emerald-100 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-800">
                                                Active
                                            </span>
                                        @elseif($item->status === 'sold_out')
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-amber-100 dark:bg-amber-950 text-amber-800 dark:text-amber-300 border border-amber-300 dark:border-amber-800">
                                                Sold Out
                                            </span>
                                        @else
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                                {{ ucfirst($item->status) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-2.5 px-4 text-right">
                                        <div class="inline-flex items-center gap-1.5 justify-end">
                                            <button
                                                wire:click="openEditProductModal({{ $item->id }})"
                                                type="button"
                                                class="px-2 py-1 rounded bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-[11px] font-semibold transition-colors cursor-pointer inline-flex items-center gap-1"
                                                title="Edit Listing"
                                            >
                                                <svg class="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                                </svg>
                                                <span>Edit</span>
                                            </button>
                                            <button
                                                wire:click="removeListing({{ $item->id }})"
                                                wire:confirm="Are you sure you want to remove this harvest listing?"
                                                type="button"
                                                class="px-2 py-1 rounded bg-red-50 hover:bg-red-100 dark:bg-red-950/50 dark:hover:bg-red-900/60 text-red-600 dark:text-red-400 text-[11px] font-semibold transition-colors cursor-pointer inline-flex items-center gap-1"
                                                title="Remove Listing"
                                            >
                                                <svg class="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                                <span>Remove</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-6 text-center text-slate-400">
                                        You have not posted any harvest listings yet. Click <strong>+ Post New Harvest</strong> to list produce.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination for Farmer's Own Listings -->
                <x-table-pagination :paginator="$this->myHarvestProducts" label="harvest listings" />
            </div>

            <!-- 2. Incoming Buyer Pre-Orders Table -->
            @if($this->userInquiries->isNotEmpty())
                <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/90 overflow-hidden shadow-xs">
                    <div class="p-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                        <div>
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900 dark:text-white">Incoming Buyer Pre-Orders</h3>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400">Pre-orders dispatched directly to your farm</p>
                        </div>
                        <a href="{{ route('trade-inquiries') }}" wire:navigate class="text-xs text-blue-600 dark:text-blue-400 hover:underline font-semibold">
                            Full Pre-Order Manager
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-slate-50 dark:bg-slate-950/60 text-slate-500 dark:text-slate-400 uppercase font-semibold text-[10px] tracking-wider border-b border-slate-200 dark:border-slate-800">
                                <tr>
                                    <th class="py-2.5 px-4">Produce</th>
                                    <th class="py-2.5 px-4">Buyer</th>
                                    <th class="py-2.5 px-4">Phone</th>
                                    <th class="py-2.5 px-4">Volume</th>
                                    <th class="py-2.5 px-4">Target Pickup</th>
                                    <th class="py-2.5 px-4">Status</th>
                                    <th class="py-2.5 px-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 dark:divide-slate-800/80 text-slate-700 dark:text-slate-300">
                                @foreach ($this->userInquiries as $inq)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30">
                                        <td class="py-2.5 px-4 font-bold text-slate-900 dark:text-white">{{ $inq->product?->name ?? 'Harvest Batch' }}</td>
                                        <td class="py-2.5 px-4">{{ $inq->buyer?->name ?? 'Buyer' }}</td>
                                        <td class="py-2.5 px-4 font-mono">{{ $inq->buyer?->phone_number ?? '—' }}</td>
                                        <td class="py-2.5 px-4 font-bold text-emerald-700 dark:text-emerald-400 font-mono">{{ number_format($inq->quantity) }} {{ $inq->product?->unit ?? 'kg' }}</td>
                                        <td class="py-2.5 px-4 text-slate-600 dark:text-slate-400">{{ $inq->pickup_date ? $inq->pickup_date->format('M d, Y') : 'Immediate' }}</td>
                                        <td class="py-2.5 px-4">
                                            @if($inq->status === 'pending')
                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-amber-100 dark:bg-amber-950 text-amber-800 dark:text-amber-300 border border-amber-300 dark:border-amber-800">
                                                    Pending
                                                </span>
                                            @elseif($inq->status === 'accepted')
                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-emerald-100 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-800">
                                                    Accepted
                                                </span>
                                            @else
                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                                    {{ ucfirst($inq->status) }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-2.5 px-4 text-right">
                                            @if($inq->status === 'pending')
                                                <div class="inline-flex items-center gap-1.5 justify-end">
                                                    <button
                                                        wire:click="updateInquiryStatus({{ $inq->id }}, 'accepted')"
                                                        type="button"
                                                        class="px-2.5 py-1 rounded bg-emerald-700 hover:bg-emerald-600 text-white text-[11px] font-semibold transition-colors cursor-pointer"
                                                    >
                                                        Accept
                                                    </button>
                                                    <button
                                                        wire:click="updateInquiryStatus({{ $inq->id }}, 'declined')"
                                                        type="button"
                                                        class="px-2.5 py-1 rounded bg-slate-200 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-[11px] font-semibold transition-colors cursor-pointer"
                                                    >
                                                        Decline
                                                    </button>
                                                </div>
                                            @else
                                                <span class="text-[11px] text-slate-400">Logged</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination for Incoming Pre-Orders -->
                    <x-table-pagination :paginator="$this->userInquiries" label="pre-orders" />
                </div>
            @endif

        @elseif(Auth::user()->isAdmin())
            <!-- ===================== ADMIN VIEW ======================== -->
            <!-- Active Harvest Listings Across Municipality with Full Oversight -->
            <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/90 overflow-hidden shadow-xs">
                <div class="p-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900 dark:text-white">Active Municipal Harvest Listings</h3>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400">All registered produce with Administrator Edit and Remove controls</p>
                    </div>
                    <a href="{{ route('harvest-registry') }}" wire:navigate class="text-xs text-emerald-700 dark:text-emerald-400 hover:underline font-semibold flex items-center gap-1">
                        <span>All Batches</span>
                        <svg class="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 dark:bg-slate-950/60 text-slate-500 dark:text-slate-400 uppercase font-semibold text-[10px] tracking-wider border-b border-slate-200 dark:border-slate-800">
                            <tr>
                                <th class="py-2.5 px-4">Produce</th>
                                <th class="py-2.5 px-4">Category</th>
                                <th class="py-2.5 px-4">Producer / Location</th>
                                <th class="py-2.5 px-4">Stock</th>
                                <th class="py-2.5 px-4">Spot Rate</th>
                                <th class="py-2.5 px-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-800/80 text-slate-700 dark:text-slate-300">
                            @forelse ($this->harvestProducts as $item)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30">
                                    <td class="py-2.5 px-4 font-bold text-slate-900 dark:text-white">{{ $item->name }}</td>
                                    <td class="py-2.5 px-4">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                            {{ $item->category }}
                                        </span>
                                    </td>
                                    <td class="py-2.5 px-4">
                                        <div class="font-medium text-slate-900 dark:text-white">{{ $item->farmer?->name ?? 'Producer' }}</div>
                                        <div class="text-[10px] text-slate-500">Brgy. {{ $item->barangay }}</div>
                                    </td>
                                    <td class="py-2.5 px-4 font-mono font-medium">{{ number_format($item->quantity) }} {{ $item->unit }}</td>
                                    <td class="py-2.5 px-4 font-bold text-emerald-700 dark:text-emerald-400">₱{{ number_format($item->price, 2) }}</td>
                                    <td class="py-2.5 px-4 text-right">
                                        <div class="inline-flex items-center gap-1.5 justify-end">
                                            <button
                                                wire:click="openEditProductModal({{ $item->id }})"
                                                type="button"
                                                class="px-2 py-1 rounded bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-[11px] font-semibold transition-colors cursor-pointer inline-flex items-center gap-1"
                                                title="Edit Listing"
                                            >
                                                <svg class="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                                </svg>
                                                <span>Edit</span>
                                            </button>
                                            <button
                                                wire:click="removeListing({{ $item->id }})"
                                                wire:confirm="Are you sure you want to remove this harvest listing?"
                                                type="button"
                                                class="px-2 py-1 rounded bg-red-50 hover:bg-red-100 dark:bg-red-950/50 dark:hover:bg-red-900/60 text-red-600 dark:text-red-400 text-[11px] font-semibold transition-colors cursor-pointer inline-flex items-center gap-1"
                                                title="Remove Listing"
                                            >
                                                <svg class="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                                <span>Remove</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-6 text-center text-slate-400">No active harvest batches registered.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination for Municipal Admin Listings -->
                <x-table-pagination :paginator="$this->harvestProducts" label="harvest listings" />
            </div>

        @else
            <!-- ===================== BUYER VIEW ======================== -->
            <!-- 1. Fresh Local Harvest Arrivals (Ready to Pre-Order) -->
            <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/90 overflow-hidden shadow-xs">
                <div class="p-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900 dark:text-white">Fresh Produce Arrivals in Cantilan</h3>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400">Pre-order directly from accredited smallholder producers</p>
                    </div>
                    <a href="{{ route('harvest-registry') }}" wire:navigate class="text-xs text-emerald-700 dark:text-emerald-400 hover:underline font-semibold flex items-center gap-1">
                        <span>Browse All</span>
                        <svg class="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 dark:bg-slate-950/60 text-slate-500 dark:text-slate-400 uppercase font-semibold text-[10px] tracking-wider border-b border-slate-200 dark:border-slate-800">
                            <tr>
                                <th class="py-2.5 px-4">Produce</th>
                                <th class="py-2.5 px-4">Category</th>
                                <th class="py-2.5 px-4">Producer & Origin</th>
                                <th class="py-2.5 px-4">Stock</th>
                                <th class="py-2.5 px-4">Farmgate Rate</th>
                                <th class="py-2.5 px-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-800/80 text-slate-700 dark:text-slate-300">
                            @forelse ($this->harvestProducts as $item)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30">
                                    <td class="py-2.5 px-4 font-bold text-slate-900 dark:text-white">{{ $item->name }}</td>
                                    <td class="py-2.5 px-4">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                            {{ $item->category }}
                                        </span>
                                    </td>
                                    <td class="py-2.5 px-4">
                                        <div class="font-medium text-slate-900 dark:text-white flex items-center gap-1">
                                            <span>{{ $item->farmer?->name ?? 'Producer' }}</span>
                                            @if($item->farmer?->status === 'verified')
                                                <svg class="size-3 text-teal-600 dark:text-teal-400 inline" title="DA-RSBSA Verified" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                            @endif
                                        </div>
                                        <div class="text-[10px] text-slate-500">Brgy. {{ $item->barangay }}</div>
                                    </td>
                                    <td class="py-2.5 px-4 font-mono font-medium">{{ number_format($item->quantity) }} {{ $item->unit }}</td>
                                    <td class="py-2.5 px-4 font-bold text-emerald-700 dark:text-emerald-400">₱{{ number_format($item->price, 2) }}</td>
                                    <td class="py-2.5 px-4 text-right">
                                        <div class="inline-flex items-center gap-1.5 justify-end">
                                            <button
                                                wire:click="openInquiryModal({{ $item->id }})"
                                                type="button"
                                                class="px-2.5 py-1 rounded bg-emerald-700 hover:bg-emerald-600 text-white text-[11px] font-semibold transition-colors cursor-pointer"
                                            >
                                                Pre-Order
                                            </button>
                                            @if($item->farmer?->phone_number)
                                                <a
                                                    href="tel:{{ $item->farmer->phone_number }}"
                                                    title="Call Producer"
                                                    class="p-1 rounded bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 transition-colors"
                                                >
                                                    <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                                    </svg>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-6 text-center text-slate-400">No active produce batches available.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination for Buyer Fresh Arrivals -->
                <x-table-pagination :paginator="$this->harvestProducts" label="produce batches" />
            </div>

            <!-- 2. Buyer's Active Pre-Orders Table -->
            @if($this->userInquiries->isNotEmpty())
                <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/90 overflow-hidden shadow-xs">
                    <div class="p-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                        <div>
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900 dark:text-white">My Active Pre-Orders</h3>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400">Status of your purchase pre-orders with local farmers</p>
                        </div>
                        <a href="{{ route('trade-inquiries') }}" wire:navigate class="text-xs text-blue-600 dark:text-blue-400 hover:underline font-semibold">
                            Manage All Pre-Orders
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-slate-50 dark:bg-slate-950/60 text-slate-500 dark:text-slate-400 uppercase font-semibold text-[10px] tracking-wider border-b border-slate-200 dark:border-slate-800">
                                <tr>
                                    <th class="py-2.5 px-4">Produce</th>
                                    <th class="py-2.5 px-4">Producer</th>
                                    <th class="py-2.5 px-4">Farmer Phone</th>
                                    <th class="py-2.5 px-4">Volume</th>
                                    <th class="py-2.5 px-4">Target Pickup</th>
                                    <th class="py-2.5 px-4 text-right">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 dark:divide-slate-800/80 text-slate-700 dark:text-slate-300">
                                @foreach ($this->userInquiries as $inq)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30">
                                        <td class="py-2.5 px-4 font-bold text-slate-900 dark:text-white">{{ $inq->product?->name ?? 'Produce Item' }}</td>
                                        <td class="py-2.5 px-4">{{ $inq->farmer?->name ?? 'Producer' }}</td>
                                        <td class="py-2.5 px-4 font-mono">{{ $inq->farmer?->phone_number ?? '—' }}</td>
                                        <td class="py-2.5 px-4 font-bold text-emerald-700 dark:text-emerald-400 font-mono">{{ number_format($inq->quantity) }} {{ $inq->product?->unit ?? 'kg' }}</td>
                                        <td class="py-2.5 px-4 text-slate-600 dark:text-slate-400">{{ $inq->pickup_date ? $inq->pickup_date->format('M d, Y') : 'Immediate' }}</td>
                                        <td class="py-2.5 px-4 text-right">
                                            @if($inq->status === 'pending')
                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-amber-100 dark:bg-amber-950 text-amber-800 dark:text-amber-300 border border-amber-300 dark:border-amber-800">
                                                    Pending
                                                </span>
                                            @elseif($inq->status === 'accepted')
                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-emerald-100 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-800">
                                                    Accepted
                                                </span>
                                            @else
                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                                    {{ ucfirst($inq->status) }}
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination for Buyer Outgoing Pre-Orders -->
                    <x-table-pagination :paginator="$this->userInquiries" label="pre-orders" />
                </div>
            @endif
        @endif

        <!-- 3. Spot Price Benchmark Highlights Table (Common reference for fair pricing) -->
        <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/90 overflow-hidden shadow-xs">
            <div class="p-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900 dark:text-white">Spot Price Benchmark Highlights</h3>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400">Cantilan prevailing commodity price averages</p>
                </div>
                <a href="{{ route('price-index') }}" wire:navigate class="text-xs text-emerald-700 dark:text-emerald-400 hover:underline font-semibold flex items-center gap-1">
                    <span>Full Price Bulletin</span>
                    <svg class="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 dark:bg-slate-950/60 text-slate-500 dark:text-slate-400 uppercase font-semibold text-[10px] tracking-wider border-b border-slate-200 dark:border-slate-800">
                        <tr>
                            <th class="py-2.5 px-4">Commodity</th>
                            <th class="py-2.5 px-4">Classification</th>
                            <th class="py-2.5 px-4">Prevailing (P_avg)</th>
                            <th class="py-2.5 px-4">Observed Range</th>
                            <th class="py-2.5 px-4 text-right">Trend</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800/80 text-slate-700 dark:text-slate-300">
                        @forelse ($this->paginatedPriceIndex as $item)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30">
                                <td class="py-2.5 px-4 font-semibold text-slate-900 dark:text-white">{{ $item['name'] }}</td>
                                <td class="py-2.5 px-4 text-slate-500 dark:text-slate-400">{{ $item['category'] }}</td>
                                <td class="py-2.5 px-4 font-bold text-emerald-700 dark:text-emerald-400">₱{{ number_format($item['p_avg'], 2) }} / {{ $item['unit'] }}</td>
                                <td class="py-2.5 px-4 font-mono text-slate-600 dark:text-slate-400">₱{{ number_format($item['p_min'], 2) }} - ₱{{ number_format($item['p_max'], 2) }}</td>
                                <td class="py-2.5 px-4 text-right font-medium text-emerald-700 dark:text-emerald-400">{{ $item['trend'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-4 text-center text-slate-400">No commodity price data available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination for Spot Price Benchmark in Overview -->
            <x-table-pagination :paginator="$this->paginatedPriceIndex" label="commodity rates" />
        </div>
    @endif

    <!-- ================================================================= -->
    <!-- VIEW MODE: PRICE_INDEX (Dedicated Spot Price Index Page)          -->
    <!-- ================================================================= -->
    @if($viewMode === 'price_index')
        <div class="space-y-4">
            <!-- Header -->
            <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/90 p-5 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-emerald-100 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-800">
                            Repository D3 • UC-06
                        </span>
                        <span class="text-xs font-mono px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-emerald-700 dark:text-emerald-300 border border-slate-300 dark:border-slate-700">
                            Mathematical Mean: P_avg = ΣP / N
                        </span>
                    </div>
                    <h1 class="text-lg font-black text-slate-900 dark:text-white tracking-tight">
                        Municipal Commodity Spot Market Price Index
                    </h1>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        Official municipal commodity price monitoring bulletin for Cantilan, Surigao del Sur.
                    </p>
                </div>

                <!-- Price Category Filter -->
                <div class="flex items-center gap-2 shrink-0">
                    <span class="text-xs font-semibold text-slate-500">Classification:</span>
                    <select
                        wire:model.live="priceCategoryFilter"
                        class="px-3 py-1.5 text-xs rounded-lg border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-emerald-500"
                    >
                        <option value="All">All Categories</option>
                        <option value="Grains & Cereals">Grains & Cereals</option>
                        <option value="Fruits">Fruits & Orchard</option>
                        <option value="Root Crops">Root Crops</option>
                        <option value="Vegetables">Vegetables</option>
                        <option value="Aquaculture">Aquaculture</option>
                    </select>
                </div>
            </div>

            <!-- Official Municipal Price Table (High Density Data Table with Pagination) -->
            <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/90 overflow-hidden shadow-xs">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 dark:bg-slate-950/60 text-slate-500 dark:text-slate-400 uppercase font-semibold text-[10px] tracking-wider border-b border-slate-200 dark:border-slate-800">
                            <tr>
                                <th class="py-3 px-4">Commodity</th>
                                <th class="py-3 px-4">Classification</th>
                                <th class="py-3 px-4">Average Spot Rate (P_avg)</th>
                                <th class="py-3 px-4">Observed Range (Min - Max)</th>
                                <th class="py-3 px-4">Unit</th>
                                <th class="py-3 px-4">Samples</th>
                                <th class="py-3 px-4">Reporting Barangays</th>
                                <th class="py-3 px-4 text-right">Trend</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-800/80 text-slate-700 dark:text-slate-300">
                            @forelse ($this->paginatedPriceIndex as $item)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30">
                                    <td class="py-3 px-4 font-bold text-slate-900 dark:text-white">{{ $item['name'] }}</td>
                                    <td class="py-3 px-4 text-slate-500 dark:text-slate-400">{{ $item['category'] }}</td>
                                    <td class="py-3 px-4 font-bold text-emerald-700 dark:text-emerald-400">₱{{ number_format($item['p_avg'], 2) }}</td>
                                    <td class="py-3 px-4 font-mono text-slate-700 dark:text-slate-300">₱{{ number_format($item['p_min'], 2) }} - ₱{{ number_format($item['p_max'], 2) }}</td>
                                    <td class="py-3 px-4 text-slate-500">{{ $item['unit'] }}</td>
                                    <td class="py-3 px-4 font-mono text-slate-600 dark:text-slate-400">{{ $item['samples'] }}</td>
                                    <td class="py-3 px-4 text-slate-600 dark:text-slate-400">
                                        {{ implode(', ', array_slice($item['barangays'], 0, 3)) }}
                                    </td>
                                    <td class="py-3 px-4 text-right font-medium text-emerald-700 dark:text-emerald-400">
                                        {{ $item['trend'] }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-8 text-center text-slate-400">No commodity benchmarks found for selected category.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination for Dedicated Price Index Bulletin -->
                <x-table-pagination :paginator="$this->paginatedPriceIndex" label="commodity benchmarks" />
            </div>
        </div>
    @endif

    <!-- ================================================================= -->
    <!-- VIEW MODE: HARVEST_REGISTRY (Dedicated Produce Marketplace)       -->
    <!-- ================================================================= -->
    @if($viewMode === 'harvest_registry')
        <div class="space-y-4">
            <!-- Header -->
            <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/90 p-5 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-emerald-100 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-800">
                            Repository D2 • UC-03
                        </span>
                        <span class="text-xs font-semibold text-slate-500">Cantilan Agriculture</span>
                    </div>
                    <h1 class="text-lg font-black text-slate-900 dark:text-white tracking-tight">
                        Active Harvest Produce Registry Across Cantilan
                    </h1>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        Live produce entries recorded across Cantilan's 17 barangays.
                    </p>
                </div>

                @can('publish-harvest')
                    <button
                        wire:click="openAddProductModal"
                        type="button"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-emerald-700 hover:bg-emerald-600 text-white text-xs font-semibold uppercase tracking-wider transition-colors shadow-xs cursor-pointer shrink-0"
                    >
                        <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>+ Post Harvest Listing</span>
                    </button>
                @endcan
            </div>

            <!-- Search & Filter Controls -->
            <div class="p-3.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/90 shadow-xs flex flex-col md:flex-row items-center justify-between gap-3">
                <div class="relative w-full md:w-72">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                        <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </span>
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Search produce, farmer, barangay..."
                        class="w-full pl-8 pr-3 py-1.5 text-xs rounded-lg border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-emerald-500"
                    >
                </div>

                <div class="flex flex-wrap items-center gap-2 w-full md:w-auto">
                    <select
                        wire:model.live="categoryFilter"
                        class="px-2.5 py-1.5 text-xs rounded-lg border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-emerald-500"
                    >
                        <option value="All">All Categories</option>
                        <option value="Grains & Cereals">Grains & Cereals</option>
                        <option value="Fruits">Fruits & Orchard</option>
                        <option value="Root Crops">Root Crops</option>
                        <option value="Vegetables">Vegetables</option>
                        <option value="Aquaculture">Aquaculture</option>
                    </select>

                    <select
                        wire:model.live="barangayFilter"
                        class="px-2.5 py-1.5 text-xs rounded-lg border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-emerald-500"
                    >
                        <option value="All">All 17 Barangays</option>
                        @foreach (\App\Models\User::BARANGAYS as $brgy)
                            <option value="{{ $brgy }}">Brgy. {{ $brgy }}</option>
                        @endforeach
                    </select>

                    @if($search !== '' || $categoryFilter !== 'All' || $barangayFilter !== 'All')
                        <button
                            wire:click="$set('search', ''); $set('categoryFilter', 'All'); $set('barangayFilter', 'All')"
                            type="button"
                            class="px-2.5 py-1.5 text-xs rounded-lg bg-slate-200 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors cursor-pointer"
                        >
                            Reset
                        </button>
                    @endif
                </div>
            </div>

            <!-- RESPONSIVE PRODUCE DATA TABLE WITH PAGINATION -->
            <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/90 overflow-hidden shadow-xs">
                @if($this->harvestProducts->isEmpty())
                    <div class="p-10 text-center space-y-2">
                        <svg class="size-10 mx-auto text-slate-400 dark:text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                        <h3 class="text-xs font-bold text-slate-900 dark:text-white">No Harvest Batches Found</h3>
                        <p class="text-[11px] text-slate-500">Try adjusting your filters or post a new produce batch.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-slate-50 dark:bg-slate-950/60 text-slate-500 dark:text-slate-400 uppercase font-semibold text-[10px] tracking-wider border-b border-slate-200 dark:border-slate-800">
                                <tr>
                                    <th class="py-3 px-4">Produce / Commodity</th>
                                    <th class="py-3 px-4">Category</th>
                                    <th class="py-3 px-4">Producer</th>
                                    <th class="py-3 px-4">Origin</th>
                                    <th class="py-3 px-4">Available Volume</th>
                                    <th class="py-3 px-4">Farmgate Rate</th>
                                    <th class="py-3 px-4">Status</th>
                                    <th class="py-3 px-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 dark:divide-slate-800/80 text-slate-700 dark:text-slate-300">
                                @foreach ($this->harvestProducts as $item)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30">
                                        <td class="py-3 px-4">
                                            <div class="font-bold text-slate-900 dark:text-white">{{ $item->name }}</div>
                                            @if($item->description)
                                                <div class="text-[11px] text-slate-400 max-w-xs truncate">{{ $item->description }}</div>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4">
                                            <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                                                {{ $item->category }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4">
                                            <div class="font-medium text-slate-900 dark:text-white flex items-center gap-1">
                                                <span>{{ $item->farmer?->name ?? 'Producer' }}</span>
                                                @if($item->farmer?->status === 'verified')
                                                    <svg class="size-3 text-teal-600 dark:text-teal-400 inline" title="DA-RSBSA Verified" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                    </svg>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="py-3 px-4 text-slate-600 dark:text-slate-400">
                                            Brgy. {{ $item->barangay }}
                                        </td>
                                        <td class="py-3 px-4 font-mono font-medium">
                                            {{ number_format($item->quantity) }} {{ $item->unit }}
                                        </td>
                                        <td class="py-3 px-4 font-bold text-emerald-700 dark:text-emerald-400">
                                            ₱{{ number_format($item->price, 2) }} <span class="text-[10px] font-normal text-slate-400">/ {{ $item->unit }}</span>
                                        </td>
                                        <td class="py-3 px-4">
                                            @if($item->status === 'active')
                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-emerald-100 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-800">
                                                    Active
                                                </span>
                                            @elseif($item->status === 'sold_out')
                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-amber-100 dark:bg-amber-950 text-amber-800 dark:text-amber-300 border border-amber-300 dark:border-amber-800">
                                                    Sold Out
                                                </span>
                                            @else
                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                                    {{ ucfirst($item->status) }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 text-right">
                                            <div class="inline-flex items-center gap-1.5 justify-end">
                                                @if(Auth::id() === $item->user_id || Auth::user()->isAdmin())
                                                    <button
                                                        wire:click="openEditProductModal({{ $item->id }})"
                                                        type="button"
                                                        class="px-2.5 py-1.5 rounded bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-semibold transition-colors cursor-pointer inline-flex items-center gap-1"
                                                        title="Edit Listing"
                                                    >
                                                        <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                                        </svg>
                                                        <span>Edit</span>
                                                    </button>
                                                    <button
                                                        wire:click="removeListing({{ $item->id }})"
                                                        wire:confirm="Are you sure you want to remove this harvest listing?"
                                                        type="button"
                                                        class="px-2.5 py-1.5 rounded bg-red-50 hover:bg-red-100 dark:bg-red-950/50 dark:hover:bg-red-900/60 text-red-600 dark:text-red-400 text-xs font-semibold transition-colors cursor-pointer inline-flex items-center gap-1"
                                                        title="Remove Listing"
                                                    >
                                                        <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                        <span>Remove</span>
                                                    </button>
                                                @else
                                                    <button
                                                        wire:click="openInquiryModal({{ $item->id }})"
                                                        type="button"
                                                        class="px-3 py-1.5 rounded-lg bg-emerald-700 hover:bg-emerald-600 text-white text-xs font-semibold uppercase tracking-wider transition-colors shadow-xs cursor-pointer inline-flex items-center gap-1"
                                                    >
                                                        <span>Pre-Order</span>
                                                    </button>
                                                @endif

                                                @if($item->farmer?->phone_number && Auth::id() !== $item->user_id)
                                                    <a
                                                        href="tel:{{ $item->farmer->phone_number }}"
                                                        title="Call {{ $item->farmer->name }}"
                                                        class="p-1.5 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 transition-colors"
                                                    >
                                                        <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                                        </svg>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination for Dedicated Harvest Registry -->
                    <x-table-pagination :paginator="$this->harvestProducts" label="harvest batches" />
                @endif
            </div>
        </div>
    @endif

    <!-- ================================================================= -->
    <!-- VIEW MODE: TRADE_INQUIRIES (Fulfillment & Pre-Orders Manager)     -->
    <!-- ================================================================= -->
    @if($viewMode === 'trade_inquiries')
        <div class="space-y-4">
            <!-- Header -->
            <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/90 p-5 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-blue-100 dark:bg-blue-950 text-blue-800 dark:text-blue-300">
                            Repository D4 • UC-05
                        </span>
                        <span class="text-xs font-semibold text-slate-500">0% Commission Lead Dispatch</span>
                    </div>
                    <h1 class="text-lg font-black text-slate-900 dark:text-white tracking-tight">
                        {{ Auth::user()->isFarmer() ? 'Producer Pre-Order Fulfillment Manager' : (Auth::user()->isAdmin() ? 'Municipal Trade Leads Audit' : 'Your Trade Inquiries') }}
                    </h1>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        Direct buyer pre-orders and trade inquiries with instant status updates.
                    </p>
                </div>
            </div>

            <!-- Inquiries Table -->
            <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/90 overflow-hidden shadow-xs">
                @if($this->userInquiries->isEmpty())
                    <div class="p-10 text-center space-y-2">
                        <svg class="size-10 mx-auto text-slate-400 dark:text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                        </svg>
                        <h3 class="text-xs font-bold text-slate-900 dark:text-white">No Trade Inquiries Found</h3>
                        <p class="text-[11px] text-slate-500">
                            {{ Auth::user()->isFarmer() ? 'Incoming buyer inquiries will appear here.' : 'Browse the Harvest Registry to submit an inquiry.' }}
                        </p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-slate-50 dark:bg-slate-950/60 text-slate-500 dark:text-slate-400 uppercase font-semibold text-[10px] tracking-wider border-b border-slate-200 dark:border-slate-800">
                                <tr>
                                    <th class="py-3 px-4">Produce</th>
                                    <th class="py-3 px-4">{{ Auth::user()->isFarmer() ? 'Buyer' : 'Producer' }}</th>
                                    <th class="py-3 px-4">Contact Phone</th>
                                    <th class="py-3 px-4">Volume</th>
                                    <th class="py-3 px-4">Target Pickup</th>
                                    <th class="py-3 px-4">Notes</th>
                                    <th class="py-3 px-4">Status</th>
                                    <th class="py-3 px-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 dark:divide-slate-800/80 text-slate-700 dark:text-slate-300">
                                @foreach ($this->userInquiries as $inq)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30">
                                        <td class="py-3 px-4 font-semibold text-slate-900 dark:text-white">
                                            {{ $inq->product?->name ?? 'Harvest Batch #' . $inq->product_id }}
                                        </td>
                                        <td class="py-3 px-4">
                                            {{ Auth::user()->isFarmer() ? ($inq->buyer?->name ?? 'Buyer') : ($inq->farmer?->name ?? 'Producer') }}
                                        </td>
                                        <td class="py-3 px-4 font-mono">
                                            {{ Auth::user()->isFarmer() ? ($inq->buyer?->phone_number ?? '—') : ($inq->farmer?->phone_number ?? '—') }}
                                        </td>
                                        <td class="py-3 px-4 font-mono font-bold text-emerald-700 dark:text-emerald-400">
                                            {{ number_format($inq->quantity) }} {{ $inq->product?->unit ?? 'kg' }}
                                        </td>
                                        <td class="py-3 px-4 text-slate-600 dark:text-slate-400">
                                            {{ $inq->pickup_date ? $inq->pickup_date->format('M d, Y') : 'Immediate' }}
                                        </td>
                                        <td class="py-3 px-4 max-w-xs truncate text-slate-500">
                                            {{ $inq->message ?? 'Standard trade inquiry' }}
                                        </td>
                                        <td class="py-3 px-4">
                                            @if($inq->status === 'pending')
                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-amber-100 dark:bg-amber-950 text-amber-800 dark:text-amber-300 border border-amber-300 dark:border-amber-800">
                                                    Pending
                                                </span>
                                            @elseif($inq->status === 'accepted')
                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-emerald-100 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-800">
                                                    Accepted
                                                </span>
                                            @else
                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-300 dark:border-slate-700">
                                                    {{ ucfirst($inq->status) }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 text-right">
                                            @if(Auth::user()->isFarmer() && $inq->status === 'pending')
                                                <div class="inline-flex items-center gap-1.5">
                                                    <button
                                                        wire:click="updateInquiryStatus({{ $inq->id }}, 'accepted')"
                                                        type="button"
                                                        class="px-2.5 py-1 rounded bg-emerald-700 hover:bg-emerald-600 text-white text-[11px] font-semibold transition-colors cursor-pointer"
                                                    >
                                                        Accept
                                                    </button>
                                                    <button
                                                        wire:click="updateInquiryStatus({{ $inq->id }}, 'declined')"
                                                        type="button"
                                                        class="px-2.5 py-1 rounded bg-slate-200 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-[11px] font-semibold transition-colors cursor-pointer"
                                                    >
                                                        Decline
                                                    </button>
                                                </div>
                                            @else
                                                <span class="text-[11px] text-slate-400">Logged</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination for Dedicated Trade Inquiries Table -->
                    <x-table-pagination :paginator="$this->userInquiries" label="trade inquiries" />
                @endif
            </div>
        </div>
    @endif

    <!-- ================================================================= -->
    <!-- MODAL 1: ADD HARVEST LISTING (UC-03)                              -->
    <!-- ================================================================= -->
    @if($showAddProductModal)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="relative w-full max-w-lg rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6 shadow-2xl space-y-4">
                <div class="flex items-start justify-between border-b border-slate-200 dark:border-slate-800 pb-3">
                    <div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white">Post Harvest Listing</h3>
                        <p class="text-xs text-slate-500">Record a newly harvested produce batch for municipal trading.</p>
                    </div>
                    <button
                        wire:click="$set('showAddProductModal', false)"
                        type="button"
                        class="p-1 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer"
                    >
                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form wire:submit="createProduct" class="space-y-3.5">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                            Commodity / Produce Name *
                        </label>
                        <input
                            type="text"
                            wire:model="new_name"
                            placeholder="e.g. Native Milled Rice, Highland Sweet Potato"
                            class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-emerald-500"
                            required
                        >
                        @error('new_name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Category *
                            </label>
                            <select
                                wire:model="new_category"
                                class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-emerald-500"
                            >
                                @foreach (\App\Models\Product::CATEGORIES as $cat)
                                    <option value="{{ $cat }}">{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Unit *
                            </label>
                            <select
                                wire:model="new_unit"
                                class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-emerald-500"
                            >
                                @foreach (\App\Models\Product::UNITS as $code => $label)
                                    <option value="{{ $code }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Available Volume *
                            </label>
                            <input
                                type="number"
                                step="0.5"
                                wire:model="new_quantity"
                                placeholder="500"
                                class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-emerald-500"
                                required
                            >
                            @error('new_quantity') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Farmgate Rate (₱ per Unit) *
                            </label>
                            <input
                                type="number"
                                step="0.5"
                                wire:model="new_price"
                                placeholder="52.00"
                                class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-emerald-500"
                                required
                            >
                            @error('new_price') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Barangay *
                            </label>
                            <select
                                wire:model="new_barangay"
                                class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-emerald-500"
                            >
                                @foreach (\App\Models\User::BARANGAYS as $brgy)
                                    <option value="{{ $brgy }}">Brgy. {{ $brgy }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Harvest Date
                            </label>
                            <input
                                type="date"
                                wire:model="new_harvest_date"
                                class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-emerald-500"
                            >
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                            Description / Quality Notes
                        </label>
                        <textarea
                            wire:model="new_description"
                            rows="2"
                            placeholder="Fresh harvest from Cantilan..."
                            class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-emerald-500"
                        ></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-200 dark:border-slate-800">
                        <button
                            wire:click="$set('showAddProductModal', false)"
                            type="button"
                            class="px-4 py-2 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs font-semibold cursor-pointer"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            wire:loading.attr="disabled"
                            class="px-5 py-2 rounded-lg bg-emerald-700 hover:bg-emerald-600 text-white text-xs font-bold uppercase tracking-wider transition-colors shadow-xs cursor-pointer"
                        >
                            Publish Listing
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- ================================================================= -->
    <!-- MODAL 2: EDIT HARVEST LISTING (Owner Farmer or Admin)             -->
    <!-- ================================================================= -->
    @if($showEditProductModal)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="relative w-full max-w-lg rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6 shadow-2xl space-y-4">
                <div class="flex items-start justify-between border-b border-slate-200 dark:border-slate-800 pb-3">
                    <div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white">Edit Harvest Listing</h3>
                        <p class="text-xs text-slate-500">Update pricing, available volume, or status.</p>
                    </div>
                    <button
                        wire:click="$set('showEditProductModal', false)"
                        type="button"
                        class="p-1 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer"
                    >
                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form wire:submit="updateProduct" class="space-y-3.5">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                            Produce Title *
                        </label>
                        <input
                            type="text"
                            wire:model="edit_title"
                            class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-emerald-500"
                            required
                        >
                        @error('edit_title') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Farmgate Rate (₱ per Unit) *
                            </label>
                            <input
                                type="number"
                                step="0.5"
                                wire:model="edit_price_per_unit"
                                class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-emerald-500"
                                required
                            >
                            @error('edit_price_per_unit') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Available Volume *
                            </label>
                            <input
                                type="number"
                                step="0.5"
                                wire:model="edit_available_quantity"
                                class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-emerald-500"
                                required
                            >
                            @error('edit_available_quantity') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                            Listing Status *
                        </label>
                        <select
                            wire:model="edit_status"
                            class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-emerald-500"
                        >
                            <option value="active">Active (Available for trade)</option>
                            <option value="sold_out">Sold Out</option>
                            <option value="archived">Archived (Unpublished)</option>
                        </select>
                        @error('edit_status') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                            Description / Quality Notes
                        </label>
                        <textarea
                            wire:model="edit_description"
                            rows="2"
                            class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-emerald-500"
                        ></textarea>
                        @error('edit_description') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-200 dark:border-slate-800">
                        <button
                            wire:click="$set('showEditProductModal', false)"
                            type="button"
                            class="px-4 py-2 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs font-semibold cursor-pointer"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            wire:loading.attr="disabled"
                            class="px-5 py-2 rounded-lg bg-emerald-700 hover:bg-emerald-600 text-white text-xs font-bold uppercase tracking-wider transition-colors shadow-xs cursor-pointer"
                        >
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- ================================================================= -->
    <!-- MODAL 3: SUBMIT TRADE INQUIRY (UC-05)                             -->
    <!-- ================================================================= -->
    @if($showInquiryModal && $selectedProductId)
        @php
            $targetProduct = \App\Models\Listing::with('farmer')->find($selectedProductId);
        @endphp
        @if($targetProduct)
            <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
                <div class="relative w-full max-w-lg rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6 shadow-2xl space-y-4">
                    <div class="flex items-start justify-between border-b border-slate-200 dark:border-slate-800 pb-3">
                        <div>
                            <h3 class="text-base font-bold text-slate-900 dark:text-white">Pre-Order Trade Inquiry</h3>
                            <p class="text-xs text-slate-500">
                                Direct pre-order for producer {{ $targetProduct->farmer?->name }} (Brgy. {{ $targetProduct->barangay }}).
                            </p>
                        </div>
                        <button
                            wire:click="$set('showInquiryModal', false)"
                            type="button"
                            class="p-1 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer"
                        >
                            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-950/70 border border-slate-200 dark:border-slate-800 flex items-center justify-between text-xs">
                        <div>
                            <span class="font-bold text-slate-900 dark:text-white block">{{ $targetProduct->name }}</span>
                            <span class="text-slate-500">Available: {{ number_format($targetProduct->quantity) }} {{ $targetProduct->unit }}</span>
                        </div>
                        <div class="text-right">
                            <span class="font-black text-emerald-700 dark:text-emerald-400 text-sm">₱{{ number_format($targetProduct->price, 2) }}</span>
                            <span class="text-[10px] text-slate-500 block">per {{ $targetProduct->unit }}</span>
                        </div>
                    </div>

                    <form wire:submit="submitInquiry" class="space-y-3.5">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                    Requested Volume ({{ $targetProduct->unit }}) *
                                </label>
                                <input
                                    type="number"
                                    step="0.5"
                                    max="{{ (float) $targetProduct->quantity }}"
                                    wire:model.live="inquiry_quantity"
                                    class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-emerald-500"
                                    required
                                >
                                @error('inquiry_quantity') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                    Pickup Date *
                                </label>
                                <input
                                    type="date"
                                    wire:model="inquiry_pickup_date"
                                    class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-emerald-500"
                                    required
                                >
                                @error('inquiry_pickup_date') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        @if($inquiry_quantity && $inquiry_quantity > 0)
                            <div class="p-2.5 rounded-lg bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800/60 flex items-center justify-between text-xs">
                                <span class="font-medium text-emerald-900 dark:text-emerald-200">Estimated Total:</span>
                                <span class="font-black text-emerald-800 dark:text-emerald-300">
                                    ₱{{ number_format($inquiry_quantity * (float) $targetProduct->price, 2) }}
                                </span>
                            </div>
                        @endif

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Notes / Instructions
                            </label>
                            <textarea
                                wire:model="inquiry_message"
                                rows="2"
                                placeholder="Preferred pickup window or instructions..."
                                class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-emerald-500"
                            ></textarea>
                            @error('inquiry_message') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-200 dark:border-slate-800">
                            <button
                                wire:click="$set('showInquiryModal', false)"
                                type="button"
                                class="px-4 py-2 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs font-semibold cursor-pointer"
                            >
                                Cancel
                            </button>
                            <button
                                type="submit"
                                wire:loading.attr="disabled"
                                class="px-5 py-2 rounded-lg bg-emerald-700 hover:bg-emerald-600 text-white text-xs font-bold uppercase tracking-wider transition-colors shadow-xs cursor-pointer"
                            >
                                Dispatch Pre-Order
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    @endif
</div>
