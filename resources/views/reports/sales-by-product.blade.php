@extends('layouts.app')

@section('title', 'Vendas por Produto')

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
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Vendas por Produto</h1>
                    <p class="text-gray-500 dark:text-gray-400">Desempenho individual de cada item do menu.</p>
                </div>
            </div>

            <form action="{{ route('reports.salesByProduct') }}" method="GET" class="flex flex-wrap items-center gap-3">
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

        <!-- Performance Chart -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-6">Top 10 Produtos por Receita</h3>
            <div class="h-80">
                <canvas id="productPerformanceChart"></canvas>
            </div>
        </div>

        <!-- Detailed Table -->
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white">Ranking Geral de Produtos</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-xs font-bold text-gray-400 uppercase tracking-wider bg-gray-50 dark:bg-gray-700/50">
                            <th class="py-4 px-6">Produto</th>
                            <th class="py-4 px-6">Qtd Vendida</th>
                            <th class="py-4 px-6">Receita Total</th>
                            <th class="py-4 px-6">% da Receita</th>
                            <th class="py-4 px-6">Markup Médio</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                        @php $totalRevenue = $sales->sum('total_revenue'); @endphp
                        @foreach($sales as $product)
                            <tr class="text-sm hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                <td class="py-4 px-6 font-bold text-gray-800 dark:text-white">{{ $product->name }}</td>
                                <td class="py-4 px-6 text-gray-600 dark:text-gray-400">{{ $product->quantity_sold }}</td>
                                <td class="py-4 px-6 font-medium text-gray-800 dark:text-white">MT
                                    {{ number_format($product->total_revenue, 2) }}</td>
                                <td class="py-4 px-6">
                                    @php $percentage = $totalRevenue > 0 ? ($product->total_revenue / $totalRevenue) * 100 : 0; @endphp
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="flex-1 h-1.5 w-16 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                                            <div class="h-full bg-blue-500 rounded-full" style="width: {{ $percentage }}%">
                                            </div>
                                        </div>
                                        <span
                                            class="text-xs font-bold text-gray-500">{{ number_format($percentage, 1) }}%</span>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    @php 
                                                                $markup = $product->purchase_price > 0 ?
                                        (($product->selling_price - $product->purchase_price) / $product->purchase_price) * 100 : 0;
                                    @endphp
                                    <span class="text-xs font-medium text-gray-500">{{ number_format($markup, 1) }}%</span>
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
            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('productPerformanceChart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: @json($sales->take(10)->pluck('name')),
                        datasets: [{
                            label: 'Receita (MT)',
                            data: @json($sales->take(10)->pluck('total_revenue')),
                            backgroundColor: '#3B82F6',
                            borderRadius: 8
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            x: { beginAtZero: true, ticks: { callback: v => 'MT ' + v.toLocaleString() } },
                            y: { grid: { display: false } }
                        }
                    }
                });
            });
        </script>
    @endpush
@endsection