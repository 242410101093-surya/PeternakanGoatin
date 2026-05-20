@extends('layouts.admin')

@section('title', 'Dashboard Overview')

@section('content')
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4 mb-stack-lg">
    <div>
        <h2 class="font-h2 text-h2 text-on-surface mb-stack-xs">Dashboard Overview</h2>
        <p class="font-body-md text-body-md text-on-surface-variant">Welcome back. Here's a summary of Goatin platform activity.</p>
    </div>
    <button class="bg-primary text-on-primary px-4 py-2 rounded-lg font-label-sm text-label-sm hover:opacity-90 transition-opacity flex items-center gap-2 ambient-shadow">
        <span class="material-symbols-outlined text-sm">download</span>
        Export Report
    </button>
</div>

<!-- Stats Bento Grid -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-gutter mb-stack-lg">
    <!-- Stat Card 1 -->
    <div class="bg-surface-container-lowest border border-surface-container-highest rounded-xl p-gutter ambient-shadow-hover transition-shadow relative overflow-hidden">
        <div class="absolute top-0 right-0 p-4 opacity-10">
            <span class="material-symbols-outlined text-6xl text-primary">group</span>
        </div>
        <div class="relative z-10">
            <p class="font-label-sm text-label-sm text-on-surface-variant mb-2">Total Users</p>
            <p class="font-h1 text-h1 text-on-surface">{{ number_format($totalUsers) }}</p>
            <div class="flex items-center gap-1 mt-stack-sm text-primary">
                <span class="material-symbols-outlined text-sm">trending_up</span>
                <span class="font-caption text-caption font-bold">+14% vs last month</span>
            </div>
        </div>
    </div>

    <!-- Stat Card 2 -->
    <div class="bg-surface-container-lowest border border-surface-container-highest rounded-xl p-gutter ambient-shadow-hover transition-shadow relative overflow-hidden">
        <div class="absolute top-0 right-0 p-4 opacity-10">
            <span class="material-symbols-outlined text-6xl text-tertiary">payments</span>
        </div>
        <div class="relative z-10">
            <p class="font-label-sm text-label-sm text-on-surface-variant mb-2">Total Sales</p>
            <p class="font-h1 text-h1 text-on-surface">Rp {{ number_format($totalSales, 0, ',', '.') }}</p>
            <div class="flex items-center gap-1 mt-stack-sm text-primary">
                <span class="material-symbols-outlined text-sm">trending_up</span>
                <span class="font-caption text-caption font-bold">+8.2% vs last month</span>
            </div>
        </div>
    </div>

    <!-- Stat Card 3 -->
    <div class="bg-surface-container-lowest border border-surface-container-highest rounded-xl p-gutter ambient-shadow-hover transition-shadow relative overflow-hidden">
        <div class="absolute top-0 right-0 p-4 opacity-10">
            <span class="material-symbols-outlined text-6xl text-error">pending_actions</span>
        </div>
        <div class="relative z-10">
            <p class="font-label-sm text-label-sm text-on-surface-variant mb-2">Pending Orders</p>
            <p class="font-h1 text-h1 text-on-surface">{{ number_format($pendingOrders) }}</p>
            <div class="flex items-center gap-1 mt-stack-sm text-tertiary">
                <span class="material-symbols-outlined text-sm">schedule</span>
                <span class="font-caption text-caption font-bold">Needs attention</span>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-gutter">
    <!-- Main Chart Area -->
    <div class="lg:col-span-2 bg-surface-container-lowest border border-surface-container-highest rounded-xl p-gutter ambient-shadow-hover transition-shadow">
        <div class="flex justify-between items-center mb-stack-md">
            <h3 class="font-h3 text-h3 text-on-surface">Monthly Sales Growth</h3>
            <select class="bg-surface-container border border-outline-variant text-on-surface-variant rounded-md px-3 py-1 text-sm focus:outline-none focus:border-primary">
                <option>Last 6 Months</option>
                <option>This Year</option>
            </select>
        </div>
        <!-- Simulated Line Chart -->
        <div class="relative h-64 w-full mt-8 flex items-end justify-between gap-2 px-2">
            <!-- Y-Axis labels -->
            <div class="absolute left-0 top-0 bottom-8 flex flex-col justify-between text-xs text-outline font-caption w-8">
                <span>50M</span>
                <span>40M</span>
                <span>30M</span>
                <span>20M</span>
                <span>10M</span>
                <span>0</span>
            </div>
            <!-- Grid lines -->
            <div class="absolute inset-0 left-10 bottom-8 flex flex-col justify-between pointer-events-none opacity-20">
                <div class="border-b border-outline w-full"></div>
                <div class="border-b border-outline w-full"></div>
                <div class="border-b border-outline w-full"></div>
                <div class="border-b border-outline w-full"></div>
                <div class="border-b border-outline w-full"></div>
                <div class="border-b border-outline w-full"></div>
            </div>
            <!-- Data Points -->
            <div class="ml-10 flex-1 h-full pb-8 relative">
                <!-- Simulated curve using SVG -->
                <svg class="absolute inset-0 w-full h-full pb-8 pointer-events-none" preserveAspectRatio="none" viewBox="0 0 100 100">
                    <path d="M 5,80 Q 12.5,75 20,70 T 35,60 T 50,52 T 70,35 T 95,20" fill="none" stroke="#4a7c59" stroke-width="2" vector-effect="non-scaling-stroke"></path>
                    <path d="M 5,100 L 5,80 Q 12.5,75 20,70 T 35,60 T 50,52 T 70,35 T 95,20 L 95,100 Z" fill="url(#grad1)" opacity="0.2"></path>
                    <defs>
                        <linearGradient id="grad1" x1="0%" x2="0%" y1="0%" y2="100%">
                            <stop offset="0%" style="stop-color:#4a7c59;stop-opacity:1"></stop>
                            <stop offset="100%" style="stop-color:#4a7c59;stop-opacity:0"></stop>
                        </linearGradient>
                    </defs>
                </svg>
                <div class="absolute left-[5%] bottom-[20%] w-2 h-2 sm:w-3 sm:h-3 bg-primary rounded-full z-10 relative group cursor-pointer" style="transform: translateX(-50%);">
                    <div class="absolute bottom-full mb-2 left-1/2 -translate-x-1/2 bg-inverse-surface text-on-secondary px-2 py-1 rounded text-[10px] sm:text-xs hidden group-hover:block whitespace-nowrap">Jan: 12M</div>
                </div>
                <div class="absolute left-[20%] bottom-[30%] w-2 h-2 sm:w-3 sm:h-3 bg-primary rounded-full z-10 relative group cursor-pointer" style="transform: translateX(-50%);">
                    <div class="absolute bottom-full mb-2 left-1/2 -translate-x-1/2 bg-inverse-surface text-on-secondary px-2 py-1 rounded text-[10px] sm:text-xs hidden group-hover:block whitespace-nowrap">Feb: 18M</div>
                </div>
                <div class="absolute left-[35%] bottom-[40%] w-2 h-2 sm:w-3 sm:h-3 bg-primary rounded-full z-10 relative group cursor-pointer" style="transform: translateX(-50%);">
                    <div class="absolute bottom-full mb-2 left-1/2 -translate-x-1/2 bg-inverse-surface text-on-secondary px-2 py-1 rounded text-[10px] sm:text-xs hidden group-hover:block whitespace-nowrap">Mar: 25M</div>
                </div>
                <div class="absolute left-[50%] bottom-[50%] w-2 h-2 sm:w-3 sm:h-3 bg-primary rounded-full z-10 relative group cursor-pointer" style="transform: translateX(-50%);">
                    <div class="absolute bottom-full mb-2 left-1/2 -translate-x-1/2 bg-inverse-surface text-on-secondary px-2 py-1 rounded text-[10px] sm:text-xs hidden group-hover:block whitespace-nowrap">Apr: 32M</div>
                </div>
                <div class="absolute left-[70%] bottom-[65%] w-2 h-2 sm:w-3 sm:h-3 bg-primary rounded-full z-10 relative group cursor-pointer" style="transform: translateX(-50%);">
                    <div class="absolute bottom-full mb-2 left-1/2 -translate-x-1/2 bg-inverse-surface text-on-secondary px-2 py-1 rounded text-[10px] sm:text-xs hidden group-hover:block whitespace-nowrap">May: 40M</div>
                </div>
                <div class="absolute left-[95%] bottom-[80%] w-2 h-2 sm:w-3 sm:h-3 bg-primary rounded-full z-10 relative group cursor-pointer" style="transform: translateX(-50%);">
                    <div class="absolute bottom-full mb-2 left-1/2 -translate-x-1/2 bg-inverse-surface text-on-secondary px-2 py-1 rounded text-[10px] sm:text-xs hidden group-hover:block whitespace-nowrap">Jun: 48M</div>
                </div>
            </div>
            <!-- X-Axis Labels -->
            <div class="absolute bottom-0 left-10 right-0 flex justify-around text-xs text-outline font-caption h-6 items-end">
                <span>Jan</span>
                <span>Feb</span>
                <span>Mar</span>
                <span>Apr</span>
                <span>May</span>
                <span>Jun</span>
            </div>
        </div>
    </div>

    <!-- Secondary Chart Area -->
    <div class="bg-surface-container-lowest border border-surface-container-highest rounded-xl p-gutter ambient-shadow-hover transition-shadow flex flex-col">
        <h3 class="font-h3 text-h3 text-on-surface mb-stack-md">User Distribution</h3>
        <div class="flex-1 flex flex-col items-center justify-center relative">
            <!-- Simulated Donut Chart -->
            <div class="w-40 h-40 rounded-full border-[16px] border-surface-container relative">
                <!-- This is a visual approximation using pure CSS borders -->
                <div class="absolute inset-[-16px] rounded-full border-[16px] border-primary border-r-transparent border-b-transparent transform rotate-45"></div>
                <div class="absolute inset-[-16px] rounded-full border-[16px] border-secondary border-t-transparent border-l-transparent border-b-transparent transform rotate-[135deg]"></div>
                <div class="absolute inset-[-16px] rounded-full border-[16px] border-tertiary border-t-transparent border-l-transparent border-r-transparent transform -rotate-45"></div>
                <div class="absolute inset-0 flex items-center justify-center flex-col">
                    <span class="font-h2 text-h2 text-on-surface">12k</span>
                    <span class="font-caption text-caption text-on-surface-variant">Total</span>
                </div>
            </div>
            <div class="w-full mt-8 space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-primary"></div>
                        <span class="font-caption text-caption text-on-surface-variant">Retailers</span>
                    </div>
                    <span class="font-label-sm text-label-sm font-semibold">45%</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-secondary"></div>
                        <span class="font-caption text-caption text-on-surface-variant">Distributors</span>
                    </div>
                    <span class="font-label-sm text-label-sm font-semibold">30%</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-tertiary"></div>
                        <span class="font-caption text-caption text-on-surface-variant">Farmers</span>
                    </div>
                    <span class="font-label-sm text-label-sm font-semibold">25%</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
