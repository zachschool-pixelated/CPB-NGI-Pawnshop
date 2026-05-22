<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Item: ') }} {{ $item->name }}
            </h2>
            <div class="space-x-2">
                <a href="{{ route('items.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                    {{ __('Back') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Status & Item Hero Banner --}}
            @php
                $statusGradient = match($item->effective_status) {
                    'for_sale' => 'linear-gradient(to right, #22c55e, #059669)',
                    'stored' => 'linear-gradient(to right, #3b82f6, #4f46e5)',
                    'past_maturity' => 'linear-gradient(to right, #f59e0b, #d97706)',
                    'for_auction' => 'linear-gradient(to right, #ef4444, #b91c1c)',
                    'sold' => 'linear-gradient(to right, #6b7280, #4b5563)',
                    'renewed' => 'linear-gradient(to right, #facc15, #ca8a04)',
                    'redeemed' => 'linear-gradient(to right, #a855f7, #7e22ce)',
                    default => 'linear-gradient(to right, #6b7280, #4b5563)'
                };
                
                $statusLabel = match($item->effective_status) {
                    'for_sale' => 'For Sale',
                    'stored' => 'Pawned',
                    'past_maturity' => 'Past Maturity',
                    'for_auction' => 'For Auction',
                    'sold' => 'Sold',
                    'renewed' => 'Renewed',
                    'redeemed' => 'Redeemed',
                    default => ucfirst($item->effective_status)
                };
            @endphp
            <div class="mb-8 rounded-2xl shadow-lg overflow-hidden relative" style="background: {{ $statusGradient }};">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white opacity-10 rounded-full blur-2xl"></div>
                <div class="absolute bottom-0 left-10 -mb-8 w-24 h-24 bg-black opacity-10 rounded-full blur-xl"></div>
                
                <div class="p-8 relative z-10 flex flex-col md:flex-row items-center justify-between text-center md:text-left">
                    <div class="flex-1 md:text-left">
                        <p class="text-white opacity-80 font-medium tracking-wider uppercase text-sm mb-1">Item Code</p>
                        <h1 class="text-4xl font-extrabold text-white tracking-tight">{{ $item->item_code }}</h1>
                    </div>
                    
                    <div class="flex-1 text-center mt-4 md:mt-0">
                        <span class="inline-block px-6 py-2 rounded-full text-white font-bold tracking-widest text-sm backdrop-blur-md shadow-sm uppercase" style="background-color: rgba(255, 255, 255, 0.2); border: 1px solid rgba(255, 255, 255, 0.4);">
                            {{ $statusLabel }}
                        </span>
                    </div>

                    <div class="flex-1 mt-6 md:mt-0 md:text-right text-white opacity-90">
                        @if(in_array($item->effective_status, ['stored', 'past_maturity', 'for_auction']) && $item->latest_transaction)
                            <p class="text-white opacity-80 font-medium tracking-wider uppercase text-sm mb-1">Maturity Date</p>
                            <p class="font-bold text-2xl tracking-tight {{ $item->effective_status === 'past_maturity' ? 'text-red-200 animate-pulse' : 'text-white' }}">
                                {{ \Carbon\Carbon::parse($item->latest_transaction->maturity_date)->format('M d, Y') }}
                            </p>
                            @if($item->effective_status === 'past_maturity' && $item->auction_date)
                                @php
                                    $daysUntilAuction = (int) ceil(now()->floatDiffInDays($item->auction_date, false));
                                    $auctionText = $daysUntilAuction > 0 ? "Auction in {$daysUntilAuction} days" : "Auction due today";
                                @endphp
                                <p class="text-xs text-red-200 mt-1 uppercase tracking-wider font-bold">{{ $auctionText }}</p>
                            @elseif($item->effective_status === 'for_auction')
                                <p class="text-xs text-red-200 mt-1 uppercase tracking-wider font-bold">Ready for auction</p>
                            @endif
                        @else
                            <p class="text-white opacity-80 font-medium tracking-wider uppercase text-sm mb-1">Appraised Value</p>
                            <p class="font-bold text-2xl tracking-tight text-white">
                                ₱{{ number_format($item->appraised_value, 2) }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Item Details -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-md sm:rounded-2xl mb-6 border border-gray-100 dark:border-gray-700">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 p-6 lg:p-8">
                    <!-- Image Showcase Section -->
                    <div class="lg:col-span-1 flex flex-col space-y-4">
                        <div class="relative rounded-2xl overflow-hidden shadow-md bg-gray-100 dark:bg-gray-900 border border-gray-100 dark:border-gray-700 aspect-square lg:aspect-auto lg:h-80">
                            <img src="{{ $item->sample_image }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
                            <!-- Overlay Badge for Category -->
                            <div class="absolute bottom-4 left-4">
                                <span class="bg-black/60 dark:bg-gray-900/60 text-white backdrop-blur-md px-3 py-1 rounded-full text-xs font-semibold tracking-wide border border-white/20">
                                    {{ $item->category->name ?? 'Uncategorized' }}
                                </span>
                            </div>
                        </div>
                        <!-- Storage Status Mini Card -->
                        <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-4 border border-gray-100 dark:border-gray-750 flex items-center space-x-3">
                            <div class="p-2 bg-indigo-50 dark:bg-indigo-900/40 rounded-lg text-indigo-600 dark:text-indigo-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </div>
                            <div>
                                <p class="text-[10px] uppercase font-bold text-gray-400 dark:text-gray-500">Current Storage</p>
                                <p class="text-sm font-bold text-gray-800 dark:text-gray-250">
                                    {{ $item->safe->name ?? 'Unassigned Safe' }}
                                </p>
                                @if($item->location)
                                    <p class="text-xs text-gray-400 dark:text-gray-500">{{ $item->location }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Details Table Section -->
                    <div class="lg:col-span-2 flex flex-col justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 border-b border-gray-100 dark:border-gray-700 pb-3">Item Details</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">Name</p>
                                    <p class="text-lg font-semibold text-gray-900 dark:text-white mt-1">{{ $item->name }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">Condition</p>
                                    <div class="mt-1">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full 
                                            @if($item->condition === 'excellent') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                            @elseif($item->condition === 'good') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                            @elseif($item->condition === 'fair') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                            @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                            @endif">
                                            {{ ucfirst($item->condition) }}
                                        </span>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">Appraised Value</p>
                                    <p class="text-lg font-bold text-green-600 dark:text-green-400 mt-1">₱{{ number_format($item->appraised_value, 2) }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">Effective Status</p>
                                    <div class="mt-1">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full 
                                            @if($item->effective_status === 'for_sale') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                            @elseif($item->effective_status === 'stored') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                            @elseif($item->effective_status === 'sold') bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200
                                            @elseif($item->effective_status === 'renewed' || $item->effective_status === 'past_maturity') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                            @elseif($item->effective_status === 'redeemed') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200
                                            @elseif($item->effective_status === 'for_auction') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                            @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200
                                            @endif">
                                            {{ $statusLabel }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            @if($item->description)
                                <div class="mt-6 border-t border-gray-100 dark:border-gray-700 pt-4">
                                    <p class="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">Description</p>
                                    <p class="text-sm text-gray-700 dark:text-gray-300 mt-1 leading-relaxed">{{ $item->description }}</p>
                                </div>
                            @endif

                            @if($item->notes)
                                <div class="mt-6 border-t border-gray-100 dark:border-gray-700 pt-4">
                                    <p class="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">Notes</p>
                                    <p class="text-sm text-gray-700 dark:text-gray-300 mt-1 leading-relaxed">{{ $item->notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Item Transactions History -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-6">Transactions History</h3>

                    @if($item->transactions->count())
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ticket #</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Customer</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($item->transactions as $txn)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">{{ $txn->pawn_ticket_number }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $txn->customer->full_name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $txn->type_label }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $txn->transaction_date->format('M d, Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    @if($txn->status === 'active') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                    @elseif($txn->status === 'redeemed') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                                    @elseif($txn->status === 'renewed') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                                    @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                                    @endif">
                                                    {{ $txn->status_label }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <a href="{{ route('transactions.show', $txn) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400 text-center py-6">This item has not been part of any transactions yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
