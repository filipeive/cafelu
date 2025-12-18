@extends('layouts.app')

@section('title', __('messages.sales_by_category'))

@section('content')
    <div class="w-full">
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 mb-6">
            <div class="flex flex-col md:flex-row justify-between items-center mb-4">
                <h4 class="text-2xl font-bold text-gray-800 dark:text-white mb-2 md:mb-0">{{ __('messages.sales_by_category_title') }}
                </h4>
                <a href="{{ route('reports.index') }}"
                    class="inline-flex items-center px-4 py-2 border border-blue-600 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-gray-700 rounded-md transition duration-150 ease-in-out">
                    <i class="mdi mdi-arrow-left mr-2"></i> {{ __('messages.back') }}
                </a>
            </div>

            <form action="{{ route('reports.salesByCategory') }}" method="GET" class="mb-4">
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

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
                <h5 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">{{ __('messages.category_performance') }}</h5>
                <div class="relative h-72">
                    <canvas id="categorySalesChart"></canvas>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
                <h5 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">{{ __('messages.sales_distribution') }}</h5>
                <div class="relative h-72">
                    <canvas id="categoryDistributionChart"></canvas>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-red-500 text-white shadow-md rounded-lg p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="mb-2 text-sm font-medium opacity-90">{{ __('messages.total_categories') }}</p>
                        <h3 class="text-3xl font-bold">{{ $salesByCategory->count() }}</h3>
                    </div>
                    <i class="mdi mdi-tag-multiple text-4xl opacity-80"></i>
                </div>
            </div>

            <div class="bg-green-500 text-white shadow-md rounded-lg p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="mb-2 text-sm font-medium opacity-90">{{ __('messages.total_sales_value') }}</p>
                        <h3 class="text-3xl font-bold">{{ number_format($salesByCategory->sum('total'), 2) }} {{ __('messages.currency_symbol') }}
                        </h3>
                    </div>
                    <i class="mdi mdi-cash-multiple text-4xl opacity-80"></i>
                </div>
            </div>

            <div class="bg-blue-600 text-white shadow-md rounded-lg p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="mb-2 text-sm font-medium opacity-90">{{ __('messages.average_per_category') }}</p>
                        <h3 class="text-3xl font-bold">{{ number_format($salesByCategory->avg('total'), 2) }} {{ __('messages.currency_symbol') }}
                        </h3>
                    </div>
                    <i class="mdi mdi-chart-pie text-4xl opacity-80"></i>
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
                                {{ __('messages.category') }}</th>
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
                        @php $totalSales = $salesByCategory->sum('total'); @endphp
                        @foreach($salesByCategory as $category)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150 ease-in-out">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    {{ $category->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    {{ $category->quantity }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    {{ number_format($category->total, 2) }} {{ __('messages.currency_symbol') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 align-middle">
                                    @php $percentage = $totalSales > 0 ? ($category->total / $totalSales) * 100 : 0; @endphp
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
            const categoryLabels = [
                @foreach($salesByCategory as $category)
                    "{{ $category->name }}",
                @endforeach
            ];

            const categoryQuantities = [
                @foreach($salesByCategory as $category)
                    {{ $category->quantity }},
                @endforeach
            ];

            const categoryTotals = [
                @foreach($salesByCategory as $category)
                    {{ $category->total }},
                @endforeach
            ];

            // Category Sales Chart
            const categorySalesCtx = document.getElementById('categorySalesChart').getContext('2d');
            const categorySalesChart = new Chart(categorySalesCtx, {
                type: 'bar',
                data: {
                    labels: categoryLabels,
                    datasets: [{
                        label: 'Valor Total (MZN)',
                        data: categoryTotals,
                        backgroundColor: '#4B49AC',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
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

            // Category Distribution Chart
            const categoryDistributionCtx = document.getElementById('categoryDistributionChart').getContext('2d');
            const categoryDistributionChart = new Chart(categoryDistributionCtx, {
                type: 'pie',
                data: {
                    labels: categoryLabels,
                    datasets: [{
                        data: categoryTotals,
                        backgroundColor: [
                            '#4B49AC',
                            '#FFC100',
                            '#248AFD',
                            '#FF4747',
                            '#57B657',
                            '#7978E9',
                            '#F3797E',
                            '#F89F9F',
                            '#7DA0FA',
                            '#FF8F00'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        });
    </script>
@endsection