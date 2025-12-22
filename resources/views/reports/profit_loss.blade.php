@extends('layouts.app')

@section('title', 'Demonstrativo de Resultados (DRE)')

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
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Lucros e Perdas (DRE)</h1>
                    <p class="text-gray-500 dark:text-gray-400">Análise detalhada da rentabilidade operacional.</p>
                </div>
            </div>

            <form action="{{ route('reports.profitLoss') }}" method="GET" class="flex flex-wrap items-center gap-3">
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

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- DRE Table -->
            <div
                class="lg:col-span-2 bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-8 border-b pb-4">Demonstrativo de Resultados
                </h3>

                <div class="space-y-6">
                    <!-- Receita Bruta -->
                    <div class="flex justify-between items-center group">
                        <span class="text-lg font-bold text-gray-700 dark:text-gray-300">(=) RECEITA BRUTA DE VENDAS</span>
                        <span class="text-lg font-bold text-gray-800 dark:text-white">MT
                            {{ number_format($salesRevenue, 2) }}</span>
                    </div>

                    <!-- CPV -->
                    <div class="flex justify-between items-center text-red-500">
                        <span class="text-sm font-medium">(-) Custo dos Produtos Vendidos (CPV)</span>
                        <span class="text-sm font-bold">MT {{ number_format($costOfGoodsSold, 2) }}</span>
                    </div>

                    <div class="h-px bg-gray-100 dark:bg-gray-700"></div>

                    <!-- Lucro Bruto -->
                    <div class="flex justify-between items-center bg-gray-50 dark:bg-gray-700/30 p-4 rounded-xl">
                        <span class="text-lg font-bold text-gray-800 dark:text-white">(=) LUCRO BRUTO</span>
                        <div class="text-right">
                            <p class="text-lg font-bold text-blue-600">MT {{ number_format($grossProfit, 2) }}</p>
                            <p class="text-xs text-gray-400">Margem Bruta: {{ number_format($grossMargin, 1) }}%</p>
                        </div>
                    </div>

                    <!-- Despesas Operacionais -->
                    <div class="space-y-3">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Despesas Operacionais</p>
                        @foreach($expensesByCategory as $category => $amount)
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-600 dark:text-gray-400">{{ $category }}</span>
                                <span class="text-red-500 font-medium">MT {{ number_format($amount, 2) }}</span>
                            </div>
                        @endforeach
                        <div
                            class="flex justify-between items-center pt-2 border-t border-dashed border-gray-200 dark:border-gray-600">
                            <span class="text-sm font-bold text-gray-700 dark:text-gray-300">Total Despesas</span>
                            <span class="text-sm font-bold text-red-600">MT
                                {{ number_format($totalOperatingExpenses, 2) }}</span>
                        </div>
                    </div>

                    <div class="h-px bg-gray-100 dark:bg-gray-700"></div>

                    <!-- Lucro Líquido -->
                    <div
                        class="flex justify-between items-center bg-blue-600 p-6 rounded-2xl text-white shadow-lg shadow-blue-200 dark:shadow-none">
                        <span class="text-xl font-bold">(=) LUCRO LÍQUIDO DO PERÍODO</span>
                        <div class="text-right">
                            <p class="text-2xl font-black">MT {{ number_format($operatingProfit, 2) }}</p>
                            <p class="text-sm opacity-80 font-medium">Margem Líquida:
                                {{ number_format($operatingMargin, 1) }}%</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Side Charts/Stats -->
            <div class="space-y-6">
                <!-- Expense Distribution -->
                <div
                    class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-6">Distribuição de Despesas</h3>
                    <div class="h-64">
                        <canvas id="expenseDistributionChart"></canvas>
                    </div>
                </div>

                <!-- Product Profitability -->
                <div
                    class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-6">Top Lucratividade</h3>
                    <div class="space-y-4">
                        @foreach($productProfitability->take(5) as $product)
                            <div class="flex justify-between items-center">
                                <div class="min-w-0">
                                    <p class="text-sm font-bold text-gray-800 dark:text-white truncate">{{ $product->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $product->quantity_sold }} vendidos</p>
                                </div>
                                <span class="text-sm font-bold text-green-600">MT
                                    {{ number_format($product->profit, 2) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const ctx = document.getElementById('expenseDistributionChart').getContext('2d');
                new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: @json($expensesByCategory->keys()),
                        datasets: [{
                            data: @json($expensesByCategory->values()),
                            backgroundColor: ['#EF4444', '#F59E0B', '#3B82F6', '#10B981', '#8B5CF6', '#EC4899'],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } }
                        }
                    }
                });
            });
        </script>
    @endpush
@endsection