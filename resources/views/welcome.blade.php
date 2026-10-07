<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    @include('partials.head')
    <title>TaboNet | Municipal Marketplace & Price Monitoring System - Cantilan, Surigao del Sur</title>
</head>
<body class="min-h-screen bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100 antialiased font-sans selection:bg-emerald-600 selection:text-white transition-colors duration-200">

    <!-- Subtle Institutional Grid Background -->
    <div class="fixed inset-0 pointer-events-none -z-10">
        <div class="absolute inset-0 bg-[radial-gradient(#cbd5e1_1px,transparent_1px)] dark:bg-[radial-gradient(#1e293b_1px,transparent_1px)] [background-size:24px_24px] opacity-40"></div>
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-7xl h-96 bg-gradient-to-b from-emerald-500/10 dark:from-emerald-950/20 via-slate-100/10 dark:via-slate-900/10 to-transparent pointer-events-none"></div>
    </div>

    <!-- Official Top Government Bar -->
    <div class="bg-slate-100 dark:bg-slate-900/90 border-b border-slate-200 dark:border-slate-800 text-xs text-slate-600 dark:text-slate-400 py-1.5 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-1 text-[11px]">
            <div class="flex items-center gap-2">
                <span class="font-semibold text-slate-800 dark:text-slate-300">Republic of the Philippines</span>
                <span>•</span>
                <span>Province of Surigao del Sur</span>
                <span>•</span>
                <span class="text-emerald-700 dark:text-emerald-400 font-medium">Municipality of Cantilan (Zip: 8317)</span>
            </div>
            <div class="flex items-center gap-3 text-slate-500 dark:text-slate-400">
                <span>NEMSU Cantilan Campus</span>
                <span>•</span>
                <span>Agile Scrum SDLC Aligned</span>
            </div>
        </div>
    </div>

    <!-- Main Navigation Bar -->
    <nav class="sticky top-0 z-40 backdrop-blur-md bg-white/90 dark:bg-slate-950/90 border-b border-slate-200 dark:border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <!-- Brand with Official Cantilan Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-3.5 group">
                <img src="{{ asset('img/cantilan_logo.png') }}" alt="Municipality of Cantilan Seal" class="h-12 w-12 object-contain drop-shadow transition-transform group-hover:scale-105">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-xl font-black tracking-tight text-slate-900 dark:text-white uppercase">Tabo<span class="text-emerald-600 dark:text-emerald-500">Net</span></span>
                        <span class="text-[10px] uppercase font-bold tracking-wider px-2 py-0.5 rounded bg-emerald-100 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-400 border border-emerald-300 dark:border-emerald-800/80">Cantilan</span>
                    </div>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 hidden sm:block">Agricultural Marketplace & Price Monitoring System</p>
                </div>
            </a>

            <!-- Navigation Links -->
            <div class="hidden md:flex items-center gap-8 text-xs font-semibold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                <a href="#overview" class="hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors flex items-center gap-1.5">
                    <span>Portal Overview</span>
                </a>
                <a href="#how-it-works" class="hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors flex items-center gap-1.5">
                    <span>Core Architecture</span>
                </a>
                <a href="#barangays" class="hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors flex items-center gap-1.5">
                    <span>17 Barangays</span>
                </a>
            </div>

            <!-- Authentication Actions -->
            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ url('/dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-emerald-700 hover:bg-emerald-600 text-white text-xs font-semibold uppercase tracking-wider transition-colors shadow-sm">
                        <span>Access Dashboard</span>
                        <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-3.5 py-2 text-xs font-semibold uppercase tracking-wider text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white transition-colors">
                        Sign In
                    </a>
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-emerald-700 hover:bg-emerald-600 text-white text-xs font-semibold uppercase tracking-wider transition-colors shadow-sm">
                        <span>Register</span>
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header id="overview" class="relative pt-16 pb-24 md:pt-20 md:pb-28 border-b border-slate-200 dark:border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-4xl mx-auto space-y-6">
                <!-- Formal Joint Accreditation Header -->
                <div class="inline-flex flex-wrap items-center justify-center gap-2 px-3.5 py-1.5 rounded-full bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700/80 text-xs text-slate-700 dark:text-slate-300 shadow-xs">
                    <img src="{{ asset('img/cantilan_logo.png') }}" alt="Seal" class="h-4 w-4 object-contain">
                    <span class="font-medium">Municipality of Cantilan, Surigao del Sur</span>
                    <span class="text-slate-400 dark:text-slate-600">•</span>
                    <span class="text-emerald-700 dark:text-emerald-400 font-semibold">North Eastern Mindanao State University (NEMSU)</span>
                </div>

                <!-- Executive Title -->
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight text-slate-900 dark:text-white leading-tight">
                    Municipal Agricultural Marketplace & Spot-Market Price Monitoring System
                </h1>

                <!-- Formal Subtitle -->
                <p class="text-sm sm:text-base text-slate-600 dark:text-slate-300 max-w-3xl mx-auto leading-relaxed">
                    A centralized municipal agricultural portal establishing direct producer-buyer trade across all 17 barangays of Cantilan. Sign in to your dashboard to access live commodity price averages, active harvest inventories, and trade inquiry dispatching.
                </p>

                <!-- Formal Action Buttons -->
                <div class="flex flex-col sm:flex-row items-center justify-center gap-3 pt-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-lg bg-emerald-700 hover:bg-emerald-600 text-white text-xs font-bold uppercase tracking-wider transition-colors shadow-sm">
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            <span>Open Municipal Dashboard</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-lg bg-emerald-700 hover:bg-emerald-600 text-white text-xs font-bold uppercase tracking-wider transition-colors shadow-sm">
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                            </svg>
                            <span>Sign In to Dashboard</span>
                        </a>

                        <a href="{{ route('register') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-lg bg-white dark:bg-slate-900 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-800 dark:text-slate-200 border border-slate-300 dark:border-slate-700 text-xs font-bold uppercase tracking-wider transition-colors shadow-xs">
                            <svg class="size-4 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                            </svg>
                            <span>Register New Account</span>
                        </a>
                    @endauth

                    <a href="#how-it-works" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 text-xs font-bold uppercase tracking-wider transition-colors">
                        <span>Learn How It Operates</span>
                    </a>
                </div>
            </div>

            <!-- Key Metric Indicators (Formal) -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-16 max-w-5xl mx-auto">
                <div class="p-4 rounded-xl bg-white dark:bg-slate-900/90 border border-slate-200 dark:border-slate-800 text-center space-y-1 shadow-xs">
                    <div class="text-2xl font-black text-slate-900 dark:text-white">17 Barangays</div>
                    <div class="text-[11px] font-bold text-emerald-700 dark:text-emerald-400 uppercase tracking-wider">Territorial Scope</div>
                    <div class="text-[10px] text-slate-500 dark:text-slate-400">Complete municipal integration</div>
                </div>
                <div class="p-4 rounded-xl bg-white dark:bg-slate-900/90 border border-slate-200 dark:border-slate-800 text-center space-y-1 shadow-xs">
                    <div class="text-2xl font-black text-slate-900 dark:text-white">0% Commission</div>
                    <div class="text-[11px] font-bold text-emerald-700 dark:text-emerald-400 uppercase tracking-wider">Direct Transaction</div>
                    <div class="text-[10px] text-slate-500 dark:text-slate-400">100% smallholder return</div>
                </div>
                <div class="p-4 rounded-xl bg-white dark:bg-slate-900/90 border border-slate-200 dark:border-slate-800 text-center space-y-1 shadow-xs">
                    <div class="text-2xl font-black text-slate-900 dark:text-white">P_avg Aggregation</div>
                    <div class="text-[11px] font-bold text-emerald-700 dark:text-emerald-400 uppercase tracking-wider">Statistical Model</div>
                    <div class="text-[10px] text-slate-500 dark:text-slate-400">Time-series spot market audit</div>
                </div>
                <div class="p-4 rounded-xl bg-white dark:bg-slate-900/90 border border-slate-200 dark:border-slate-800 text-center space-y-1 shadow-xs">
                    <div class="text-2xl font-black text-slate-900 dark:text-white">DA-RSBSA Aligned</div>
                    <div class="text-[11px] font-bold text-emerald-700 dark:text-emerald-400 uppercase tracking-wider">Producer Registry</div>
                    <div class="text-[10px] text-slate-500 dark:text-slate-400">Verified farmer credentials</div>
                </div>
            </div>
        </div>
    </header>

    <!-- SECTION: System Architecture & Methodology (Agile Scrum) -->
    <section id="how-it-works" class="py-16 bg-slate-100/60 dark:bg-slate-900/30 border-b border-slate-200 dark:border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-12 space-y-2">
                <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded bg-slate-200 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-[11px] font-semibold uppercase tracking-wider text-emerald-800 dark:text-emerald-400">
                    <span>Engineering Specifications</span>
                </div>
                <h2 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">Structured SDLC Architecture</h2>
                <p class="text-xs text-slate-600 dark:text-slate-400">
                    TaboNet adheres to Agile Scrum framework standards, establishing normalized relational persistence (3NF) across four core data repositories:
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Pillar 1 -->
                <div class="p-6 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 space-y-3 shadow-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-mono font-bold text-emerald-700 dark:text-emerald-400 uppercase tracking-wider">
                            Repository D2 • UC-03
                        </span>
                        <span class="text-xs px-2 py-0.5 rounded bg-emerald-100 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-400 font-semibold">Producer</span>
                    </div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white">Producer Inventory Management</h3>
                    <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                        Registered smallholder farmers record harvest parameters, stock quantities, and pricing data formatted in municipal agricultural metrics (kg, kaing, sako) directly from the dashboard.
                    </p>
                </div>

                <!-- Pillar 2 -->
                <div class="p-6 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 space-y-3 shadow-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-mono font-bold text-emerald-700 dark:text-emerald-400 uppercase tracking-wider">
                            Repository D4 • UC-05
                        </span>
                        <span class="text-xs px-2 py-0.5 rounded bg-blue-100 dark:bg-blue-950 text-blue-800 dark:text-blue-400 font-semibold">Buyer</span>
                    </div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white">Direct Trade Lead Dispatch</h3>
                    <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                        Purchasers submit structured inquiry payloads specifying required volume, pickup timestamps, and delivery queries directly to producer inboxes with real-time status tracking.
                    </p>
                </div>

                <!-- Pillar 3 -->
                <div class="p-6 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 space-y-3 shadow-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-mono font-bold text-emerald-700 dark:text-emerald-400 uppercase tracking-wider">
                            Repository D3 • UC-06
                        </span>
                        <span class="text-xs px-2 py-0.5 rounded bg-purple-100 dark:bg-purple-950 text-purple-800 dark:text-purple-400 font-semibold">Municipal</span>
                    </div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white">Time-Series Price Snapshot Engine</h3>
                    <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                        Automatic audit logging tracks every price adjustment, compiling statistical ranges (P_avg, P_min, P_max) on the dashboard to mitigate predatory spot-market fluctuations.
                    </p>
                </div>
            </div>

            <!-- Dashboard Callout Banner -->
            <div class="mt-12 p-8 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="space-y-1 text-center md:text-left">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">Ready to inspect prices or post harvest listings?</h3>
                    <p class="text-xs text-slate-600 dark:text-slate-400 max-w-xl">
                        Log in to your authorized TaboNet dashboard to view the live Municipal Price Board, filter harvest listings, and submit pre-order inquiries.
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('login') }}" class="px-5 py-2.5 rounded-lg bg-emerald-700 hover:bg-emerald-600 text-white text-xs font-semibold uppercase tracking-wider transition-colors shadow-xs">
                        Open Dashboard
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION: 17 Cantilan Barangays Administrative Directory -->
    <section id="barangays" class="py-16 border-b border-slate-200 dark:border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-10 space-y-2">
                <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded bg-slate-200 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-[11px] font-semibold uppercase tracking-wider text-emerald-800 dark:text-emerald-400">
                    <span>Municipal Geographical Integration</span>
                </div>
                <h2 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">Administrative Barangay Coverage (17)</h2>
                <p class="text-xs text-slate-600 dark:text-slate-400">
                    Agricultural trade data cataloged across all seventeen constituent barangays of the Municipality of Cantilan, Surigao del Sur:
                </p>
            </div>

            <!-- Directory Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-2.5 max-w-5xl mx-auto">
                @foreach (\App\Models\User::BARANGAYS as $brgy)
                    <div class="p-2.5 rounded-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-center hover:border-slate-300 dark:hover:border-slate-700 transition-colors shadow-xs">
                        <span class="text-xs font-medium text-slate-700 dark:text-slate-300">Brgy. {{ $brgy }}</span>
                    </div>
                @endforeach
                <div class="p-2.5 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-300 dark:border-emerald-800/80 text-center flex items-center justify-center">
                    <span class="text-xs font-bold text-emerald-800 dark:text-emerald-300">Zip: 8317 SDS</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Formal Municipal & Institutional Footer -->
    <footer class="bg-slate-100 dark:bg-slate-950 py-12 text-slate-600 dark:text-slate-400 text-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 pb-10 border-b border-slate-200 dark:border-slate-800">
                <!-- Col 1: Government & Seal -->
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('img/cantilan_logo.png') }}" alt="Cantilan Official Seal" class="h-10 w-10 object-contain">
                        <div>
                            <span class="text-base font-bold text-slate-900 dark:text-white uppercase tracking-tight">TaboNet</span>
                            <p class="text-[10px] text-slate-500 dark:text-slate-400">Municipality of Cantilan</p>
                        </div>
                    </div>
                    <p class="text-slate-600 dark:text-slate-400 leading-relaxed text-[11px]">
                        A Web-Based Marketplace and Price Monitoring System for Local Farmers and Buyers in Cantilan, Surigao del Sur.
                    </p>
                </div>

                <!-- Col 2: Navigation -->
                <div class="space-y-2">
                    <h4 class="font-bold text-slate-900 dark:text-white uppercase tracking-wider text-[11px]">Portal Navigation</h4>
                    <ul class="space-y-1.5 text-[11px]">
                        <li><a href="{{ url('/dashboard') }}" class="hover:text-emerald-700 dark:hover:text-emerald-400">Municipal Dashboard</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-emerald-700 dark:hover:text-emerald-400">User Account Sign In</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-emerald-700 dark:hover:text-emerald-400">Producer/Buyer Registration</a></li>
                    </ul>
                </div>

                <!-- Col 3: Academic Institution -->
                <div class="space-y-2">
                    <h4 class="font-bold text-slate-900 dark:text-white uppercase tracking-wider text-[11px]">Academic Institution</h4>
                    <p class="text-slate-800 dark:text-slate-300 font-semibold text-[11px]">North Eastern Mindanao State University</p>
                    <p class="text-[11px]">NEMSU Cantilan Campus</p>
                    <p class="text-[11px]">Cantilan, Surigao del Sur 8317</p>
                    <p class="text-[11px]">Official Tel: 086-212-2723</p>
                    <p class="text-[11px]"><a href="https://www.nemsu.edu.ph" target="_blank" class="text-emerald-700 dark:text-emerald-400 hover:underline">www.nemsu.edu.ph</a></p>
                </div>

                <!-- Col 4: Standards -->
                <div class="space-y-2">
                    <h4 class="font-bold text-slate-900 dark:text-white uppercase tracking-wider text-[11px]">Technical Compliance</h4>
                    <p class="text-[11px]"><strong class="text-slate-800 dark:text-slate-300">Methodology:</strong> Agile (Scrum Framework)</p>
                    <p class="text-[11px]"><strong class="text-slate-800 dark:text-slate-300">Standards:</strong> 3NF Relational Persistence (D1–D4)</p>
                    <p class="text-[11px]"><strong class="text-slate-800 dark:text-slate-300">Jurisdiction:</strong> Cantilan, Surigao del Sur</p>
                    <p class="text-[11px]"><strong class="text-slate-800 dark:text-slate-300">Accreditation:</strong> DA-RSBSA Compliant</p>
                </div>
            </div>

            <div class="pt-6 flex flex-col sm:flex-row items-center justify-between text-[11px] text-slate-500 dark:text-slate-400 gap-3">
                <p>© {{ date('Y') }} TaboNet • Municipality of Cantilan & North Eastern Mindanao State University (NEMSU).</p>
                <div class="flex items-center gap-3">
                    <span>Cantilan, SDS 8317</span>
                    <span>•</span>
                    <span>ISO 9001 Aligned</span>
                    <span>•</span>
                    <span>Republic of the Philippines</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bottom Right Dark / Light Mode Floating Toggle -->
    <x-theme-toggle />

    @fluxScripts
</body>
</html>
