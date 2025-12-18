@extends('layouts.app')

@section('title', __('messages.sales_by_date'))

@section('content')
    <div class="w-full">
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 mb-6">
            <div class="flex flex-col md:flex-row justify-between items-center mb-4">
                <h4 class="text-2xl font-bold text-gray-800 dark:text-white mb-2 md:mb-0">{{ __('messages.sales_by_date_title') }}</h4>
                <a href="{{ route('reports.index') }}"
                    class="inline-flex items-center px-4 py-2 border border-blue-600 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-gray-700 rounded-md transition duration-150 ease-in-out">
                    <i class="mdi mdi-arrow-left mr-2"></i> {{ __('messages.back') }}
                </a>
            </div>

            <form action="{{ route('reports.salesByDate') }}" method="GET" class="mb-4">
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

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-blue-600 text-white shadow-md rounded-lg p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="mb-2 text-sm font-medium opacity-90">{{ __('messages.total_sales') }}</p>
                        <h3 class="text-3xl font-bold">{{ number_format($salesByDate->sum('total'), 2) }} {{ __('messages.currency_symbol') }}</h3>
                    </div>
                    <i class="mdi mdi-cash-multiple text-4xl opacity-80"></i>
                </div>
            </div>

            <div class="bg-cyan-500 text-white shadow-md rounded-lg p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="mb-2 text-sm font-medium opacity-90">{{ __('messages.daily_average') }}</p>
                        <h3 class="text-3xl font-bold">{{ number_format($salesByDate->avg('total'), 2) }} {{ __('messages.currency_symbol') }}</h3>
                    </div>
                    <i class="mdi mdi-chart-line text-4xl opacity-80"></i>
                </div>
            </div>

            <div class="bg-green-500 text-white shadow-md rounded-lg p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="mb-2 text-sm font-medium opacity-90">{{ __('messages.period') }}</p>
                        <h3 class="text-3xl font-bold">{{ $salesByDate->count() }} {{ __('messages.days') }}</h3>
                    </div>
                    <i class="mdi mdi-calendar-range text-4xl opacity-80"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 mb-6">
            <h5 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">{{ __('messages.sales_evolution') }}</h5>
            <div class="relative h-72">
                <canvas id="salesTrendChart"></canvas>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden mb-6">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h5 class="text-lg font-semibold text-gray-800 dark:text-white">{{ __('messages.sales_detail_by_date') }}</h5>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('messages.date') }}</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('messages.total_value') }}</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('messages.percentage_of_total') }}</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('messages.comparison') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @php 
                            $totalSales = $salesByDate->sum('total');
                            $avgSales = $salesByDate->avg('total');
                        @endphp

                        @foreach($salesByDate as $sale)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150 ease-in-out">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ \Carbon\Carbon::parse($sale->date)->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ number_format($sale->total, 2) }} {{ __('messages.currency_symbol') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 align-middle">
                                    @php $percentage = $totalSales > 0 ? ($sale->total / $totalSales) * 100 : 0; @endphp
                                    <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700 mb-1">
                                        <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $percentage }}%"></div>
                                    </div>
                                    <small class="text-gray-500 dark:text-gray-400">{{ number_format($percentage, 2) }}%</small>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if($sale->total > $avgSales)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                            <i class="mdi mdi-arrow-up-bold mr-1"></i>
                                            {{ number_format($avgSales > 0 ? (($sale->total / $avgSales) - 1) * 100 : 0, 2) }}% {{ __('messages.above_average') }}
                                        </span>
                                    @elseif($sale->total < $avgSales)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                            <i class="mdi mdi-arrow-down-bold mr-1"></i>
                                            {{ number_format($avgSales > 0 ? (1 - ($sale->total / $avgSales)) * 100 : 0, 2) }}% {{ __('messages.below_average') }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                            <i class="mdi mdi-equal mr-1"></i>
                                            {{ __('messages.on_average') }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
            <h5 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">{{ __('messages.performance_analysis') }}</h5>
            @php
                $maxSale = $salesByDate->max('total');
                $minSale = $salesByDate->min('total');
                $maxDate = $salesByDate->where('total', $maxSale)->first()->date ?? null;
                $minDate = $salesByDate->where('total', $minSale)->first()->date ?? null;
            @endphp
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @if($maxDate)
                    <div class="bg-green-50 dark:bg-green-900 border-l-4 border-green-500 p-4 rounded-r-lg">
                        <div class="flex items-center mb-2">
                            <i class="mdi mdi-star text-green-600 dark:text-green-400 text-xl mr-2"></i>
                            <h6 class="text-green-800 dark:text-green-200 font-semibold">{{ __('messages.best_day') }}</h6>
                        </div>
                        <p class="text-green-700 dark:text-green-300 mb-1">{{ \Carbon\Carbon::parse($maxDate)->format('d/m/Y') }}</p>
                        <h4 class="text-2xl font-bold text-green-900 dark:text-green-100">{{ number_format($maxSale, 2) }} {{ __('messages.currency_symbol') }}</h4>
                    </div>
                @endif

                @if($minDate)
                    <div class="bg-red-50 dark:bg-red-900 border-l-4 border-red-500 p-4 rounded-r-lg">
                        <div class="flex items-center mb-2">
                            <i class="mdi mdi-emoticon-sad text-red-600 dark:text-red-400 text-xl mr-2"></i>
                            <h6 class="text-red-800 dark:text-red-200 font-semibold">{{ __('messages.worst_day') }}</h6>
                        </div>
                        <p class="text-red-700 dark:text-red-300 mb-1">{{ \Carbon\Carbon::parse($minDate)->format('d/m/Y') }}</p>
                        <h4 class="text-2xl font-bold text-red-900 dark:text-green-100">{{ number_format($minSale, 2) }} {{ __('messages.currency_symbol') }}</h4>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dateLabels = [
                @foreach($salesByDate as $sale)
                    "{{ \Carbon\Carbon::parse($sale->date)->format('d/m') }}",
                @endforeach
            ];

            const salesTotals = [
                @foreach($salesByDate as $sale)
                    {{ $sale->total }},
                @endforeach
            ];

            // Sales Trend Chart
            const salesTrendCtx = document.getElementById('salesTrendChart').getContext('2d');
            const salesTrendChart = new Chart(salesTrendCtx, {
                type: 'line',
                data: {
                    labels: dateLabels,
                    datasets: [{
                        label: 'Vendas Diárias (MZN)',
                        data: salesTotals,
                        backgroundColor: 'rgba(75, 73, 172, 0.2)',
                        borderColor: '#4B49AC',
                        borderWidth: 2,
                        pointBackgroundColor: '#4B49AC',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Valor (MZN)'
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Data'
                            },
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