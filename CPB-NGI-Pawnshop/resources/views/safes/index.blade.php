<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Safes') }}
            </h2>
            <a href="{{ route('safes.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded dark:bg-blue-600 dark:hover:bg-blue-800">
                New Safe
            </a>
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

            @if ($safes->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($safes as $safe)
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-lg transition overflow-hidden">
                            <div class="p-6">
                                <!-- Safe Code Header -->
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Safe ID</p>
                                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $safe->safe_code }}</p>
                                    </div>
                                </div>

                                <!-- Safe Details -->
                                <div class="space-y-3 mb-6">
                                    <div>
                                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Name</p>
                                        <p class="text-gray-900 dark:text-gray-100 font-semibold">{{ $safe->name }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Location</p>
                                        <p class="text-gray-900 dark:text-gray-100">{{ $safe->location }}</p>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Items Stored</p>
                                            <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $safe->items_count }}/{{ $safe->items_capacity }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Value Capacity</p>
                                            <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">₱{{ number_format($safe->capacity, 0) }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="flex gap-2 pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <a href="{{ route('safes.show', $safe) }}" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-3 rounded text-center text-sm dark:bg-blue-600 dark:hover:bg-blue-700 transition">
                                        View
                                    </a>
                                    @if ($safe->items_count > 0)
                                        <button type="button" disabled class="flex-1 bg-gray-300 dark:bg-gray-750 text-gray-500 dark:text-gray-400 font-semibold py-2 px-3 rounded text-sm cursor-not-allowed" title="Cannot edit safe with items">
                                            Edit
                                        </button>
                                        <button type="button" disabled class="flex-1 bg-gray-300 dark:bg-gray-750 text-gray-500 dark:text-gray-400 font-semibold py-2 px-3 rounded text-sm cursor-not-allowed" title="Cannot delete safe with items">
                                            Delete
                                        </button>
                                    @else
                                        <a href="{{ route('safes.edit', $safe) }}" class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 px-3 rounded text-center text-sm dark:bg-yellow-600 dark:hover:bg-yellow-700 transition">
                                            Edit
                                        </a>
                                        <form action="{{ route('safes.destroy', $safe) }}" method="POST" class="flex-1" onsubmit="return confirm('Are you sure?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-3 rounded text-sm dark:bg-red-600 dark:hover:bg-red-700 transition">
                                                Delete
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $safes->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <p class="text-gray-500 dark:text-gray-400 text-lg mb-4">No safes found.</p>
                    <a href="{{ route('safes.create') }}" class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded dark:bg-blue-600 dark:hover:bg-blue-800">
                        Create your first safe
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

