<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center flex-wrap gap-4">
            <div class="flex items-center space-x-4">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Inventory') }}
                </h2>
            </div>

                <form method="GET" action="{{ route('items.index') }}" class="flex flex-wrap gap-3 items-center">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search item name or code..." class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg w-64 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 text-sm">
                    <select name="category" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <select name="status" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <option value="">All Status</option>
                        <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                        <option value="loaned" {{ request('status') == 'loaned' ? 'selected' : '' }}>Pawned</option>
                    </select>
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition text-sm font-medium">Filter</button>
                    <a href="{{ route('items.index') }}" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition text-sm font-medium">Reset</a>
                </form>
            </div>
        </x-slot>

        <div class="py-12" x-data="{ viewMode: localStorage.getItem('itemsViewMode') || 'list' }" x-init="$watch('viewMode', val => localStorage.setItem('itemsViewMode', val))">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                
                <!-- View Toggle Buttons Inside Main Content -->
                <div class="flex justify-end mb-4">
                    <div class="bg-white dark:bg-gray-800 p-1 rounded-lg border border-gray-200 dark:border-gray-700 flex space-x-1 shadow-sm">
                        <button @click="viewMode = 'list'" :class="viewMode === 'list' ? 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200'" class="p-1.5 rounded-md transition flex items-center justify-center" title="List View">
                            <svg width="20" height="20" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                        </button>
                        <button @click="viewMode = 'grid'" :class="viewMode === 'grid' ? 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200'" class="p-1.5 rounded-md transition flex items-center justify-center" title="Grid View">
                            <svg width="20" height="20" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                        </button>
                    </div>
                </div>

                @if ($items->count())
                    
                    <!-- LIST VIEW -->
                    <div x-show="viewMode === 'list'" style="display: none;" x-transition.opacity.duration.300ms class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Item Code</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Category</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Appraised Value</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($items as $item)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-indigo-600 dark:text-indigo-400">{{ $item->item_code }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 font-medium">{{ $item->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $item->category->name ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-gray-100">₱{{ number_format($item->appraised_value, 2) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    @if($item->effective_status === 'for_sale') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                    @elseif($item->effective_status === 'stored') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                                    @elseif($item->effective_status === 'sold') bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200
                                                    @elseif($item->effective_status === 'renewed') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                                    @elseif($item->effective_status === 'past_maturity') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                                    @elseif($item->effective_status === 'redeemed') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200
                                                    @elseif($item->effective_status === 'for_auction') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                                    @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200
                                                    @endif">
                                                    @if($item->effective_status === 'for_sale') For Sale
                                                    @elseif($item->effective_status === 'stored') Pawned
                                                    @elseif($item->effective_status === 'sold') Sold
                                                    @elseif($item->effective_status === 'renewed') Renewed
                                                    @elseif($item->effective_status === 'past_maturity') Past Maturity
                                                    @elseif($item->effective_status === 'redeemed') Redeemed
                                                    @elseif($item->effective_status === 'for_auction') For Auction
                                                    @else {{ ucfirst($item->effective_status) }}
                                                    @endif
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm space-x-3">
                                                <a href="{{ route('items.show', $item) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 font-medium">View</a>
                                                @if(in_array($item->effective_status, ['for_auction', 'redeemed', 'sold']) && $item->item_status !== 'voided')
                                                    <button x-data type="button" 
                                                            class="text-red-600 hover:text-red-900 dark:text-red-400 font-medium"
                                                            @click="$dispatch('open-void-modal', { 
                                                                url: '{{ route('items.request-void', $item) }}', 
                                                                code: '{{ $item->item_code }}',
                                                                isTeller: {{ auth()->user()->isTeller() ? 'true' : 'false' }}
                                                            })">
                                                        Remove
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>                    <!-- GRID VIEW -->
                    <div x-show="viewMode === 'grid'" style="display: none;" x-transition.opacity.duration.300ms class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach($items as $item)
                            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-md rounded-2xl border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-all duration-300 flex flex-col h-full group">
                                <!-- Card Image -->
                                <div class="relative h-44 w-full overflow-hidden bg-gray-100 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
                                    <img src="{{ $item->sample_image }}" alt="{{ $item->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                    
                                    <!-- Status Overlay -->
                                    <div class="absolute top-3 right-3">
                                        <span class="px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-wider rounded-full shadow-md bg-white/95 dark:bg-gray-800/95 border border-gray-200 dark:border-gray-700
                                            @if($item->effective_status === 'for_sale') text-green-700 dark:text-green-300 border-green-200/50 dark:border-green-800/50
                                            @elseif($item->effective_status === 'stored') text-blue-700 dark:text-blue-300 border-blue-200/50 dark:border-blue-800/50
                                            @elseif($item->effective_status === 'sold') text-gray-700 dark:text-gray-300 border-gray-200/50 dark:border-gray-700/50
                                            @elseif($item->effective_status === 'renewed' || $item->effective_status === 'past_maturity') text-yellow-700 dark:text-yellow-300 border-yellow-200/50 dark:border-yellow-800/50
                                            @elseif($item->effective_status === 'redeemed') text-purple-700 dark:text-purple-300 border-purple-200/50 dark:border-purple-800/50
                                            @elseif($item->effective_status === 'for_auction') text-red-700 dark:text-red-300 border-red-200/50 dark:border-red-800/50
                                            @else text-gray-750 dark:text-gray-300 border-gray-200/50
                                            @endif">
                                            @if($item->effective_status === 'for_sale') For Sale
                                            @elseif($item->effective_status === 'stored') Pawned
                                            @elseif($item->effective_status === 'sold') Sold
                                            @elseif($item->effective_status === 'renewed') Renewed
                                            @elseif($item->effective_status === 'past_maturity') Past Maturity
                                            @elseif($item->effective_status === 'redeemed') Redeemed
                                            @elseif($item->effective_status === 'for_auction') For Auction
                                            @else {{ ucfirst($item->effective_status) }}
                                            @endif
                                        </span>
                                    </div>
                                    
                                    <!-- Code Overlay -->
                                    <div class="absolute bottom-3 left-3">
                                        <span class="px-2 py-0.5 text-[10px] font-mono font-bold bg-gray-900/70 text-white backdrop-blur-md rounded border border-white/10 tracking-wide">
                                            {{ $item->item_code }}
                                        </span>
                                    </div>
                                </div>
                                
                                <!-- Card Body -->
                                <div class="p-5 flex-grow flex flex-col justify-between">
                                    <div class="space-y-2">
                                        <!-- Category -->
                                        <div class="flex items-center text-[11px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                                            <svg width="14" height="14" class="w-3.5 h-3.5 mr-1 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                            {{ $item->category->name ?? 'Uncategorized' }}
                                        </div>
                                        
                                        <!-- Item Title (Fixed Height) -->
                                        <h3 class="text-base font-bold text-gray-800 dark:text-gray-200 leading-snug group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors line-clamp-2 h-12" title="{{ $item->name }}">
                                            {{ $item->name }}
                                        </h3>
                                    </div>
                                    
                                    <!-- Value Section -->
                                    <div class="pt-3 border-t border-gray-100 dark:border-gray-700/60 mt-4">
                                        <p class="text-[10px] font-bold text-gray-450 dark:text-gray-500 uppercase tracking-wider">Appraised Value</p>
                                        <p class="text-lg font-black text-green-600 dark:text-green-400">₱{{ number_format($item->appraised_value, 2) }}</p>
                                    </div>
                                </div>
                                
                                <!-- Card Footer -->
                                <div class="bg-gray-50 dark:bg-gray-800/40 border-t border-gray-100 dark:border-gray-700 px-5 py-3.5 flex justify-between items-center rounded-b-2xl">
                                    <a href="{{ route('items.show', $item) }}" class="text-xs font-bold text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition flex items-center gap-1">
                                        View Details 
                                        <svg width="14" height="14" class="w-3.5 h-3.5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                    </a>
                                    @if(in_array($item->effective_status, ['for_auction', 'redeemed', 'sold']) && $item->item_status !== 'voided')
                                        <button x-data type="button" 
                                                class="p-1.5 text-gray-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition"
                                                title="Remove Item"
                                                @click="$dispatch('open-void-modal', { 
                                                    url: '{{ route('items.request-void', $item) }}', 
                                                    code: '{{ $item->item_code }}',
                                                    isTeller: {{ auth()->user()->isTeller() ? 'true' : 'false' }}
                                                })">
                                            <svg width="16" height="16" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6 bg-white dark:bg-gray-800 shadow-sm rounded-lg px-6 py-4">
                        {{ $items->links() }}
                    </div>
                @else
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-10 text-center flex flex-col items-center justify-center">
                            <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-1">No items found</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Items will appear here when a new pawn transaction is created or matching your search filters.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

    <!-- Void Item Modal -->
    <div x-data="{ 
            voidUrl: '', 
            voidCode: '', 
            isTeller: false 
        }" 
        @open-void-modal.window="
            voidUrl = $event.detail.url; 
            voidCode = $event.detail.code; 
            isTeller = $event.detail.isTeller;
            $dispatch('open-modal', 'void-item-modal');
        ">
        <x-modal name="void-item-modal" focusable>
            <form method="POST" x-bind:action="voidUrl" class="p-6">
                @csrf
                <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100">
                    Remove: <span x-text="voidCode" class="text-red-600 dark:text-red-400"></span>
                </h2>
                
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400" x-show="isTeller">
                    Please provide a reason for this removal request. It will be sent to a manager for review.
                </p>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400" x-show="!isTeller">
                    Are you sure you want to remove this item to inventory immediately? Please provide a reason.
                </p>

                <div class="mt-6">
                    <x-input-label for="approval_notes" value="Reason for Removal" class="sr-only" />
                    <textarea
                        id="approval_notes"
                        name="approval_notes"
                        rows="3"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm"
                        placeholder="Enter your reason here..."
                        required
                    ></textarea>
                </div>

                <div class="mt-6 flex justify-end">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        Cancel
                    </x-secondary-button>

                    <x-danger-button class="ms-3">
                        <span x-show="isTeller">Submit Request</span>
                        <span x-show="!isTeller">Remove</span>
                    </x-danger-button>
                </div>
            </form>
        </x-modal>
    </div>
</x-app-layout>
