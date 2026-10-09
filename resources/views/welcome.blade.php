<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    @include('partials.head')
    <title>TaboNet | Fresh Harvests & Fair Prices - Municipality of Cantilan</title>
</head>
<body class="min-h-screen bg-emerald-50/30 dark:bg-slate-950 text-slate-800 dark:text-slate-100 antialiased font-sans selection:bg-emerald-600 selection:text-white transition-colors duration-200">

    <!-- Vibrant Agricultural Background Glows -->
    <div class="fixed inset-0 pointer-events-none -z-10 overflow-hidden">
        <!-- Sunlit Warm Amber Glow Top Right -->
        <div class="absolute -top-32 -right-32 w-96 h-96 rounded-full bg-amber-400/15 dark:bg-amber-500/10 blur-3xl pointer-events-none"></div>
        <!-- Lush Green Glow Top Left -->
        <div class="absolute -top-24 -left-24 w-[32rem] h-[32rem] rounded-full bg-emerald-500/15 dark:bg-emerald-600/10 blur-3xl pointer-events-none"></div>
        <!-- Soft Fertile Earth Glow Center -->
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-7xl h-[40rem] bg-gradient-to-b from-emerald-100/30 dark:from-emerald-950/20 via-transparent to-amber-100/20 dark:to-transparent blur-2xl pointer-events-none"></div>
    </div>

    <!-- Official Top Government Notice Bar -->
    <div class="bg-emerald-900 text-emerald-100 dark:bg-slate-900 border-b border-emerald-800/80 dark:border-slate-800 text-xs py-2 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-1.5 text-[11px]">
            <div class="flex items-center gap-2">
                <span class="inline-block size-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span class="font-semibold text-white">Republic of the Philippines</span>
                <span class="opacity-60">•</span>
                <span>Province of Surigao del Sur</span>
                <span class="opacity-60">•</span>
                <span class="text-amber-300 font-medium">Municipality of Cantilan (Zip: 8317)</span>
            </div>
            <div class="flex items-center gap-2 text-emerald-200/90 dark:text-slate-400">
                <span>In Partnership with NEMSU Cantilan Campus</span>
                <span class="opacity-60">•</span>
                <span class="text-white font-medium">Empowering Local Agriculture</span>
            </div>
        </div>
    </div>

    <!-- Main Navigation Bar -->
    <nav class="sticky top-0 z-40 backdrop-blur-md bg-white/95 dark:bg-slate-950/95 border-b border-emerald-100 dark:border-slate-800/80 shadow-2xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <!-- Brand with Official Cantilan Seal -->
            <a href="{{ route('home') }}" class="flex items-center gap-3.5 group">
                <div class="relative">
                    <img src="{{ asset('img/cantilan_logo.png') }}" alt="Municipality of Cantilan Seal" class="h-12 w-12 object-contain drop-shadow transition-transform group-hover:scale-105">
                    <span class="absolute -bottom-1 -right-1 flex h-4 w-4 items-center justify-center rounded-full bg-emerald-600 text-white text-[9px] font-bold shadow-xs">🌾</span>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-xl font-black tracking-tight text-slate-900 dark:text-white uppercase">Tabo<span class="text-emerald-600 dark:text-emerald-500">Net</span></span>
                        <span class="text-[10px] uppercase font-bold tracking-wider px-2 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">Cantilan</span>
                    </div>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 hidden sm:block">Connecting Farmers, Buyers & Daily Market Prices</p>
                </div>
            </a>

            <!-- Navigation Links -->
            <div class="hidden md:flex items-center gap-7 text-xs font-semibold text-slate-700 dark:text-slate-300">
                <a href="#about" class="hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors flex items-center gap-1.5">
                    <span>About TaboNet</span>
                </a>
                <a href="#how-it-works" class="hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors flex items-center gap-1.5">
                    <span>How It Works</span>
                </a>
                <a href="#crops" class="hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors flex items-center gap-1.5">
                    <span>Crops & Produce</span>
                </a>
                <a href="#barangays" class="hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors flex items-center gap-1.5">
                    <span>17 Barangays</span>
                </a>
            </div>

            <!-- Authentication Actions -->
            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ url('/dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold uppercase tracking-wider transition-all shadow-sm shadow-emerald-600/30 hover:shadow-md">
                        <span>Access Dashboard</span>
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 text-xs font-semibold text-slate-700 dark:text-slate-300 hover:text-emerald-700 dark:hover:text-emerald-400 transition-colors">
                        Sign In
                    </a>
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold uppercase tracking-wider transition-all shadow-sm shadow-emerald-600/30 hover:shadow-md">
                        <span>Join Free</span>
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header id="about" class="relative pt-12 pb-20 md:pt-16 md:pb-24 border-b border-emerald-100 dark:border-slate-800/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto space-y-6">
                <!-- Warm Community Badge -->
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-100/80 dark:bg-emerald-950/70 border border-emerald-200 dark:border-emerald-800 text-xs text-emerald-900 dark:text-emerald-300 shadow-2xs">
                    <span class="text-sm">🌱</span>
                    <span class="font-semibold">Direct From Cantilan Farms to Your Table</span>
                </div>

                <!-- Inviting Main Title -->
                <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black tracking-tight text-slate-900 dark:text-white leading-[1.15]">
                    Fresh Local Harvests, <span class="bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent">Fair Prices</span> for Everyone
                </h1>

                <!-- Simple, Warm, Human Subtitle -->
                <p class="text-base sm:text-lg text-slate-600 dark:text-slate-300 leading-relaxed font-normal">
                    TaboNet connects Cantilan's hardworking farmers directly with local buyers, restaurants, and families. No unfair markups, no middlemen — just fresh produce, honest daily market rates, and a stronger farming community.
                </p>

                <!-- Hero Action Buttons -->
                <div class="flex flex-col sm:flex-row items-center justify-center gap-3.5 pt-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 px-7 py-3.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-bold transition-all shadow-md shadow-emerald-600/25 hover:scale-[1.02]">
                            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            <span>Open Your Dashboard</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 px-7 py-3.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-bold transition-all shadow-md shadow-emerald-600/25 hover:scale-[1.02]">
                            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                            </svg>
                            <span>Sign In to Marketplace</span>
                        </a>

                        <a href="{{ route('register') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 px-7 py-3.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-900 text-sm font-bold transition-all shadow-md shadow-amber-500/20 hover:scale-[1.02]">
                            <svg class="size-5 text-slate-900" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                            </svg>
                            <span>Register as Farmer or Buyer</span>
                        </a>
                    @endauth

                    <a href="#how-it-works" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-white dark:bg-slate-900 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-800 text-sm font-semibold transition-colors">
                        <span>How It Works</span>
                    </a>
                </div>
            </div>

            <!-- Key Agricultural Highlights -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mt-16 max-w-5xl mx-auto">
                <!-- Card 1 -->
                <div class="p-5 rounded-2xl bg-white/90 dark:bg-slate-900/90 border border-emerald-100 dark:border-emerald-950/50 shadow-sm space-y-2 text-center group hover:border-emerald-300 dark:hover:border-emerald-700 transition-all">
                    <div class="inline-flex p-2.5 rounded-xl bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300 text-xl group-hover:scale-110 transition-transform">
                        🏡
                    </div>
                    <div class="text-2xl font-black text-slate-900 dark:text-white">17 Barangays</div>
                    <div class="text-xs font-bold text-emerald-700 dark:text-emerald-400">Town-Wide Coverage</div>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400">Connecting farm communities from Linunga-an to Bugsukan</p>
                </div>

                <!-- Card 2 -->
                <div class="p-5 rounded-2xl bg-white/90 dark:bg-slate-900/90 border border-amber-100 dark:border-amber-950/50 shadow-sm space-y-2 text-center group hover:border-amber-300 dark:hover:border-amber-700 transition-all">
                    <div class="inline-flex p-2.5 rounded-xl bg-amber-100 dark:bg-amber-950 text-amber-700 dark:text-amber-300 text-xl group-hover:scale-110 transition-transform">
                        🤝
                    </div>
                    <div class="text-2xl font-black text-slate-900 dark:text-white">0% Fees</div>
                    <div class="text-xs font-bold text-amber-700 dark:text-amber-400">Direct Farmer Trade</div>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400">No middleman cuts — 100% of sales stay with local farmers</p>
                </div>

                <!-- Card 3 -->
                <div class="p-5 rounded-2xl bg-white/90 dark:bg-slate-900/90 border border-teal-100 dark:border-teal-950/50 shadow-sm space-y-2 text-center group hover:border-teal-300 dark:hover:border-teal-700 transition-all">
                    <div class="inline-flex p-2.5 rounded-xl bg-teal-100 dark:bg-teal-950 text-teal-700 dark:text-teal-300 text-xl group-hover:scale-110 transition-transform">
                        📊
                    </div>
                    <div class="text-2xl font-black text-slate-900 dark:text-white">Fair Prices</div>
                    <div class="text-xs font-bold text-teal-700 dark:text-teal-400">Daily Market Rates</div>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400">Updated price monitoring prevents overcharging and undercutting</p>
                </div>

                <!-- Card 4 -->
                <div class="p-5 rounded-2xl bg-white/90 dark:bg-slate-900/90 border border-emerald-100 dark:border-emerald-950/50 shadow-sm space-y-2 text-center group hover:border-emerald-300 dark:hover:border-emerald-700 transition-all">
                    <div class="inline-flex p-2.5 rounded-xl bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300 text-xl group-hover:scale-110 transition-transform">
                        🛡️
                    </div>
                    <div class="text-2xl font-black text-slate-900 dark:text-white">Verified Growers</div>
                    <div class="text-xs font-bold text-emerald-700 dark:text-emerald-400">Official Agriculture Office</div>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400">Legitimate farmers registered with local municipal records</p>
                </div>
            </div>
        </div>
    </header>

    <!-- SECTION: How TaboNet Works (Simple & Step-by-Step) -->
    <section id="how-it-works" class="py-20 bg-white/60 dark:bg-slate-900/40 border-b border-emerald-100 dark:border-slate-800/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-14 space-y-3">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-100 dark:bg-emerald-950/70 border border-emerald-200 dark:border-emerald-800 text-xs font-bold uppercase tracking-wider text-emerald-800 dark:text-emerald-300">
                    <span>Simple 3-Step Process</span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight">How TaboNet Helps Cantilan</h2>
                <p class="text-sm text-slate-600 dark:text-slate-400">
                    Designed so any farmer, market vendor, or resident can easily buy and sell local agricultural goods.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Step 1: For Farmers -->
                <div class="relative p-7 rounded-2xl bg-white dark:bg-slate-900 border border-emerald-100 dark:border-slate-800 space-y-4 shadow-sm hover:shadow-md transition-shadow">
                    <div class="size-10 rounded-xl bg-emerald-600 text-white font-black flex items-center justify-center text-lg shadow-sm shadow-emerald-600/30">
                        1
                    </div>
                    <div class="space-y-1">
                        <span class="text-xs font-bold uppercase tracking-wider text-emerald-700 dark:text-emerald-400">For Farmers</span>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">Post Your Fresh Harvest</h3>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                        Easily list what you grow — Palay, corn, vegetables, or fruits. Add your expected harvest date and quantities in regular units like kilos, sacks (<em>sako</em>), or baskets (<em>kaing</em>).
                    </p>
                </div>

                <!-- Step 2: For Buyers -->
                <div class="relative p-7 rounded-2xl bg-white dark:bg-slate-900 border border-amber-100 dark:border-slate-800 space-y-4 shadow-sm hover:shadow-md transition-shadow">
                    <div class="size-10 rounded-xl bg-amber-500 text-slate-900 font-black flex items-center justify-center text-lg shadow-sm shadow-amber-500/30">
                        2
                    </div>
                    <div class="space-y-1">
                        <span class="text-xs font-bold uppercase tracking-wider text-amber-700 dark:text-amber-400">For Buyers</span>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">Send Direct Inquiries</h3>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                        Browse listings from nearby barangays. Contact the farmer directly to request volumes, set your pickup or delivery time, and agree on fair terms without hidden charges.
                    </p>
                </div>

                <!-- Step 3: For Everyone -->
                <div class="relative p-7 rounded-2xl bg-white dark:bg-slate-900 border border-teal-100 dark:border-slate-800 space-y-4 shadow-sm hover:shadow-md transition-shadow">
                    <div class="size-10 rounded-xl bg-teal-600 text-white font-black flex items-center justify-center text-lg shadow-sm shadow-teal-600/30">
                        3
                    </div>
                    <div class="space-y-1">
                        <span class="text-xs font-bold uppercase tracking-wider text-teal-700 dark:text-teal-400">For the Community</span>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">Check Fair Daily Prices</h3>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                        Stay informed with the live Cantilan Price Board. View fair market averages across commodities so no farmer is underpaid and no buyer is overcharged.
                    </p>
                </div>
            </div>

            <!-- Friendly Callout Banner -->
            <div class="mt-14 p-8 sm:p-10 rounded-3xl bg-gradient-to-r from-emerald-600 to-teal-700 text-white shadow-lg flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="space-y-2 text-center md:text-left">
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/20 text-xs font-semibold">
                        <span>🚜 Open to All Cantilan Agricultural Stakeholders</span>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-black">Ready to post harvests or check today's prices?</h3>
                    <p class="text-xs sm:text-sm text-emerald-100 max-w-xl">
                        Sign in to your free account now to access the live price index, send inquiries, and support local farming.
                    </p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('login') }}" class="px-6 py-3 rounded-xl bg-white hover:bg-emerald-50 text-emerald-800 text-xs font-bold uppercase tracking-wider transition-colors shadow-sm">
                        Sign In Now
                    </a>
                    <a href="{{ route('register') }}" class="px-6 py-3 rounded-xl bg-amber-400 hover:bg-amber-300 text-slate-900 text-xs font-bold uppercase tracking-wider transition-colors shadow-sm">
                        Create Account
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION: Crops & Agricultural Commodities -->
    <section id="crops" class="py-20 border-b border-emerald-100 dark:border-slate-800/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-14 space-y-3">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-100 dark:bg-amber-950/70 border border-amber-200 dark:border-amber-800 text-xs font-bold uppercase tracking-wider text-amber-800 dark:text-amber-300">
                    <span>What Cantilan Grows</span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight">Main Agricultural Commodities</h2>
                <p class="text-sm text-slate-600 dark:text-slate-400">
                    TaboNet tracks listings and fair market prices for key crops grown across our local farms:
                </p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6 max-w-5xl mx-auto">
                <!-- Category 1 -->
                <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-center space-y-3 hover:border-emerald-300 transition-colors shadow-xs">
                    <div class="text-3xl">🌾</div>
                    <h3 class="font-bold text-slate-900 dark:text-white text-base">Palay & Corn</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Dry & wet palay, white and yellow corn harvested from our fertile river valleys.</p>
                </div>

                <!-- Category 2 -->
                <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-center space-y-3 hover:border-emerald-300 transition-colors shadow-xs">
                    <div class="text-3xl">🥬</div>
                    <h3 class="font-bold text-slate-900 dark:text-white text-base">Fresh Vegetables</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Eggplants, tomatoes, beans, squash, and leafy greens grown by local families.</p>
                </div>

                <!-- Category 3 -->
                <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-center space-y-3 hover:border-emerald-300 transition-colors shadow-xs">
                    <div class="text-3xl">🍌</div>
                    <h3 class="font-bold text-slate-900 dark:text-white text-base">Fruits & Root Crops</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Bananas, kamote (sweet potato), balanghoy (cassava), and seasonal fruits.</p>
                </div>

                <!-- Category 4 -->
                <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-center space-y-3 hover:border-emerald-300 transition-colors shadow-xs">
                    <div class="text-3xl">🥥</div>
                    <h3 class="font-bold text-slate-900 dark:text-white text-base">Coconut & Agri-Goods</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Fresh coconuts, copra, and processed farm products straight from growers.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION: 17 Cantilan Barangays Directory -->
    <section id="barangays" class="py-20 bg-emerald-50/40 dark:bg-slate-900/30 border-b border-emerald-100 dark:border-slate-800/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-12 space-y-3">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-100 dark:bg-emerald-950/70 border border-emerald-200 dark:border-emerald-800 text-xs font-bold uppercase tracking-wider text-emerald-800 dark:text-emerald-300">
                    <span>Our Town Directory</span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight">Proudly Serving All 17 Barangays</h2>
                <p class="text-sm text-slate-600 dark:text-slate-400">
                    Connecting farmers, buyers, and public markets across the Municipality of Cantilan:
                </p>
            </div>

            <!-- Directory Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3 max-w-5xl mx-auto">
                @foreach (\App\Models\User::BARANGAYS as $brgy)
                    <div class="p-3 rounded-xl bg-white dark:bg-slate-900 border border-emerald-100 dark:border-slate-800 text-center hover:border-emerald-400 dark:hover:border-emerald-600 transition-colors shadow-2xs">
                        <span class="text-xs font-semibold text-slate-800 dark:text-slate-200">Brgy. {{ $brgy }}</span>
                    </div>
                @endforeach
                <div class="p-3 rounded-xl bg-emerald-600 text-white text-center flex items-center justify-center font-bold text-xs shadow-xs">
                    <span>Cantilan, SDS 8317</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Friendly Municipal & Institutional Footer -->
    <footer class="bg-white dark:bg-slate-950 py-14 text-slate-600 dark:text-slate-400 text-xs border-t border-slate-200 dark:border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 pb-10 border-b border-slate-200 dark:border-slate-800">
                <!-- Col 1: Government & Seal -->
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('img/cantilan_logo.png') }}" alt="Cantilan Official Seal" class="h-11 w-11 object-contain">
                        <div>
                            <span class="text-base font-black text-slate-900 dark:text-white uppercase tracking-tight">TaboNet Cantilan</span>
                            <p class="text-[11px] text-emerald-700 dark:text-emerald-400 font-medium">Municipal Agricultural Marketplace</p>
                        </div>
                    </div>
                    <p class="text-slate-600 dark:text-slate-400 leading-relaxed text-[11px]">
                        A community-centered marketplace and daily price monitoring system connecting local farmers and buyers across Cantilan, Surigao del Sur.
                    </p>
                </div>

                <!-- Col 2: Navigation -->
                <div class="space-y-2">
                    <h4 class="font-bold text-slate-900 dark:text-white uppercase tracking-wider text-[11px]">Quick Links</h4>
                    <ul class="space-y-1.5 text-[11px]">
                        <li><a href="{{ url('/dashboard') }}" class="hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">Marketplace Dashboard</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">Sign In to Account</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">Register as Farmer / Buyer</a></li>
                        <li><a href="#how-it-works" class="hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">How It Works</a></li>
                    </ul>
                </div>

                <!-- Col 3: Academic & Municipal Partnership -->
                <div class="space-y-2">
                    <h4 class="font-bold text-slate-900 dark:text-white uppercase tracking-wider text-[11px]">Institutional Partners</h4>
                    <p class="text-slate-800 dark:text-slate-200 font-semibold text-[11px]">Municipality of Cantilan & North Eastern Mindanao State University (NEMSU)</p>
                    <p class="text-[11px]">NEMSU Cantilan Campus, Surigao del Sur 8317</p>
                    <p class="text-[11px]">Municipal Agriculture Office • Cantilan Public Market</p>
                    <p class="text-[11px]"><a href="https://www.nemsu.edu.ph" target="_blank" class="text-emerald-600 dark:text-emerald-400 hover:underline">www.nemsu.edu.ph</a></p>
                </div>
            </div>

            <div class="pt-6 flex flex-col sm:flex-row items-center justify-between text-[11px] text-slate-500 dark:text-slate-400 gap-3">
                <p>© {{ date('Y') }} TaboNet • Municipality of Cantilan & NEMSU Cantilan Campus.</p>
                <div class="flex items-center gap-3">
                    <span>Cantilan, SDS 8317</span>
                    <span>•</span>
                    <span>Supporting Local Agriculture</span>
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
