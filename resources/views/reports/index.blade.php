@extends('layouts.app')

@section('title', __('messages.reports_dashboard'))

@section('content')
    <div class="w-full">
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 mb-6">
            <h4 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">{{ __('messages.system_reports') }}</h4>
            <p class="text-gray-600 dark:text-gray-400">{{ __('messages.select_report_type') }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Relatório de Vendas -->
            <div
                class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-shadow duration-300">
                <div class="p-6 text-center">
                    <i class="mdi mdi-cash-multiple text-blue-500 text-5xl mb-4 block"></i>
                    <h5 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">{{ __('messages.sales_report') }}
                    </h5>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">{{ __('messages.sales_report_desc') }}</p>
                    <a href="{{ route('reports.sales') }}"
                        class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">
                        {{ __('messages.view') }}
                    </a>
                </div>
            </div>

            <!-- Relatório de Estoque -->
            <div
                class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-shadow duration-300">
                <div class="p-6 text-center">
                    <i class="mdi mdi-package-variant text-green-500 text-5xl mb-4 block"></i>
                    <h5 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">
                        {{ __('messages.inventory_report') }}</h5>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">{{ __('messages.inventory_report_desc') }}</p>
                    <a href="{{ route('reports.inventory') }}"
                        class="inline-block bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">
                        {{ __('messages.view') }}
                    </a>
                </div>
            </div>

            <!-- Vendas por Produto -->
            <div
                class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-shadow duration-300">
                <div class="p-6 text-center">
                    <i class="mdi mdi-food text-cyan-500 text-5xl mb-4 block"></i>
                    <h5 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">
                        {{ __('messages.sales_by_product') }}</h5>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">{{ __('messages.sales_by_product_desc') }}</p>
                    <a href="{{ route('reports.salesByProduct') }}"
                        class="inline-block bg-cyan-600 hover:bg-cyan-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">
                        {{ __('messages.view') }}
                    </a>
                </div>
            </div>

            <!-- Vendas por Categoria -->
            <div
                class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-shadow duration-300">
                <div class="p-6 text-center">
                    <i class="mdi mdi-tag-multiple text-red-500 text-5xl mb-4 block"></i>
                    <h5 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">
                        {{ __('messages.sales_by_category') }}</h5>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">{{ __('messages.sales_by_category_desc') }}</p>
                    <a href="{{ route('reports.salesByCategory') }}"
                        class="inline-block bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">
                        {{ __('messages.view') }}
                    </a>
                </div>
            </div>

            <!-- Vendas por Forma de Pagamento -->
            <div
                class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-shadow duration-300">
                <div class="p-6 text-center">
                    <i class="mdi mdi-credit-card-multiple text-yellow-500 text-5xl mb-4 block"></i>
                    <h5 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">
                        {{ __('messages.sales_by_payment_method') }}</h5>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">{{ __('messages.sales_by_payment_method_desc') }}</p>
                    <a href="{{ route('reports.salesByPaymentMethod') }}"
                        class="inline-block bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">
                        {{ __('messages.view') }}
                    </a>
                </div>
            </div>

            <!-- Vendas por Data -->
            <div
                class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-shadow duration-300">
                <div class="p-6 text-center">
                    <i class="mdi mdi-calendar-text text-gray-500 text-5xl mb-4 block"></i>
                    <h5 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">{{ __('messages.sales_by_date') }}
                    </h5>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">{{ __('messages.sales_by_date_desc') }}</p>
                    <a href="{{ route('reports.salesByDate') }}"
                        class="inline-block bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">
                        {{ __('messages.view') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection