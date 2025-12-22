@extends('layouts.app')

@section('title', 'Relatório Especializado de Vendas')

@section('content')
    <div class="p-6 space-y-6">
        <!-- Header -->
        <div
            class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="flex items-center gap-4">
                <a href="{{ route('reports.index') }}"
                    class="w-10 h-10 flex items-center justify-center rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-500 hover:text-blue-600 transition-colors">
                    <i class="mdi mdi-arrow-left text-xl"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Relatório de Vendas</h1>
                    <p class="text-gray-500 dark:text-gray-400">Análise detalhada de transações e rentabilidade.</p>
                </div>
            </div>

            <form action="{{ route('reports.sales') }}" method="GET" class="flex flex-wrap items-center gap-3">
                <div
                    class="flex items-center gap-2 bg-gray-50 dark:bg-gray-700/50 p-2 rounded-xl border border-gray-200 dark:border-gray-600">
                    <input type="date" name="date_from" value="{{ $dateFrom }}"
                        class="bg-transparent border-none text-sm focus:ring-0 text-gray-700 dark:text-gray-200">
                    <span class="text-gray-400">até</span>
                    <input type="date" name="date_to" value="{{ $dateTo }}"
                        class="bg-transparent border-none text-sm focus:ring-0 text-gray-700 dark:text-gray-200">
                </div>
                <select name="payment_method"
                    class="bg-gray-50 dark:bg-gray-700/50 border-gray-200 dark:border-gray-600 rounded-xl text-sm focus:ring-blue-500 text-gray-700 dark:text-gray-200">
                    <option value="all" {{ $paymentMethod == 'all' ? 'selected' : '' }}>Todos Métodos</option>
                    <option value="cash" {{ $paymentMethod == 'cash' ? 'selected' : '' }}>Dinheiro</option>
                    <option value="card" {{ $paymentMethod == 'card' ? 'selected' : '' }}>Cartão</option>
                    <option value="transfer" {{ $paymentMethod == 'transfer' ? 'selected' : '' }}>Transferência</option>
                    <option value="credit" {{ $paymentMethod == 'credit' ? 'selected' : '' }}>Crédito</option>
                </select>
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl font-medium transition-all">
                    Filtrar
                </button>
            </form>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Volume de Vendas</p>
                <h3 class="text-2xl font-bold text-gray-800 dark:text-white mt-1">{{ $totalSales }}</h3>
                <p class="text-xs text-gray-400 mt-2">Ticket Médio: MT {{ number_format($averageTicket, 2) }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Faturamento Bruto</p>
                <h3 class="text-2xl font-bold text-gray-800 dark:text-white mt-1">MT {{ number_format($totalRevenue, 2) }}
                </h3>
                <p class="text-xs text-gray-400 mt-2">Custo: MT {{ number_format($totalCost, 2) }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Lucro Estimado</p>
                <h3 class="text-2xl font-bold text-green-600 mt-1">MT {{ number_format($totalProfit, 2) }}</h3>
                <p class="text-xs text-gray-400 mt-2">Margem Média: {{ number_format($averageMargin, 1) }}%</p>
            </div>
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Método Principal</p>
                @php $topMethod = $salesByMethod->sortByDesc('total')->keys()->first(); @endphp
                <h3 class="text-2xl font-bold text-gray-800 dark:text-white mt-1">{{ ucfirst($topMethod ?? 'N/A') }}</h3>
                <p class="text-xs text-gray-400 mt-2">MT {{ number_format($salesByMethod[$topMethod]['total'] ?? 0, 2) }}
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Sales Trend -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-6">Evolução Diária</h3>
                <div class="h-64">
                    <canvas id="salesDayChart"></canvas>
                </div>
            </div>

            <!-- Top Products -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-6">Top 10 Produtos</h3>
                <div class="space-y-3">
                    @foreach($topProducts as $product)
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/30 rounded-xl">
                            <div class="flex items-center gap-3">
                                <span class="text-xs font-bold text-gray-400 w-4">{{ $loop->iteration }}</span>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $product['name'] }}</span>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-gray-800 dark:text-white">MT
                                    {{ number_format($product['revenue'], 2) }}</p>
                                <p class="text-[10px] text-gray-400">{{ $product['quantity'] }} vendidos</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Detailed Table -->
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white">Lista de Transações</h3>
                <div class="flex gap-2">
                    <a href="{{ route('reports.export', ['type' => 'sales', 'date_from' => $dateFrom, 'date_to' => $dateTo, 'format' => 'pdf']) }}"
                        class="p-2 text-gray-500 hover:text-red-600 transition-colors">
                        <i class="mdi mdi-file-pdf-box text-2xl"></i>
                    </a>
                    <a href="{{ route('reports.exportExcel', ['type' => 'sales', 'date_from' => $dateFrom, 'date_to' => $dateTo]) }}"
                        class="p-2 text-gray-500 hover:text-green-600 transition-colors">
                        <i class="mdi mdi-file-excel text-2xl"></i>
                    </a>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-xs font-bold text-gray-400 uppercase tracking-wider bg-gray-50 dark:bg-gray-700/50">
                            <th class="py-4 px-6">ID</th>
                            <th class="py-4 px-6">Data</th>
                            <th class="py-4 px-6">Cliente</th>
                            <th class="py-4 px-6">Método</th>
                            <th class="py-4 px-6 text-right">Total</th>
                            <th class="py-4 px-6 text-right">Lucro</th>
                            <th class="py-4 px-6 text-right">Margem</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                        @foreach($sales as $sale)
                            <tr class="text-sm hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                <td class="py-4 px-6 font-medium text-gray-800 dark:text-white">#{{ $sale->id }}</td>
                                <td class="py-4 px-6 text-gray-500">
                                    {{ \Carbon\Carbon::parse($sale->sale_date)->format('d/m/Y H:i') }}</td>
                                <td class="py-4 px-6 text-gray-700 dark:text-gray-300">
                                    {{ $sale->customer_name ?? 'Cliente Geral' }}</td>
                                <td class="py-4 px-6">
                                    <span
                                        class="px-2 py-1 rounded-lg bg-gray-100 dark:bg-gray-700 text-xs font-medium">{{ ucfirst($sale->payment_method) }}</span>
                                </td>
                                <td class="py-4 px-6 text-right font-bold">MT {{ number_format($sale->total_amount, 2) }}</td>
                                <td class="py-4 px-6 text-right text-green-600 font-medium">MT
                                    {{ number_format($sale->profit, 2) }}</td>
                                <td class="py-4 px-6 text-right">
                                    <span
                                        class="text-xs font-bold {{ $sale->margin > 30 ? 'text-green-600' : ($sale->margin > 15 ? 'text-yellow-600' : 'text-red-600') }}">
                                        {{ number_format($sale->margin, 1) }}%
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const ctx = document.getElementById('salesDayChart').getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: @json($salesByDay->keys()->map(fn($d) => \Carbon\Carbon::parse($d)->format('d/m'))),
                        datasets: [{
                            label: 'Faturamento',
                            data: @json($salesByDay->pluck('total')),
                            borderColor: '#3B82F6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, ticks: { callback: v => 'MT ' + v.toLocaleString() } },
                            x: { grid: { display: false } }
                        }
                    }
                });
            });
        </script>
    @endpush
@endsection