@extends('layouts.app')

@section('title', __('messages.sales_by_product'))

@section('content')
    <div class="w-full">
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 mb-6">
            <div class="flex flex-col md:flex-row justify-between items-center mb-4">
                <h4 class="text-2xl font-bold text-gray-800 dark:text-white mb-2 md:mb-0">{{ __('messages.sales_by_product_title') }}
                </h4>
                <a href="{{ route('reports.index') }}"
                    class="inline-flex items-center px-4 py-2 border border-blue-600 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-gray-700 rounded-md transition duration-150 ease-in-out">
                    <i class="mdi mdi-arrow-left mr-2"></i> {{ __('messages.back') }}
                </a>
            </div>

            <form action="{{ route('reports.salesByProduct') }}" method="GET" class="mb-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('messages.date_initial') }}</label>
                        <input type="date"
                            class="form-input w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                            id="start_date" name="start_date" value="{{ $startDate->format('Y-m-d') }}">
                    </div>
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('messages.date_final') }}</label>
                        <input type="date"
                            class="form-input w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                            id="end_date" name="end_date" value="{{ $endDate->format('Y-m-d') }}">
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

        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 mb-6">
            <h5 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">{{ __('messages.product_sales_performance') }}</h5>
            <div class="relative h-72">
                <canvas id="productSalesChart"></canvas>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-cyan-500 text-white shadow-md rounded-lg p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="mb-2 text-sm font-medium opacity-90">{{ __('messages.total_products_sold') }}</p>
                        <h3 class="text-3xl font-bold">{{ $salesByProduct->sum('quantity') }}</h3>
                    </div>
                    <i class="mdi mdi-package-variant text-4xl opacity-80"></i>
                </div>
            </div>

            <div class="bg-green-500 text-white shadow-md rounded-lg p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="mb-2 text-sm font-medium opacity-90">{{ __('messages.total_sales_value') }}</p>
                        <h3 class="text-3xl font-bold">{{ number_format($salesByProduct->sum('total'), 2) }} {{ __('messages.currency_symbol') }}
                        </h3>
                    </div>
                    <i class="mdi mdi-cash-multiple text-4xl opacity-80"></i>
                </div>
            </div>

            <div class="bg-blue-600 text-white shadow-md rounded-lg p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="mb-2 text-sm font-medium opacity-90">{{ __('messages.average_ticket_per_product') }}</p>
                        <h3 class="text-3xl font-bold">{{ number_format($salesByProduct->avg('total'), 2) }} {{ __('messages.currency_symbol') }}
                        </h3>
                    </div>
                    <i class="mdi mdi-chart-areaspline text-4xl opacity-80"></i>
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
                                {{ __('messages.quantity_sold_plural') }}</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('messages.total') }}</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('messages.percentage_of_total') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @php $totalSales = $salesByProduct->sum('total'); @endphp
                        @foreach($salesByProduct as $product)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150 ease-in-out">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    {{ $product->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    {{ $product->quantity }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    {{ number_format($product->total, 2) }} {{ __('messages.currency_symbol') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 align-middle">
                                    @php $percentage = $totalSales > 0 ? ($product->total / $totalSales) * 100 : 0; @endphp
                                    <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700 mb-1">
                                        <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $percentage }}%"></div>
                                    </div>
                                    <small class="text-gray-500 dark:text-gray-400">{{ number_format($percentage, 2) }}%</small>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Get the top 10 products for chart
            const productLabels = [
                @foreach($salesByProduct->take(10) as $product)
                    "{{ $product->name }}",
                @endforeach
            ];

            const productQuantities = [
                @foreach($salesByProduct->take(10) as $product)
                    {{ $product->quantity }},
                @endforeach
            ];

            const productTotals = [
                @foreach($salesByProduct->take(10) as $product)
                    {{ $product->total }},
                @endforeach
            ];

            // Product Sales Chart
            const productSalesCtx = document.getElementById('productSalesChart').getContext('2d');
            const productSalesChart = new Chart(productSalesCtx, {
                type: 'bar',
                data: {
                    labels: productLabels,
                    datasets: [
                        {
                            label: 'Quantidade Vendida',
                            data: productQuantities,
                            backgroundColor: '#4B49AC',
                            borderColor: '#4B49AC',
                            borderWidth: 1,
                            yAxisID: 'y'
                        },
                        {
                            label: 'Valor Total (MZN)',
                            data: productTotals,
                            backgroundColor: '#FFC100',
                            borderColor: '#FFC100',
                            borderWidth: 1,
                            type: 'line',
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            position: 'left',
                            title: {
                                display: true,
                                text: 'Quantidade'
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            }
                        },
                        y1: {
                            beginAtZero: true,
                            position: 'right',
                            grid: {
                                drawOnChartArea: false
                            },
                            title: {
                                display: true,
                                text: 'Valor (MZN)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection