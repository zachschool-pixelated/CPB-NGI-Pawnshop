<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Item Categories') }}
            </h2>
            <div class="flex items-center gap-4">
                <!-- Layout Toggle Buttons -->
                <div class="flex rounded-md shadow-sm" role="group">
                    <button id="btn-list-view" type="button" class="p-2 rounded-l-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 transition" title="List View">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <button id="btn-grid-view" type="button" class="p-2 rounded-r-md border-t border-b border-r border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 transition" title="Grid View">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    </button>
                </div>
                
                <a href="{{ route('categories.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded dark:bg-blue-600 dark:hover:bg-blue-800 transition">
                    New Category
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if ($message = Session::get('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded dark:bg-green-900 dark:border-green-700 dark:text-green-100">
                    {{ $message }}
                </div>
            @endif

            @if ($message = Session::get('error'))
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded dark:bg-red-900 dark:border-red-700 dark:text-red-100">
                    {{ $message }}
                </div>
            @endif

            <!-- List View Container -->
            <div id="list-view-container" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th class="px-6 py-3">Name</th>
                                <th class="px-6 py-3">Description</th>
                                <th class="px-6 py-3">Items</th>
                                <th class="px-6 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($categories as $category)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-900">
                                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        {{ $category->name }}
                                    </td>
                                    <td class="px-6 py-4">{{ Str::limit($category->description, 50) }}</td>
                                    <td class="px-6 py-4">{{ $category->items_count }}</td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('categories.show', $category) }}" class="text-blue-500 hover:text-blue-700 dark:hover:text-blue-400 mr-3">View</a>
                                        @if($category->items_count > 0)
                                            <span class="text-gray-400 dark:text-gray-600 cursor-not-allowed mr-3" title="Cannot edit category containing items">Edit</span>
                                            <span class="text-gray-400 dark:text-gray-600 cursor-not-allowed" title="Cannot delete category containing items">Delete</span>
                                        @else
                                            <a href="{{ route('categories.edit', $category) }}" class="text-yellow-500 hover:text-yellow-700 dark:hover:text-yellow-400 mr-3 font-medium">Edit</a>
                                            <form action="{{ route('categories.destroy', $category) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700 dark:hover:text-red-400 font-medium">Delete</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                        No categories found. <a href="{{ route('categories.create') }}" class="text-blue-500 hover:text-blue-700">Create one</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Grid View Container -->
            <div id="grid-view-container" class="hidden">
                @if ($categories->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($categories as $category)
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm hover:shadow-md border border-gray-200 dark:border-gray-700 p-6 flex flex-col justify-between transition">
                                <div>
                                    <div class="flex justify-between items-start mb-4">
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $category->name }}</h3>
                                        <span class="text-xs font-semibold bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-2.5 py-0.5 rounded">
                                            {{ $category->items_count }} {{ Str::plural('Item', $category->items_count) }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 line-clamp-3">
                                        {{ $category->description ?? __('No description provided.') }}
                                    </p>
                                </div>
                                <div class="mt-4 pt-4 border-t border-gray-150 dark:border-gray-700 flex justify-end gap-3 text-sm">
                                    <a href="{{ route('categories.show', $category) }}" class="text-blue-500 hover:text-blue-700 dark:hover:text-blue-400 font-medium">View</a>
                                    @if($category->items_count > 0)
                                        <span class="text-gray-400 dark:text-gray-600 cursor-not-allowed font-medium" title="Cannot edit category containing items">Edit</span>
                                        <span class="text-gray-400 dark:text-gray-600 cursor-not-allowed font-medium" title="Cannot delete category containing items">Delete</span>
                                    @else
                                        <a href="{{ route('categories.edit', $category) }}" class="text-yellow-500 hover:text-yellow-700 dark:hover:text-yellow-400 font-medium">Edit</a>
                                        <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 dark:hover:text-red-400 font-medium">Delete</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-white dark:bg-gray-800 p-6 text-center rounded-lg shadow-sm text-gray-500 dark:text-gray-400">
                        No categories found. <a href="{{ route('categories.create') }}" class="text-blue-500 hover:text-blue-700">Create one</a>
                    </div>
                @endif
            </div>

            <div class="mt-4">
                {{ $categories->links() }}
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btnList = document.getElementById('btn-list-view');
            const btnGrid = document.getElementById('btn-grid-view');
            const containerList = document.getElementById('list-view-container');
            const containerGrid = document.getElementById('grid-view-container');

            function setView(view) {
                localStorage.setItem('category-view-preference', view);
                if (view === 'grid') {
                    containerList.classList.add('hidden');
                    containerGrid.classList.remove('hidden');

                    // Set grid button active
                    btnGrid.className = 'p-2 rounded-r-md border border-blue-500 bg-blue-500 text-white dark:border-blue-600 dark:bg-blue-600 transition';
                    btnList.className = 'p-2 rounded-l-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 transition';
                } else {
                    containerList.classList.remove('hidden');
                    containerGrid.classList.add('hidden');

                    // Set list button active
                    btnList.className = 'p-2 rounded-l-md border border-blue-500 bg-blue-500 text-white dark:border-blue-600 dark:bg-blue-600 transition';
                    btnGrid.className = 'p-2 rounded-r-md border-t border-b border-r border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 transition';
                }
            }

            // Load saved preference or default to list
            const savedView = localStorage.getItem('category-view-preference') || 'list';
            setView(savedView);

            btnList.addEventListener('click', () => setView('list'));
            btnGrid.addEventListener('click', () => setView('grid'));
        });
    </script>
</x-app-layout>
