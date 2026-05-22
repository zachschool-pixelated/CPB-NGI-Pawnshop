<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Reports Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Active Loans -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6 border-l-4 border-blue-500">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Active Loans</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-white text-right">{{ number_format($totalActiveLoansCount) }} <span class="text-lg font-normal text-gray-500">/ ₱{{ number_format($totalActiveLoansAmount, 2) }}</span></div>
                </div>

                <!-- Loans Released Today -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6 border-l-4 border-purple-500">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Loans Released Today</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-white text-right">₱{{ number_format($loansReleasedToday, 2) }}</div>
                </div>

                <!-- Total Collections Today -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6 border-l-4 border-green-500">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Collections Today</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-white text-right">₱{{ number_format($totalCollectionsToday, 2) }}</div>
                </div>

                <!-- Interest Income -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6 border-l-4 border-yellow-500">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Interest Income</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-white text-right">₱{{ number_format($interestIncome, 2) }}</div>
                </div>
            </div>

            <!-- Report Links -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl overflow-hidden">
                <div class="p-6 text-gray-900 dark:text-gray-100 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-bold">Available Reports</h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
                    <!-- Collection Summary Card -->
                    <a href="{{ route('reports.summary') }}" class="group bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm hover:shadow-md border border-gray-100 dark:border-gray-700 hover:border-indigo-500 dark:hover:border-indigo-500 transition duration-300 flex flex-col justify-between">
                        <div class="flex items-start space-x-4">
                            <div class="p-3 rounded-xl bg-indigo-50 text-indigo-600 dark:bg-indigo-900/50 dark:text-indigo-400 group-hover:scale-110 transition duration-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-base font-bold text-gray-800 dark:text-gray-200 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition">Collection Summary</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">View overall collected principal, interest, service charge, and penalties.</p>
                            </div>
                        </div>
                        <div class="mt-4 text-xs font-semibold text-indigo-600 dark:text-indigo-400 flex items-center group-hover:translate-x-1 transition-transform">
                            Generate Report <span class="ml-1">&rarr;</span>
                        </div>
                    </a>

                    <!-- Daily Transaction Card -->
                    <a href="{{ route('reports.transactions') }}" class="group bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm hover:shadow-md border border-gray-100 dark:border-gray-700 hover:border-blue-500 dark:hover:border-blue-500 transition duration-300 flex flex-col justify-between">
                        <div class="flex items-start space-x-4">
                            <div class="p-3 rounded-xl bg-blue-50 text-blue-600 dark:bg-blue-900/50 dark:text-blue-400 group-hover:scale-110 transition duration-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-base font-bold text-gray-800 dark:text-gray-200 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition">Daily Transactions</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">View all pawn transactions, loans released, and statuses.</p>
                            </div>
                        </div>
                        <div class="mt-4 text-xs font-semibold text-blue-600 dark:text-blue-400 flex items-center group-hover:translate-x-1 transition-transform">
                            Generate Report <span class="ml-1">&rarr;</span>
                        </div>
                    </a>

                    <!-- Payments Report Card -->
                    <a href="{{ route('reports.payments') }}" class="group bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm hover:shadow-md border border-gray-100 dark:border-gray-700 hover:border-green-500 dark:hover:border-green-500 transition duration-300 flex flex-col justify-between">
                        <div class="flex items-start space-x-4">
                            <div class="p-3 rounded-xl bg-green-50 text-green-600 dark:bg-green-900/50 dark:text-green-400 group-hover:scale-110 transition duration-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-base font-bold text-gray-800 dark:text-gray-200 group-hover:text-green-600 dark:group-hover:text-green-400 transition">Payments Report</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Track all collected payments (interest, redemptions).</p>
                            </div>
                        </div>
                        <div class="mt-4 text-xs font-semibold text-green-600 dark:text-green-400 flex items-center group-hover:translate-x-1 transition-transform">
                            Generate Report <span class="ml-1">&rarr;</span>
                        </div>
                    </a>

                    <!-- POS Sales Report Card -->
                    <a href="{{ route('reports.sales') }}" class="group bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm hover:shadow-md border border-gray-100 dark:border-gray-700 hover:border-amber-500 dark:hover:border-amber-500 transition duration-300 flex flex-col justify-between">
                        <div class="flex items-start space-x-4">
                            <div class="p-3 rounded-xl bg-amber-50 text-amber-600 dark:bg-amber-900/50 dark:text-amber-400 group-hover:scale-110 transition duration-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-base font-bold text-gray-800 dark:text-gray-200 group-hover:text-amber-600 dark:group-hover:text-amber-400 transition">POS Sales Report</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Track all retail/POS sales generated by cashiers and managers.</p>
                            </div>
                        </div>
                        <div class="mt-4 text-xs font-semibold text-amber-600 dark:text-amber-400 flex items-center group-hover:translate-x-1 transition-transform">
                            Generate Report <span class="ml-1">&rarr;</span>
                        </div>
                    </a>

                    <!-- Inventory of Pawned Items Card -->
                    <a href="{{ route('reports.inventory') }}" class="group bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm hover:shadow-md border border-gray-100 dark:border-gray-700 hover:border-purple-500 dark:hover:border-purple-500 transition duration-300 flex flex-col justify-between">
                        <div class="flex items-start space-x-4">
                            <div class="p-3 rounded-xl bg-purple-50 text-purple-600 dark:bg-purple-900/50 dark:text-purple-400 group-hover:scale-110 transition duration-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-base font-bold text-gray-800 dark:text-gray-200 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition">Pawned Items Inventory</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Track pawned items movement (Beginning, Added, Minus, Ending).</p>
                            </div>
                        </div>
                        <div class="mt-4 text-xs font-semibold text-purple-600 dark:text-purple-400 flex items-center group-hover:translate-x-1 transition-transform">
                            Generate Report <span class="ml-1">&rarr;</span>
                        </div>
                    </a>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
