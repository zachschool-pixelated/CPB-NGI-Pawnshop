<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Dashboard') }}
            </h2>
            <div class="flex items-center space-x-3">
                <a href="{{ route('transactions.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    New Pawn
                </a>
                <a href="{{ route('payments.create') }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 focus:bg-emerald-700 active:bg-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    Payment
                </a>
            </div>
        </div>
    </x-slot>

    <!-- Load Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- Financial Metrics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Today's Collections -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden relative group">
                    <div class="absolute inset-0 bg-gradient-to-r from-emerald-500 to-teal-400 opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Today's Collections</div>
                            <div class="p-2 bg-emerald-50 dark:bg-emerald-900/30 rounded-lg">
                                <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-baseline">
                            <div class="text-3xl font-bold text-gray-900 dark:text-white">₱{{ number_format($todaysCollections, 2) }}</div>
                        </div>
                    </div>
                    <div class="h-1 w-full bg-emerald-500"></div>
                </div>

                <!-- Monthly Profit -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden relative group">
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-500 to-indigo-400 opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Monthly Profit</div>
                            <div class="p-2 bg-blue-50 dark:bg-blue-900/30 rounded-lg">
                                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-baseline">
                            <div class="text-3xl font-bold text-gray-900 dark:text-white">₱{{ number_format($monthlyProfit, 2) }}</div>
                        </div>
                    </div>
                    <div class="h-1 w-full bg-blue-500"></div>
                </div>

                <!-- Total Loan Amount (Active) -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden relative group">
                    <div class="absolute inset-0 bg-gradient-to-r from-amber-500 to-orange-400 opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Active Loans Out</div>
                            <div class="p-2 bg-amber-50 dark:bg-amber-900/30 rounded-lg">
                                <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-baseline">
                            <div class="text-3xl font-bold text-gray-900 dark:text-white">₱{{ number_format($totalLoanAmount, 2) }}</div>
                        </div>
                    </div>
                    <div class="h-1 w-full bg-amber-500"></div>
                </div>

                <!-- Active Transactions -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden relative group">
                    <div class="absolute inset-0 bg-gradient-to-r from-purple-500 to-pink-400 opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Active Tickets</div>
                            <div class="p-2 bg-purple-50 dark:bg-purple-900/30 rounded-lg">
                                <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-baseline">
                            <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $activeCount }}</div>
                        </div>
                    </div>
                    <div class="h-1 w-full bg-purple-500"></div>
                </div>
            </div>

            <!-- Main Layout Grid -->
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
                
                <!-- Left Column (Chart + Table) -->
                <div class="xl:col-span-2 space-y-8">
                    
                    <!-- Chart -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Cash Flow Analytics</h3>
                            <span class="text-sm text-gray-500 bg-gray-100 dark:bg-gray-700 px-3 py-1 rounded-full">Last 14 Days</span>
                        </div>
                        <div class="relative w-full" style="height: 320px;">
                            <canvas id="cashFlowChart"></canvas>
                        </div>
                    </div>

                    <!-- Recent Transactions -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Recent Transactions</h3>
                            <a href="{{ route('transactions.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">View All &rarr;</a>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700/50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ticket #</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Customer</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700">
                                    @forelse($recentTransactions as $txn)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-indigo-600">
                                            <a href="{{ route('transactions.show', $txn) }}">{{ $txn->pawn_ticket_number }}</a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                            {{ $txn->customer->full_name ?? 'Unknown' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                            ₱{{ number_format($txn->loan_amount, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full @if($txn->status==='active') bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400 @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 @endif">
                                                {{ $txn->status_label }}
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="4" class="px-6 py-8 text-center text-sm text-gray-500">No transactions found.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

                <!-- Right Column (Alerts, Maturities, Vaults) -->
                <div class="space-y-8">
                    
                    <!-- Pending Approvals Alert -->
                    @if($pendingApprovals->count() > 0)
                    <div class="bg-gradient-to-br from-red-500 to-rose-600 rounded-xl shadow-lg overflow-hidden text-white">
                        <div class="p-6">
                            <div class="flex items-center mb-2">
                                <div class="p-2 bg-white/20 rounded-lg mr-3">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                </div>
                                <h3 class="text-xl font-bold">Action Required</h3>
                            </div>
                            <p class="text-red-50 mb-4">{{ $pendingApprovals->count() }} pending request(s) require manager review.</p>
                            <a href="{{ route('approvals.index') }}" class="inline-block bg-white text-red-600 font-bold px-4 py-2 rounded-lg text-sm hover:bg-red-50 transition-colors shadow-sm">Review Now</a>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Overdue & Upcoming Maturities -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center">
                                <svg class="w-5 h-5 mr-2 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Maturities
                            </h3>
                        </div>
                        <div class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse($maturities as $mat)
                            <div class="p-5 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <a href="{{ route('transactions.show', $mat) }}" class="text-sm font-bold text-indigo-600 hover:underline">{{ $mat->pawn_ticket_number }}</a>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $mat->customer->full_name ?? 'Unknown' }}</p>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-sm font-black @if($mat->maturity_date < now()) text-red-600 dark:text-red-400 @else text-amber-600 dark:text-amber-400 @endif">
                                            {{ $mat->maturity_date ? $mat->maturity_date->format('M d') : 'N/A' }}
                                        </div>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold mt-1 @if($mat->maturity_date < now()) bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 @else bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400 @endif">
                                            @if($mat->maturity_date < now()) Overdue @else Upcoming @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="p-6 text-center">
                                <svg class="w-8 h-8 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <p class="text-gray-500 font-medium text-sm">All caught up!</p>
                                <p class="text-xs text-gray-400">No upcoming maturities.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Vault / Safe Capacity -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center">
                                <svg class="w-5 h-5 mr-2 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                Vault Storage
                            </h3>
                        </div>
                        <div class="p-6 space-y-6">
                            @forelse($safes as $safe)
                            @php
                                $capacity = $safe->items_capacity > 0 ? min(100, round(($safe->current_items_count / $safe->items_capacity) * 100)) : 0;
                                $color = $capacity > 90 ? 'bg-red-500' : ($capacity > 75 ? 'bg-amber-500' : 'bg-emerald-500');
                                $textColor = $capacity > 90 ? 'text-red-600' : ($capacity > 75 ? 'text-amber-600' : 'text-emerald-600');
                            @endphp
                            <div>
                                <div class="flex justify-between items-end mb-2">
                                    <span class="font-bold text-gray-800 dark:text-gray-200">{{ $safe->name }}</span>
                                    <span class="text-sm font-semibold {{ $textColor }}">{{ $capacity }}%</span>
                                </div>
                                <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-3 overflow-hidden">
                                    <div class="{{ $color }} h-3 rounded-full transition-all duration-500" style="width: {{ $capacity }}%"></div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1.5 text-right">{{ $safe->current_items_count }} of {{ $safe->items_capacity > 0 ? $safe->items_capacity : '∞' }} items</p>
                            </div>
                            @empty
                            <div class="text-sm text-gray-500 text-center py-4">No safes configured.</div>
                            @endforelse
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('cashFlowChart').getContext('2d');
            
            const isDarkMode = document.documentElement.classList.contains('dark');
            const textColor = isDarkMode ? '#9ca3af' : '#6b7280';
            const gridColor = isDarkMode ? '#374151' : '#f3f4f6';

            // Premium gradients for chart
            const gradientRed = ctx.createLinearGradient(0, 0, 0, 400);
            gradientRed.addColorStop(0, 'rgba(239, 68, 68, 0.8)');
            gradientRed.addColorStop(1, 'rgba(239, 68, 68, 0.2)');

            const gradientGreen = ctx.createLinearGradient(0, 0, 0, 400);
            gradientGreen.addColorStop(0, 'rgba(16, 185, 129, 0.8)');
            gradientGreen.addColorStop(1, 'rgba(16, 185, 129, 0.2)');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($dates) !!},
                    datasets: [
                        {
                            label: 'Loans Released',
                            data: {!! json_encode($loanData) !!},
                            backgroundColor: gradientRed,
                            hoverBackgroundColor: 'rgba(239, 68, 68, 1)',
                            borderRadius: 6,
                            barPercentage: 0.6,
                            categoryPercentage: 0.8
                        },
                        {
                            label: 'Collections Received',
                            data: {!! json_encode($collectionData) !!},
                            backgroundColor: gradientGreen,
                            hoverBackgroundColor: 'rgba(16, 185, 129, 1)',
                            borderRadius: 6,
                            barPercentage: 0.6,
                            categoryPercentage: 0.8
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            align: 'end',
                            labels: { 
                                color: textColor,
                                usePointStyle: true,
                                boxWidth: 8,
                                font: { weight: 'bold', family: "'Figtree', sans-serif" }
                            }
                        },
                        tooltip: {
                            backgroundColor: isDarkMode ? '#1f2937' : '#ffffff',
                            titleColor: isDarkMode ? '#ffffff' : '#111827',
                            bodyColor: isDarkMode ? '#d1d5db' : '#4b5563',
                            borderColor: isDarkMode ? '#374151' : '#e5e7eb',
                            borderWidth: 1,
                            padding: 12,
                            boxPadding: 6,
                            usePointStyle: true,
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { 
                                color: gridColor,
                                drawBorder: false,
                            },
                            ticks: { 
                                color: textColor,
                                font: { family: "'Figtree', sans-serif" },
                                callback: function(value) { 
                                    return '₱' + (value >= 1000 ? (value/1000) + 'k' : value);
                                }
                            },
                            border: { display: false }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { 
                                color: textColor,
                                font: { family: "'Figtree', sans-serif" }
                            },
                            border: { display: false }
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>