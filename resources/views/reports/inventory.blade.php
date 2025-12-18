@extends('layouts.app')

@section('title', __('messages.inventory_report'))

@section('content')
    <div class="w-full">
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 mb-6">
            <div class="flex flex-col md:flex-row justify-between items-center mb-4">
                <h4 class="text-2xl font-bold text-gray-800 dark:text-white mb-2 md:mb-0">{{ __('messages.inventory_report_title') }}</h4>
                <a href="{{ route('reports.index') }}"
                    class="inline-flex items-center px-4 py-2 border border-blue-600 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-gray-700 rounded-md transition duration-150 ease-in-out">
                    <i class="mdi mdi-arrow-left mr-2"></i> {{ __('messages.back') }}
                </a>
            </div>

            <form action="{{ route('reports.inventory') }}" method="GET" class="mb-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-end">
                    <div>
                        <label for="low_stock_threshold"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('messages.low_stock_threshold') }}</label>
                        <input type="number"
                            class="form-input w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                            id="low_stock_threshold" name="low_stock_threshold" min="1" value="{{ $lowStockThreshold }}">
                    </div>
                    <div>
                        <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md transition duration-150 ease-in-out">
                            <i class="mdi mdi-filter mr-2"></i> {{ __('messages.filter') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-red-500 text-white shadow-md rounded-lg p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="mb-2 text-sm font-medium opacity-90">{{ __('messages.critical_stock_products') }}</p>
                        <h3 class="text-3xl font-bold">{{ $lowStockProducts->where('stock_quantity', '<', 5)->count() }}
                        </h3>
                    </div>
                    <i class="mdi mdi-alert-circle-outline text-4xl opacity-80"></i>
                </div>
            </div>

            <div class="bg-yellow-400 text-gray-900 shadow-md rounded-lg p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="mb-2 text-sm font-medium opacity-90">{{ __('messages.total_low_stock_products') }}</p>
                        <h3 class="text-3xl font-bold">{{ $lowStockProducts->count() }}</h3>
                    </div>
                    <i class="mdi mdi-alert text-4xl opacity-80"></i>
                </div>
            </div>

            <div class="bg-cyan-500 text-white shadow-md rounded-lg p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="mb-2 text-sm font-medium opacity-90">{{ __('messages.defined_limit') }}</p>
                        <h3 class="text-3xl font-bold">{{ $lowStockThreshold }} {{ __('messages.units') }}</h3>
                    </div>
                    <i class="mdi mdi-format-list-bulleted text-4xl opacity-80"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('messages.product') }}</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('messages.category') }}</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('messages.current_stock') }}</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('messages.status') }}</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($lowStockProducts as $product)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150 ease-in-out">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    {{ $product->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    {{ $product->category->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    {{ $product->stock_quantity }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($product->stock_quantity <= 0)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">{{ __('messages.out_of_stock') }}</span>
                                    @elseif ($product->stock_quantity < 5)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">{{ __('messages.critical') }}</span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">{{ __('messages.low_stock') }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('products.index') }}"
                                        class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">{{ __('messages.restock') }}</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5"
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                    {{ __('messages.no_low_stock_found') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection