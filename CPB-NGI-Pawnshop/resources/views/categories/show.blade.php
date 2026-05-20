<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $category->name }}
            </h2>
            <div class="flex gap-2">
                @if($category->items->count() > 0)
                    <button disabled class="bg-gray-300 dark:bg-gray-700 text-gray-500 dark:text-gray-400 font-bold py-2 px-4 rounded cursor-not-allowed" title="Cannot edit category containing items">
                        Edit
                    </button>
                @else
                    <a href="{{ route('categories.edit', $category) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded dark:bg-yellow-600 dark:hover:bg-yellow-800">
                        Edit
                    </a>
                @endif
                <a href="{{ route('categories.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded dark:bg-gray-600 dark:hover:bg-gray-800">
                    Back
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Category Details Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Category Details</h3>
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400">Name</h3>
                            <p class="text-lg font-semibold mt-1">{{ $category->name }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Items</h3>
                            <p class="text-lg font-semibold mt-1">{{ $category->items->count() }}</p>
                        </div>
                    </div>

                    @if($category->description)
                        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400">Description</h3>
                            <p class="text-gray-900 dark:text-gray-100 mt-2">{{ $category->description }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Items in Category -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Items in This Category ({{ $category->items->count() }})</h3>

                    @if($category->items->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th class="px-6 py-3">Item Code</th>
                                        <th class="px-6 py-3">Name</th>
                                        <th class="px-6 py-3">Condition</th>
                                        <th class="px-6 py-3">Appraised Value</th>
                                        <th class="px-6 py-3">Status</th>
                                        <th class="px-6 py-3">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($category->items as $item)
                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-900">
                                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $item->item_code }}</td>
                                            <td class="px-6 py-4">{{ $item->name }}</td>
                                            <td class="px-6 py-4">
                                                <span class="px-3 py-1 rounded text-xs font-semibold
                                                    @if($item->condition === 'excellent')
                                                        bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                    @elseif($item->condition === 'good')
                                                        bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                                    @elseif($item->condition === 'fair')
                                                        bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                                    @else
                                                        bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                                    @endif
                                                ">
                                                    {{ ucfirst($item->condition) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">₱{{ number_format($item->appraised_value, 2) }}</td>
                                            <td class="px-6 py-4">
                                                @if($item->is_available)
                                                    <span class="px-3 py-1 rounded text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Available</span>
                                                @else
                                                    <span class="px-3 py-1 rounded text-xs font-semibold bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200">In Loan</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4">
                                                <a href="{{ route('items.show', $item) }}" class="text-blue-500 hover:text-blue-700 dark:hover:text-blue-400">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400">No items in this category yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
