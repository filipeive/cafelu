@extends('layouts.app')

@section('title', __('messages.search_results'))

@section('content')
    <div class="w-full">
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                {{ __('messages.search_results_for') }}: <span class="text-warning">"{{ $query }}"</span>
            </h2>
            <p class="text-gray-500 dark:text-gray-400 mt-1">
                {{ $totalResults }} {{ __('messages.records_found') }}
            </p>
        </div>

        @if($totalResults > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Products -->
                @if($results['products']->count() > 0)
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div
                            class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 flex items-center gap-2">
                            <i class="mdi mdi-food-variant text-warning"></i>
                            <h3 class="font-semibold text-gray-700 dark:text-gray-200">{{ __('messages.products') }}</h3>
                        </div>
                        <div class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach($results['products'] as $product)
                                <a href="{{ route('products.index', ['search' => $product->name]) }}"
                                    class="block px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $product->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $product->description }}</p>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Sales -->
                @if($results['sales']->count() > 0)
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div
                            class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 flex items-center gap-2">
                            <i class="mdi mdi-cash-register text-success"></i>
                            <h3 class="font-semibold text-gray-700 dark:text-gray-200">{{ __('messages.sales') }}</h3>
                        </div>
                        <div class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach($results['sales'] as $sale)
                                <a href="{{ route('sales.show', $sale->id) }}"
                                    class="block px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">#{{ $sale->id }} -
                                        {{ $sale->customer_name ?? __('messages.customer') }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $sale->created_at->format('d/m/Y H:i') }} -
                                        {{ number_format($sale->total_amount, 2) }} MZN</p>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Orders -->
                @if($results['orders']->count() > 0)
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div
                            class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 flex items-center gap-2">
                            <i class="mdi mdi-cart-outline text-info"></i>
                            <h3 class="font-semibold text-gray-700 dark:text-gray-200">{{ __('messages.orders') }}</h3>
                        </div>
                        <div class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach($results['orders'] as $order)
                                <a href="{{ route('orders.show', $order->id) }}"
                                    class="block px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">#{{ $order->id }} -
                                        {{ $order->table->number ?? __('messages.no_table') }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $order->created_at->format('d/m/Y H:i') }} -
                                        {{ ucfirst($order->status) }}</p>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Clients -->
                @if($results['clients']->count() > 0)
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div
                            class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 flex items-center gap-2">
                            <i class="mdi mdi-account-group text-primary"></i>
                            <h3 class="font-semibold text-gray-700 dark:text-gray-200">{{ __('messages.clients') }}</h3>
                        </div>
                        <div class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach($results['clients'] as $client)
                                <a href="{{ route('clients.index', ['search' => $client->name]) }}"
                                    class="block px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $client->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $client->email }} - {{ $client->phone }}</p>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Employees -->
                @if($results['employees']->count() > 0)
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div
                            class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 flex items-center gap-2">
                            <i class="mdi mdi-account-tie text-warning"></i>
                            <h3 class="font-semibold text-gray-700 dark:text-gray-200">{{ __('messages.employees') }}</h3>
                        </div>
                        <div class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach($results['employees'] as $employee)
                                <a href="{{ route('employees.index', ['search' => $employee->name]) }}"
                                    class="block px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $employee->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $employee->role }} - {{ $employee->phone }}
                                    </p>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Users -->
                @if($results['users']->count() > 0)
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div
                            class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 flex items-center gap-2">
                            <i class="mdi mdi-account text-danger"></i>
                            <h3 class="font-semibold text-gray-700 dark:text-gray-200">{{ __('messages.users') }}</h3>
                        </div>
                        <div class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach($results['users'] as $user)
                                <a href="{{ route('users.index', ['search' => $user->name]) }}"
                                    class="block px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $user->email }} - {{ $user->role }}</p>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @else
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-12 text-center">
                <div class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="mdi mdi-magnify-close text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">{{ __('messages.no_results_found') }}</h3>
                <p class="text-gray-500 dark:text-gray-400 mt-2">{{ __('messages.try_different_keywords') }}</p>
                <a href="{{ route('dashboard') }}"
                    class="mt-6 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-warning hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-warning">
                    {{ __('messages.back_to_dashboard') }}
                </a>
            </div>
        @endif
    </div>
@endsection