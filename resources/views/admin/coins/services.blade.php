@extends('layouts.app')

@section('title', 'Service Pricing')
@section('page-title', 'Service Pricing')

@section('content')
<div class="max-w-4xl mx-auto">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Service Configuration</h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Configure which services are free or paid, and set coin costs.
                    </p>
                </div>

                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($services as $service)
                    <form method="POST" action="{{ route('admin.coins.services.update', $service) }}" class="p-6">
                        @csrf
                        @method('PATCH')
                        
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center">
                                    <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $service->name }}</h4>
                                    @if($service->is_active)
                                        <span class="ml-3 px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Active</span>
                                    @else
                                        <span class="ml-3 px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">Inactive</span>
                                    @endif
                                </div>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $service->description }}</p>
                                <p class="text-xs text-gray-400 mt-1">Slug: {{ $service->slug }}</p>
                            </div>
                        </div>

                        <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                            {{-- Is Free --}}
                            <div>
                                <label class="flex items-center">
                                    <input type="checkbox" name="is_free" value="1" 
                                        {{ $service->is_free ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Free Service</span>
                                </label>
                            </div>

                            {{-- Coin Cost --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Coin Cost</label>
                                <input type="number" name="coin_cost" value="{{ $service->coin_cost }}" min="0"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700"
                                    {{ $service->is_free ? 'disabled' : '' }}>
                                <p class="text-xs text-gray-500 mt-1">Ignored if free</p>
                            </div>

                            {{-- Is Active --}}
                            <div>
                                <label class="flex items-center">
                                    <input type="checkbox" name="is_active" value="1" 
                                        {{ $service->is_active ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Service Active</span>
                                </label>
                            </div>
                        </div>

                        <div class="mt-4 flex justify-end">
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm rounded hover:bg-indigo-700">
                                Save Changes
                            </button>
                        </div>
                    </form>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script>
        // Enable/disable coin cost based on free checkbox
        document.querySelectorAll('input[name="is_free"]').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const costInput = this.closest('form').querySelector('input[name="coin_cost"]');
                costInput.disabled = this.checked;
            });
        });
    </script>
@endsection
