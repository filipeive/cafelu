@extends('layouts.app')

@section('title', __('messages.sales_by_payment_method'))

@section('content')
    <div class="w-full">
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 mb-6">
            <div class="flex flex-col md:flex-row justify-between items-center mb-4">
                <h4 class="text-2xl font-bold text-gray-800 dark:text-white mb-2 md:mb-0">{{ __('messages.sales_by_payment_method_title') }}</h4>
                <a href="{{ route('reports.index') }}"
                    class="inline-flex items-center px-4 py-2 border border-blue-600 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-gray-700 rounded-md transition duration-150 ease-in-out">
                    <i class="mdi mdi-arrow-left mr-2"></i> {{ __('messages.back') }}
                </a>
            </div>

            <form action="{{ route('reports.salesByPaymentMethod') }}" method="GET" class="mb-4">
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
                <h5 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">{{ __('messages.payment_method_distribution') }}
                </h5>
                <div class="relative h-72">
                    <canvas id="paymentMethodChart"></canvas>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
                <h5 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">{{ __('messages.transaction_count') }}</h5>
                <div class="relative h-72">
                    <canvas id="transactionCountChart"></canvas>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            @foreach($salesByPaymentMethod as $index => $method)
                @php
                    $colors = ['bg-blue-600', 'bg-green-500', 'bg-yellow-400', 'bg-red-500', 'bg-cyan-500'];
                    $color = $colors[$index % count($colors)];
                    $icons = ['mdi-credit-card', 'mdi-cash', 'mdi-cash-multiple', 'mdi-cellphone', 'mdi-bank'];
                    $icon = $icons[$index % count($icons)];
                    $textColor = ($color == 'bg-yellow-400') ? 'text-gray-900' : 'text-white';
                @endphp
                <div class="{{ $color }} {{ $textColor }} shadow-md rounded-lg p-6">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="mb-2 text-sm font-medium opacity-90">{{ $method->payment_method }}</p>
                            <h3 class="text-3xl font-bold">{{ number_format($method->total, 2) }} {{ __('messages.currency_symbol') }}</h3>
                            <small class="opacity-80">{{ $method->count }} {{ __('messages.transactions') }}</small>
                        </div>
                        <i class="mdi {{ $icon }} text-4xl opacity-80"></i>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('messages.payment_method') }}</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('messages.number_of_transactions') }}</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('messages.total_amount') }}</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('messages.average_per_transaction') }}</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('messages.percentage_of_total') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @php $totalSales = $salesByPaymentMethod->sum('total'); @endphp
                        @foreach($salesByPaymentMethod as $method)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150 ease-in-out">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    @if($method->payment_method == 'Dinheiro')
                                        <i class="mdi mdi-cash text-green-500 mr-2"></i>
                                    @elseif($method->payment_method == 'Cartão de Crédito')
                                        <i class="mdi mdi-credit-card text-blue-600 mr-2"></i>
                                    @elseif($method->payment_method == 'Cartão de Débito')
                                        <i class="mdi mdi-credit-card-outline text-cyan-500 mr-2"></i>
                                    @elseif($method->payment_method == 'Pix')
                                        <i class="mdi mdi-cellphone text-yellow-500 mr-2"></i>
                                    @elseif($method->payment_method == 'Transferência')
                                        <i class="mdi mdi-bank text-red-500 mr-2"></i>
                                    @else
                                        <i class="mdi mdi-cash-multiple mr-2"></i>
                                    @endif
                                    {{ $method->payment_method }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    {{ $method->count }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    {{ number_format($method->total, 2) }} {{ __('messages.currency_symbol') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    {{ number_format($method->total / $method->count, 2) }} {{ __('messages.currency_symbol') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 align-middle">
                                    @php $percentage = $totalSales > 0 ? ($method->total / $totalSales) * 100 : 0; @endphp
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
            const paymentLabels = [
                @foreach($salesByPaymentMethod as $method)
                    "{{ $method->payment_method }}",
                @endforeach
            ];

            const paymentTotals = [
                @foreach($salesByPaymentMethod as $method)
                    {{ $method->total }},
                @endforeach
            ];

            const transactionCounts = [
                @foreach($salesByPaymentMethod as $method)
                    {{ $method->count }},
                @endforeach
            ];

            // Payment Method Distribution Chart
            const paymentMethodCtx = document.getElementById('paymentMethodChart').getContext('2d');
            const paymentMethodChart = new Chart(paymentMethodCtx, {
                type: 'pie',
                data: {
                    labels: paymentLabels,
                    datasets: [{
                        data: paymentTotals,
                        backgroundColor: [
                            '#4B49AC',
                            '#FFC100',
                            '#248AFD',
                            '#FF4747',
                            '#57B657'
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

            // Transaction Count Chart
            const transactionCountCtx = document.getElementById('transactionCountChart').getContext('2d');
            const transactionCountChart = new Chart(transactionCountCtx, {
                type: 'bar',
                data: {
                    labels: paymentLabels,
                    datasets: [{
                        label: 'Quantidade de Transações',
                        data: transactionCounts,
                        backgroundColor: '#FFC100',
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
        });
    </script>
@endsection