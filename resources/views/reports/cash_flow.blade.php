@extends('layouts.app')

@section('title', 'Fluxo de Caixa')

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
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Fluxo de Caixa</h1>
                    <p class="text-gray-500 dark:text-gray-400">Análise de entradas e saídas de recursos.</p>
                </div>
            </div>

            <form action="{{ route('reports.cashFlow') }}" method="GET" class="flex flex-wrap items-center gap-3">
                <div
                    class="flex items-center gap-2 bg-gray-50 dark:bg-gray-700/50 p-2 rounded-xl border border-gray-200 dark:border-gray-600">
                    <input type="date" name="date_from" value="{{ $dateFrom }}"
                        class="bg-transparent border-none text-sm focus:ring-0 text-gray-700 dark:text-gray-200">
                    <span class="text-gray-400">até</span>
                    <input type="date" name="date_to" value="{{ $dateTo }}"
                        class="bg-transparent border-none text-sm focus:ring-0 text-gray-700 dark:text-gray-200">
                </div>
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl font-medium transition-all">
                    Filtrar
                </button>
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total de Entradas</p>
                <h3 class="text-2xl font-bold text-green-600 mt-1">MT {{ number_format($totalInflows, 2) }}</h3>
                <div class="mt-4 space-y-2">
                    @foreach($cashInflows as $method => $amount)
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-500">{{ ucfirst($method) }}</span>
                            <span class="font-medium text-gray-700 dark:text-gray-300">MT {{ number_format($amount, 2) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total de Saídas</p>
                <h3 class="text-2xl font-bold text-red-600 mt-1">MT {{ number_format($totalOutflows, 2) }}</h3>
                <div class="mt-4 space-y-2">
                    @foreach($cashOutflows->take(3) as $category => $amount)
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-500 truncate mr-2">{{ $category }}</span>
                            <span class="font-medium text-gray-700 dark:text-gray-300">MT {{ number_format($amount, 2) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Saldo Líquido</p>
                <h3 class="text-2xl font-bold {{ $netCashFlow >= 0 ? 'text-blue-600' : 'text-red-600' }} mt-1">
                    MT {{ number_format($netCashFlow, 2) }}
                </h3>
                <p class="text-xs text-gray-400 mt-4">Resultado do período selecionado</p>
            </div>
        </div>

        <!-- Chart -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-6">Evolução Diária do Fluxo</h3>
            <div class="h-80">
                <canvas id="cashFlowChart"></canvas>
            </div>
        </div>

        <!-- Detailed Table -->
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white">Detalhamento Diário</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-xs font-bold text-gray-400 uppercase tracking-wider bg-gray-50 dark:bg-gray-700/50">
                            <th class="py-4 px-6">Data</th>
                            <th class="py-4 px-6">Entradas</th>
                            <th class="py-4 px-6">Saídas</th>
                            <th class="py-4 px-6">Saldo</th>
                            <th class="py-4 px-6">Vendas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                        @foreach($dailyCashFlow as $day)
                            <tr class="text-sm hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                <td class="py-4 px-6 font-medium text-gray-800 dark:text-white">{{ $day['date_full'] }}</td>
                                <td class="py-4 px-6 text-green-600 font-medium">MT {{ number_format($day['inflow'], 2) }}</td>
                                <td class="py-4 px-6 text-red-600 font-medium">MT {{ number_format($day['outflow'], 2) }}</td>
                                <td class="py-4 px-6 font-bold {{ $day['net'] >= 0 ? 'text-blue-600' : 'text-red-600' }}">
                                    MT {{ number_format($day['net'], 2) }}
                                </td>
                                <td class="py-4 px-6 text-gray-500 dark:text-gray-400">{{ $day['sales_count'] }}</td>
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
                const ctx = document.getElementById('cashFlowChart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: @json(collect($dailyCashFlow)->pluck('date')),
                        datasets: [
                            {
                                label: 'Entradas',
                                data: @json(collect($dailyCashFlow)->pluck('inflow')),
                                backgroundColor: '#10B981',
                                borderRadius: 4
                            },
                            {
                                label: 'Saídas',
                                data: @json(collect($dailyCashFlow)->pluck('outflow')),
                                backgroundColor: '#EF4444',
                                borderRadius: 4
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'top', align: 'end' },
                            tooltip: { mode: 'index', intersect: false }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { color: 'rgba(156, 163, 175, 0.1)' },
                                ticks: { callback: value => 'MT ' + value.toLocaleString() }
                            },
                            x: { grid: { display: false } }
                        }
                    }
                });
            });
        </script>
    @endpush
@endsection