@extends('layouts.app')

@section('title', 'Análise ABC de Produtos')

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
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Análise ABC de Produtos</h1>
                    <p class="text-gray-500 dark:text-gray-400">Classificação por impacto na receita total.</p>
                </div>
            </div>

            <form action="{{ route('reports.abcAnalysis') }}" method="GET" class="flex flex-wrap items-center gap-3">
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

        <!-- ABC Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Classe A -->
            <div
                class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border-l-4 border-l-green-500 border-gray-100 dark:border-gray-700">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white">Classe A</h3>
                        <p class="text-xs text-gray-500">80% da Receita</p>
                    </div>
                    <span
                        class="w-10 h-10 rounded-xl bg-green-100 dark:bg-green-900/30 flex items-center justify-center text-green-600 font-black">A</span>
                </div>
                <div class="space-y-2">
                    <p class="text-2xl font-bold text-gray-800 dark:text-white">MT
                        {{ number_format($abcStats['A']->sum('total_revenue'), 2) }}</p>
                    <p class="text-xs text-gray-400">{{ $abcStats['A']->count() }} produtos responsáveis por
                        {{ number_format($abcStats['A']->sum('revenue_percentage'), 1) }}% do total.</p>
                </div>
            </div>

            <!-- Classe B -->
            <div
                class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border-l-4 border-l-yellow-500 border-gray-100 dark:border-gray-700">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white">Classe B</h3>
                        <p class="text-xs text-gray-500">15% da Receita</p>
                    </div>
                    <span
                        class="w-10 h-10 rounded-xl bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center text-yellow-600 font-black">B</span>
                </div>
                <div class="space-y-2">
                    <p class="text-2xl font-bold text-gray-800 dark:text-white">MT
                        {{ number_format($abcStats['B']->sum('total_revenue'), 2) }}</p>
                    <p class="text-xs text-gray-400">{{ $abcStats['B']->count() }} produtos responsáveis por
                        {{ number_format($abcStats['B']->sum('revenue_percentage'), 1) }}% do total.</p>
                </div>
            </div>

            <!-- Classe C -->
            <div
                class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border-l-4 border-l-red-500 border-gray-100 dark:border-gray-700">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white">Classe C</h3>
                        <p class="text-xs text-gray-500">5% da Receita</p>
                    </div>
                    <span
                        class="w-10 h-10 rounded-xl bg-red-100 dark:bg-red-900/30 flex items-center justify-center text-red-600 font-black">C</span>
                </div>
                <div class="space-y-2">
                    <p class="text-2xl font-bold text-gray-800 dark:text-white">MT
                        {{ number_format($abcStats['C']->sum('total_revenue'), 2) }}</p>
                    <p class="text-xs text-gray-400">{{ $abcStats['C']->count() }} produtos responsáveis por
                        {{ number_format($abcStats['C']->sum('revenue_percentage'), 1) }}% do total.</p>
                </div>
            </div>
        </div>

        <!-- ABC Chart -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-6">Curva de Pareto (Acumulado)</h3>
            <div class="h-80">
                <canvas id="paretoChart"></canvas>
            </div>
        </div>

        <!-- Detailed Table -->
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white">Detalhamento por Produto</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-xs font-bold text-gray-400 uppercase tracking-wider bg-gray-50 dark:bg-gray-700/50">
                            <th class="py-4 px-6">Classe</th>
                            <th class="py-4 px-6">Produto</th>
                            <th class="py-4 px-6">Vendas</th>
                            <th class="py-4 px-6">Receita</th>
                            <th class="py-4 px-6">% Receita</th>
                            <th class="py-4 px-6">% Acumulada</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                        @foreach($abcProducts as $product)
                                        <tr class="text-sm hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                            <td class="py-4 px-6">
                                                <span
                                                    class="px-2 py-1 rounded text-xs font-black 
                                                    {{ $product->abc_classification == 'A' ? 'bg-green-100 text-green-700' :
                            ($product->abc_classification == 'B' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                                    {{ $product->abc_classification }}
                                                </span>
                                            </td>
                                            <td class="py-4 px-6 font-bold text-gray-800 dark:text-white">{{ $product->name }}</td>
                                            <td class="py-4 px-6 text-gray-600 dark:text-gray-400">{{ $product->total_quantity }}</td>
                                            <td class="py-4 px-6 font-medium text-gray-800 dark:text-white">MT
                                                {{ number_format($product->total_revenue, 2) }}</td>
                                            <td class="py-4 px-6 text-gray-500">{{ number_format($product->revenue_percentage, 1) }}%</td>
                                            <td class="py-4 px-6">
                                                <div class="flex items-center gap-2">
                                                    <div
                                                        class="flex-1 h-1.5 w-24 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                                                        <div class="h-full bg-blue-500 rounded-full"
                                                            style="width: {{ $product->cumulative_percentage }}%"></div>
                                                    </div>
                                                    <span
                                                        class="text-xs font-bold text-gray-500">{{ number_format($product->cumulative_percentage, 1) }}%</span>
                                                </div>
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
                const ctx = document.getElementById('paretoChart').getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: @json($abcProducts->pluck('name')),
                        datasets: [{
                            label: '% Acumulada',
                            data: @json($abcProducts->pluck('cumulative_percentage')),
                            borderColor: '#3B82F6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            fill: true,
                            tension: 0.4,
                            pointRadius: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 100,
                                ticks: { callback: value => value + '%' }
                            },
                            x: { display: false }
                        }
                    }
                });
            });
        </script>
    @endpush
@endsection