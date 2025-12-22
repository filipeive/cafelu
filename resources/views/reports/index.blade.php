@extends('layouts.app')

@section('title', 'Dashboard de Relatórios')

@section('content')
    <div class="p-6 space-y-6">
        <!-- Header -->
        <div
            class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Relatórios e Insights</h1>
                <p class="text-gray-500 dark:text-gray-400">Acompanhe o desempenho do seu negócio em tempo real.</p>
            </div>

            <form action="{{ route('reports.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
                <div
                    class="flex items-center gap-2 bg-gray-50 dark:bg-gray-700/50 p-2 rounded-xl border border-gray-200 dark:border-gray-600">
                    <input type="date" name="date_from" value="{{ $dateFrom }}"
                        class="bg-transparent border-none text-sm focus:ring-0 text-gray-700 dark:text-gray-200">
                    <span class="text-gray-400">até</span>
                    <input type="date" name="date_to" value="{{ $dateTo }}"
                        class="bg-transparent border-none text-sm focus:ring-0 text-gray-700 dark:text-gray-200">
                </div>
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl font-medium transition-all flex items-center gap-2 shadow-sm shadow-blue-200 dark:shadow-none">
                    <i class="mdi mdi-filter-variant"></i> Filtrar
                </button>
            </form>
        </div>

        <!-- Quick Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Revenue Card -->
            <div
                class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform">
                    <i class="mdi mdi-currency-usd text-6xl text-green-600"></i>
                </div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Receita Total</p>
                <h3 class="text-2xl font-bold text-gray-800 dark:text-white mt-1">MT {{ number_format($totalRevenue, 2) }}
                </h3>
                <div class="mt-4 flex items-center gap-2">
                    <span
                        class="text-xs font-medium px-2 py-0.5 rounded-full {{ $revenueGrowth >= 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        <i class="mdi {{ $revenueGrowth >= 0 ? 'mdi-arrow-up' : 'mdi-arrow-down' }}"></i>
                        {{ abs(number_format($revenueGrowth, 1)) }}%
                    </span>
                    <span class="text-xs text-gray-400">vs período anterior</span>
                </div>
            </div>

            <!-- Profit Card -->
            <div
                class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform">
                    <i class="mdi mdi-trending-up text-6xl text-blue-600"></i>
                </div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Lucro Líquido</p>
                <h3 class="text-2xl font-bold text-gray-800 dark:text-white mt-1">MT {{ number_format($netProfit, 2) }}</h3>
                <div class="mt-4 flex items-center gap-2">
                    <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-blue-100 text-blue-700">
                        Margem: {{ number_format($netMargin, 1) }}%
                    </span>
                </div>
            </div>

            <!-- Sales Count Card -->
            <div
                class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform">
                    <i class="mdi mdi-cart-outline text-6xl text-purple-600"></i>
                </div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total de Vendas</p>
                <h3 class="text-2xl font-bold text-gray-800 dark:text-white mt-1">{{ $totalSales }}</h3>
                <p class="text-xs text-gray-400 mt-4">Ticket Médio: MT {{ number_format($averageTicket, 2) }}</p>
            </div>

            <!-- Expenses Card -->
            <div
                class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform">
                    <i class="mdi mdi-account-cash text-6xl text-red-600"></i>
                </div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Despesas Totais</p>
                <h3 class="text-2xl font-bold text-gray-800 dark:text-white mt-1">MT {{ number_format($totalExpenses, 2) }}
                </h3>
                <p class="text-xs text-gray-400 mt-4">COGS: MT {{ number_format($costOfGoodsSold, 2) }}</p>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Sales Trend Chart -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white">Tendência de Vendas e Lucro</h3>
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-full bg-blue-500"></span>
                            <span class="text-xs text-gray-500">Receita</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-full bg-green-500"></span>
                            <span class="text-xs text-gray-500">Lucro</span>
                        </div>
                    </div>
                </div>
                <div class="h-80">
                    <canvas id="salesTrendChart"></canvas>
                </div>
            </div>

            <!-- Payment Methods Chart -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-6">Formas de Pagamento</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="h-64">
                        <canvas id="paymentMethodChart"></canvas>
                    </div>
                    <div class="space-y-4 flex flex-col justify-center">
                        @foreach($paymentMethodLabels as $index => $label)
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/30 rounded-xl">
                                <div class="flex items-center gap-3">
                                    <div class="w-2 h-2 rounded-full"
                                        style="background-color: {{ ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6'][$index % 5] }}">
                                    </div>
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $label }}</span>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-bold text-gray-800 dark:text-white">MT
                                        {{ number_format($paymentMethodAmountData[$index], 2) }}</p>
                                    <p class="text-xs text-gray-400">{{ $paymentMethodData[$index] }} transações</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Section: Top Products & Recent Sales -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Top Products -->
            <div
                class="lg:col-span-1 bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white">Mais Vendidos</h3>
                    <a href="{{ route('reports.salesByProduct') }}" class="text-sm text-blue-600 hover:underline">Ver
                        todos</a>
                </div>
                <div class="space-y-4">
                    @foreach($topProducts->take(5) as $product)
                        <div
                            class="flex items-center gap-4 p-3 hover:bg-gray-50 dark:hover:bg-gray-700/30 rounded-xl transition-colors">
                            <div
                                class="w-10 h-10 rounded-lg bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center text-blue-600 font-bold">
                                {{ $loop->iteration }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-gray-800 dark:text-white truncate">{{ $product->name }}</p>
                                <p class="text-xs text-gray-500">{{ $product->total_quantity }} unidades vendidas</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-gray-800 dark:text-white">MT
                                    {{ number_format($product->total_revenue, 2) }}</p>
                                <p class="text-xs text-green-600">{{ number_format($product->margin, 1) }}% margem</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Recent Sales -->
            <div
                class="lg:col-span-2 bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white">Vendas Recentes</h3>
                    <a href="{{ route('reports.sales') }}" class="text-sm text-blue-600 hover:underline">Ver todas</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr
                                class="text-xs font-bold text-gray-400 uppercase tracking-wider border-b border-gray-100 dark:border-gray-700">
                                <th class="pb-3 px-2">ID</th>
                                <th class="pb-3 px-2">Cliente</th>
                                <th class="pb-3 px-2">Data</th>
                                <th class="pb-3 px-2">Método</th>
                                <th class="pb-3 px-2 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                            @foreach($recentSales as $sale)
                                <tr class="text-sm group hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                    <td class="py-4 px-2 font-medium text-gray-800 dark:text-white">#{{ $sale->id }}</td>
                                    <td class="py-4 px-2">
                                        <div class="flex flex-col">
                                            <span
                                                class="font-medium text-gray-800 dark:text-white">{{ $sale->customer_name ?? 'Cliente Geral' }}</span>
                                            <span class="text-xs text-gray-400">{{ $sale->user->name ?? 'N/A' }}</span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-2 text-gray-500 dark:text-gray-400">
                                        {{ \Carbon\Carbon::parse($sale->sale_date)->format('d/m/Y H:i') }}</td>
                                    <td class="py-4 px-2">
                                        <span
                                            class="px-2 py-1 rounded-lg bg-gray-100 dark:bg-gray-700 text-xs font-medium text-gray-600 dark:text-gray-300">
                                            {{ ucfirst($sale->payment_method) }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-2 text-right font-bold text-gray-800 dark:text-white">MT
                                        {{ number_format($sale->total_amount, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Navigation Cards -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mt-6">
            <a href="{{ route('reports.cashFlow') }}"
                class="p-4 bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 hover:border-blue-500 dark:hover:border-blue-500 transition-all text-center group">
                <i
                    class="mdi mdi-cash-register text-2xl text-blue-500 group-hover:scale-110 transition-transform block mb-2"></i>
                <span class="text-xs font-bold text-gray-700 dark:text-gray-300">Fluxo de Caixa</span>
            </a>
            <a href="{{ route('reports.profitLoss') }}"
                class="p-4 bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 hover:border-green-500 dark:hover:border-green-500 transition-all text-center group">
                <i
                    class="mdi mdi-finance text-2xl text-green-500 group-hover:scale-110 transition-transform block mb-2"></i>
                <span class="text-xs font-bold text-gray-700 dark:text-gray-300">DRE (Lucros/Perdas)</span>
            </a>
            <a href="{{ route('reports.customerProfitability') }}"
                class="p-4 bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 hover:border-purple-500 dark:hover:border-purple-500 transition-all text-center group">
                <i
                    class="mdi mdi-account-star text-2xl text-purple-500 group-hover:scale-110 transition-transform block mb-2"></i>
                <span class="text-xs font-bold text-gray-700 dark:text-gray-300">Rentabilidade</span>
            </a>
            <a href="{{ route('reports.abcAnalysis') }}"
                class="p-4 bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 hover:border-yellow-500 dark:hover:border-yellow-500 transition-all text-center group">
                <i
                    class="mdi mdi-chart-bubble text-2xl text-yellow-500 group-hover:scale-110 transition-transform block mb-2"></i>
                <span class="text-xs font-bold text-gray-700 dark:text-gray-300">Análise ABC</span>
            </a>
            <a href="{{ route('reports.inventory') }}"
                class="p-4 bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 hover:border-red-500 dark:hover:border-red-500 transition-all text-center group">
                <i
                    class="mdi mdi-package-variant text-2xl text-red-500 group-hover:scale-110 transition-transform block mb-2"></i>
                <span class="text-xs font-bold text-gray-700 dark:text-gray-300">Estoque</span>
            </a>
            <a href="{{ route('reports.periodComparison') }}"
                class="p-4 bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 hover:border-cyan-500 dark:hover:border-cyan-500 transition-all text-center group">
                <i class="mdi mdi-compare text-2xl text-cyan-500 group-hover:scale-110 transition-transform block mb-2"></i>
                <span class="text-xs font-bold text-gray-700 dark:text-gray-300">Comparativo</span>
            </a>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Sales Trend Chart
                const salesCtx = document.getElementById('salesTrendChart').getContext('2d');
                new Chart(salesCtx, {
                    type: 'line',
                    data: {
                        labels: @json($salesChartLabels),
                        datasets: [
                            {
                                label: 'Receita',
                                data: @json($salesChartData),
                                borderColor: '#3B82F6',
                                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                fill: true,
                                tension: 0.4,
                                borderWidth: 3,
                                pointRadius: 4,
                                pointBackgroundColor: '#3B82F6'
                            },
                            {
                                label: 'Lucro',
                                data: @json($profitChartData),
                                borderColor: '#10B981',
                                backgroundColor: 'transparent',
                                fill: false,
                                tension: 0.4,
                                borderWidth: 3,
                                pointRadius: 4,
                                pointBackgroundColor: '#10B981'
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                mode: 'index',
                                intersect: false,
                                padding: 12,
                                backgroundColor: 'rgba(17, 24, 39, 0.9)',
                                titleFont: { size: 14, weight: 'bold' },
                                bodyFont: { size: 13 },
                                callbacks: {
                                    label: function (context) {
                                        return context.dataset.label + ': MT ' + context.parsed.y.toLocaleString();
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { color: 'rgba(156, 163, 175, 0.1)', drawBorder: false },
                                ticks: {
                                    callback: value => 'MT ' + value.toLocaleString(),
                                    font: { size: 11 },
                                    color: '#9CA3AF'
                                }
                            },
                            x: {
                                grid: { display: false },
                                ticks: { font: { size: 11 }, color: '#9CA3AF' }
                            }
                        }
                    }
                });

                // Payment Method Chart
                const paymentCtx = document.getElementById('paymentMethodChart').getContext('2d');
                new Chart(paymentCtx, {
                    type: 'doughnut',
                    data: {
                        labels: @json($paymentMethodLabels),
                        datasets: [{
                            data: @json($paymentMethodAmountData),
                            backgroundColor: ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6'],
                            borderWidth: 0,
                            hoverOffset: 10
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '75%',
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                padding: 12,
                                backgroundColor: 'rgba(17, 24, 39, 0.9)',
                                callbacks: {
                                    label: function (context) {
                                        return context.label + ': MT ' + context.parsed.toLocaleString();
                                    }
                                }
                            }
                        }
                    }
                });
            });
        </script>
    @endpush
@endsection