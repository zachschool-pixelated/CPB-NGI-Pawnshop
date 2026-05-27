<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ __('Pawn Management') }}</h2>
            @if(!auth()->user()->isCashier())
                <a href="{{ route('pawn.wizard') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">+ New Pawn</a>
            @endif
        </div>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Search Bar --}}
            <div class="mb-6">
                <form method="GET" action="{{ route('transactions.index') }}" onsubmit="event.preventDefault();" class="flex gap-3 items-center">
                    <div class="relative flex-1 max-w-md">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" name="search" id="search-input" value="{{ request('search') }}" placeholder="Search ticket #, customer, or item name..." autocomplete="off"
                            class="block w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500 transition">
                    </div>
                </form>
            </div>

            <div id="transactions-container">
                @if ($transactions->count())
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="overflow-x-auto" style="min-height: 220px;">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Ticket #</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Customer</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Item(s)</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Maturity</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($transactions as $txn)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $txn->pawn_ticket_number }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $txn->customer->full_name }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                            @foreach($txn->items as $txnItem)
                                                <span class="block">{{ $txnItem->item->name ?? 'N/A' }}</span>
                                            @endforeach
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $txn->type_label }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ number_format($txn->loan_amount, 2) }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $txn->maturity_date->format('M d, Y') }}</td>
                                        <td class="px-6 py-4 text-sm">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                @if($txn->status==='active') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                @elseif($txn->status==='redeemed') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                                @elseif($txn->status==='renewed') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                                @elseif($txn->status==='forfeited') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                                @elseif($txn->status==='sold') bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200
                                                @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 @endif">{{ $txn->status_label }}</span>
                                        </td>
                                         <td class="px-6 py-4 text-sm whitespace-nowrap text-right">
                                            <div class="inline-block text-left relative" :class="open ? 'z-50' : ''" x-data="{ open: false }">
                                                <button @click="open = !open" type="button" class="flex items-center text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 focus:outline-none p-1 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition ml-auto">
                                                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                                    </svg>
                                                </button>
                                                <div x-show="open" @click.outside="open = false"
                                                     x-transition:enter="transition ease-out duration-100" 
                                                     x-transition:enter-start="transform opacity-0 scale-95" 
                                                     x-transition:enter-end="transform opacity-100 scale-100" 
                                                     x-transition:leave="transition ease-in duration-75" 
                                                     x-transition:leave-start="transform opacity-100 scale-100" 
                                                     x-transition:leave-end="transform opacity-0 scale-95" 
                                                     class="origin-top-right absolute right-0 top-full mt-1 w-32 rounded-md shadow-lg bg-white dark:bg-gray-700 ring-1 ring-black ring-opacity-5 z-50 focus:outline-none"
                                                     style="display: none;">
                                                    <div class="py-1 text-left">
                                                        <a href="{{ route('transactions.show', $txn) }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">View</a>
                                                        @if($txn->status === 'active' && !auth()->user()->isCashier())
                                                            <a href="{{ route('transactions.edit', $txn) }}" class="block px-4 py-2 text-sm text-green-600 dark:text-green-400 hover:bg-gray-100 dark:hover:bg-gray-600">Edit</a>
                                                        @endif
                                                        @if($txn->status !== 'voided' && $txn->status !== 'redeemed' && $txn->status !== 'forfeited' && $txn->status !== 'sold' && !auth()->user()->isCashier())
                                                            <button type="button" 
                                                                    class="w-full text-left block px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-600"
                                                                    @click="$dispatch('open-void-modal', { 
                                                                        url: '{{ route('transactions.request-void', $txn) }}', 
                                                                        ticket: '{{ $txn->pawn_ticket_number }}',
                                                                        isTeller: {{ auth()->user()->isTeller() ? 'true' : 'false' }}
                                                                    }); open = false;">
                                                                Void
                                                            </button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                         </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="px-6 py-4">{{ $transactions->links() }}</div>
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        @if(request('search'))
                            <p class="text-gray-500 dark:text-gray-400">No transactions found for "{{ request('search') }}".</p>
                            <a href="{{ route('transactions.index') }}" class="inline-block mt-4 px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">Clear Search</a>
                        @else
                            <p class="text-gray-500 dark:text-gray-400">No transactions yet.</p>
                            @if(!auth()->user()->isCashier())
                                <a href="{{ route('pawn.wizard') }}" class="inline-block mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Create First Pawn</a>
                            @endif
                        @endif
                    </div>
                </div>
            @endif
            </div>
        </div>
    </div>

    <!-- Void Transaction Modal -->
    <div x-data="{ 
            voidUrl: '', 
            voidTicket: '', 
            isTeller: false 
        }" 
        @open-void-modal.window="
            voidUrl = $event.detail.url; 
            voidTicket = $event.detail.ticket; 
            isTeller = $event.detail.isTeller;
            $dispatch('open-modal', 'void-transaction-modal');
        ">
        <x-modal name="void-transaction-modal" focusable>
            <form method="POST" x-bind:action="voidUrl" class="p-6">
                @csrf
                <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100">
                    Void Transaction: <span x-text="voidTicket" class="text-yellow-600 dark:text-yellow-400"></span>
                </h2>
                
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400" x-show="isTeller">
                    Please provide a reason for this void request. It will be sent to a manager for review.
                </p>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400" x-show="!isTeller">
                    Are you sure you want to void this transaction? All associated items will also be voided. Please provide a reason.
                </p>

                <div class="mt-6">
                    <x-input-label for="approval_notes" value="Reason for Voiding" class="sr-only" />
                    <textarea
                        id="approval_notes"
                        name="approval_notes"
                        rows="3"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-yellow-500 focus:ring-yellow-500 rounded-md shadow-sm"
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
                        <span x-show="!isTeller">Void Immediately</span>
                    </x-danger-button>
                </div>
            </form>
        </x-modal>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('search-input');
            const container = document.getElementById('transactions-container');
            if (!searchInput || !container) return;

            let debounceTimeout;
            searchInput.addEventListener('input', () => {
                clearTimeout(debounceTimeout);
                const query = searchInput.value;
                
                debounceTimeout = setTimeout(() => {
                    const url = new URL(window.location.href);
                    if (query) {
                        url.searchParams.set('search', query);
                    } else {
                        url.searchParams.delete('search');
                    }
                    
                    // Reset to first page when searching
                    url.searchParams.delete('page');
                    
                    // Update browser URL without reloading
                    window.history.pushState({}, '', url);

                    // Fetch new content via AJAX
                    fetch(url)
                        .then(res => res.text())
                        .then(html => {
                            const parser = new DOMParser();
                            const doc = parser.parseFromString(html, 'text/html');
                            const newContainer = doc.getElementById('transactions-container');
                            if (newContainer) {
                                container.innerHTML = newContainer.innerHTML;
                                if (window.Alpine) {
                                    window.Alpine.initTree(container);
                                }
                            }
                        })
                        .catch(err => console.error('Search failed:', err));
                }, 300); // 300ms debounce
            });

            // Handle AJAX pagination and reset link clicks
            container.addEventListener('click', (e) => {
                const link = e.target.closest('a');
                if (link && link.href) {
                    const isPaginationLink = link.closest('nav') !== null;
                    const isClearSearchLink = link.textContent.trim() === 'Clear Search';
                    
                    if (isPaginationLink || isClearSearchLink) {
                        e.preventDefault();
                        const url = new URL(link.href);
                        window.history.pushState({}, '', url);
                        
                        // Clear search input if returning to base page
                        if (!url.searchParams.has('search')) {
                            searchInput.value = '';
                        } else {
                            searchInput.value = url.searchParams.get('search');
                        }

                        fetch(url)
                            .then(res => res.text())
                            .then(html => {
                                const parser = new DOMParser();
                                const doc = parser.parseFromString(html, 'text/html');
                                const newContainer = doc.getElementById('transactions-container');
                                if (newContainer) {
                                    container.innerHTML = newContainer.innerHTML;
                                    if (window.Alpine) {
                                        window.Alpine.initTree(container);
                                    }
                                    container.scrollIntoView({ behavior: 'smooth' });
                                }
                            })
                            .catch(err => console.error('AJAX load failed:', err));
                    }
                }
            });
        });
    </script>
</x-app-layout>
