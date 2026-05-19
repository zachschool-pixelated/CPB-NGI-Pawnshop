<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-r border-gray-100 dark:border-gray-700 w-72 flex-shrink-0 flex flex-col h-full z-20 shadow-sm transition-all duration-300">
    <!-- Sidebar Header -->
    <div class="h-20 flex items-center px-6 border-b border-gray-100 dark:border-gray-700 flex-shrink-0">
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 w-full">
            <img src="{{ asset('cpbngi_logo.png') }}" alt="CPB-NGI Logo" class="h-10 w-auto object-contain flex-shrink-0">
            <span class="text-xl font-bold text-gray-800 dark:text-gray-200 truncate">CPB-NGI Pawnshop</span>
        </a>
    </div>

    <!-- Navigation Links -->
    <div class="flex-1 overflow-y-auto py-4">
        <div class="space-y-1">
            <!-- Dashboard -->
            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                {{ __('Dashboard') }}
            </x-nav-link>

            <!-- Customers & Front Desk Operations (Hidden from Cashier) -->
            @if(!auth()->user()->isCashier())
                <!-- Customers -->
                <x-nav-link :href="route('customers.index')" :active="request()->routeIs('customers.*')">
                    <svg class="mr-3 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    {{ __('Customers') }}
                </x-nav-link>

                <!-- Front Desk -->
                <div x-data="{ open: {{ request()->routeIs('transactions.*', 'pawn.*', 'payments.*') ? 'true' : 'false' }} }" class="space-y-1">
                    <button @click="open = !open" type="button" class="flex items-center w-full justify-between px-4 py-3 text-sm font-medium border-l-4 border-transparent text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 focus:outline-none transition duration-150 ease-in-out {{ request()->routeIs('transactions.*', 'pawn.*', 'payments.*') ? 'bg-gray-50 dark:bg-gray-800' : '' }}">
                        <div class="flex items-center">
                            <svg class="mr-3 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                            </svg>
                            Front Desk
                        </div>
                        <svg :class="{'rotate-180': open}" class="h-4 w-4 transform transition-transform duration-200 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div x-show="open" x-transition class="pl-11 pr-4 py-1 space-y-1">
                        <a href="{{ route('pawn.wizard') }}" class="block px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('pawn.wizard') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-100' }}">New Pawn</a>
                        <a href="{{ route('transactions.index') }}" class="block px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('transactions.index', 'transactions.show') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-100' }}">Transactions</a>
                        <a href="{{ route('transactions.actions.search') }}" class="block px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('transactions.actions.*', 'transactions.renew*', 'transactions.redeem*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-100' }}">Renew / Redeem</a>
                        <a href="{{ route('payments.index') }}" class="block px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('payments.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-100' }}">Payments</a>
                    </div>
                </div>
            @endif

            <!-- Inventory (Admin & Manager Only) -->
            @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                <div x-data="{ open: {{ request()->routeIs('items.*', 'safes.*', 'categories.*') ? 'true' : 'false' }} }" class="space-y-1 mt-2">
                    <button @click="open = !open" type="button" class="flex items-center w-full justify-between px-4 py-3 text-sm font-medium border-l-4 border-transparent text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 focus:outline-none transition duration-150 ease-in-out {{ request()->routeIs('items.*', 'safes.*', 'categories.*') ? 'bg-gray-50 dark:bg-gray-800' : '' }}">
                        <div class="flex items-center">
                            <svg class="mr-3 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                            Inventory & Vault
                        </div>
                        <svg :class="{'rotate-180': open}" class="h-4 w-4 transform transition-transform duration-200 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div x-show="open" x-transition class="pl-11 pr-4 py-1 space-y-1">
                        <a href="{{ route('items.index') }}" class="block px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('items.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-100' }}">Pawned Items</a>
                        <a href="{{ route('safes.index') }}" class="block px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('safes.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-100' }}">Safes / Vaults</a>
                        <a href="{{ route('categories.index') }}" class="block px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('categories.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-100' }}">Categories</a>
                    </div>
                </div>
            @endif

            <!-- Admin & Management -->
            @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                <div x-data="{ open: {{ request()->routeIs('approvals.*', 'users.*', 'audit-logs.*') ? 'true' : 'false' }} }" class="space-y-1 mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                    <button @click="open = !open" type="button" class="flex items-center w-full justify-between px-4 py-3 text-sm font-medium border-l-4 border-transparent text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 focus:outline-none transition duration-150 ease-in-out {{ request()->routeIs('approvals.*', 'users.*', 'audit-logs.*') ? 'bg-gray-50 dark:bg-gray-800' : '' }}">
                        <div class="flex items-center">
                            <svg class="mr-3 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            Management
                        </div>
                        <svg :class="{'rotate-180': open}" class="h-4 w-4 transform transition-transform duration-200 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div x-show="open" x-transition class="pl-11 pr-4 py-1 space-y-1">
                        <a href="{{ route('approvals.index') }}" class="block px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('approvals.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-100' }}">Pending Approvals</a>
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('users.index') }}" class="block px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('users.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-100' }}">Users</a>
                            <a href="{{ route('audit-logs.index') }}" class="block px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('audit-logs.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-100' }}">Audit Logs</a>
                        @endif
                    </div>
                </div>
            @endif


        </div>
    </div>

    <!-- Sidebar Footer / User Info -->
    <div class="border-t border-gray-100 dark:border-gray-700 p-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center truncate">
                <div class="flex-shrink-0 h-8 w-8 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center text-indigo-700 dark:text-indigo-300 font-bold">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="ml-3 truncate">
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300 truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 truncate">{{ Auth::user()->email }}</p>
                </div>
            </div>
        </div>
        
        <div class="mt-4 flex flex-col space-y-2">
            <!-- Dark Mode Toggle -->
            <button type="button" onclick="toggleDarkMode()" class="flex items-center text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 w-full text-left">
                <!-- Sun icon (shows in dark mode) -->
                <svg id="theme-toggle-light-icon" class="hidden mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <!-- Moon icon (shows in light mode) -->
                <svg id="theme-toggle-dark-icon" class="hidden mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                </svg>
                <span id="theme-toggle-text">Toggle Theme</span>
            </button>

            <a href="{{ route('profile.edit') }}" class="flex items-center text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">
                <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                {{ __('Profile') }}
            </a>
            
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center text-sm text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 w-full text-left">
                    <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    {{ __('Log Out') }}
                </button>
            </form>
        </div>
    </div>
</nav>

<script>
    // Initial icon state
    function updateThemeIcons() {
        const darkIcon = document.getElementById('theme-toggle-dark-icon');
        const lightIcon = document.getElementById('theme-toggle-light-icon');
        const themeText = document.getElementById('theme-toggle-text');
        
        if (document.documentElement.classList.contains('dark')) {
            lightIcon.classList.remove('hidden');
            darkIcon.classList.add('hidden');
            themeText.textContent = 'Light Mode';
        } else {
            darkIcon.classList.remove('hidden');
            lightIcon.classList.add('hidden');
            themeText.textContent = 'Dark Mode';
        }
    }

    // Toggle function
    function toggleDarkMode() {
        if (document.documentElement.classList.contains('dark')) {
            document.documentElement.classList.remove('dark');
            localStorage.theme = 'light';
        } else {
            document.documentElement.classList.add('dark');
            localStorage.theme = 'dark';
        }
        
        // Dispatch event for components like charts that need to react to theme changes
        window.dispatchEvent(new Event('theme-changed'));
        updateThemeIcons();
    }

    // Run on mount
    document.addEventListener('DOMContentLoaded', updateThemeIcons);
</script>
