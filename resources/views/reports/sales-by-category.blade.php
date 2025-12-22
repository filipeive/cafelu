@extends('layouts.app')

@section('title', 'Vendas por Categoria')

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
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Vendas por Categoria</h1>
                    <p class="text-gray-500 dark:text-gray-400">Distribuição de vendas entre as categorias do menu.</p>
                </div>
            </div>

            <form action="{{ route('reports.salesByCategory') }}" method="GET" class="flex flex-wrap items-center gap-3">
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

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Category Chart -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-6">Participação por Categoria</h3>
                <div class="h-80">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>

            <!-- Detailed Table -->
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white">Ranking de Categorias</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr
                                class="text-xs font-bold text-gray-400 uppercase tracking-wider bg-gray-50 dark:bg-gray-700/50">
                                <th class="py-4 px-6">Categoria</th>
                                <th class="py-4 px-6">Qtd Itens</th>
                                <th class="py-4 px-6">Receita Total</th>
                                <th class="py-4 px-6">% do Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                            @php $totalRevenue = $sales->sum('total_revenue'); @endphp
                            @foreach($sales as $category)
                                <tr class="text-sm hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                    <td class="py-4 px-6 font-bold text-gray-800 dark:text-white">{{ $category->name }}</td>
                                    <td class="py-4 px-6 text-gray-600 dark:text-gray-400">{{ $category->quantity_sold }}</td>
                                    <td class="py-4 px-6 font-medium text-gray-800 dark:text-white">MT
                                        {{ number_format($category->total_revenue, 2) }}</td>
                                    <td class="py-4 px-6">
                                        @php $percentage = $totalRevenue > 0 ? ($category->total_revenue / $totalRevenue) * 100 : 0; @endphp
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
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const ctx = document.getElementById('categoryChart').getContext('2d');
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: @json($sales->pluck('name')),
                        datasets: [{
                            data: @json($sales->pluck('total_revenue')),
                            backgroundColor: ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#EC4899'],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '70%',
                        plugins: { legend: { position: 'bottom' } }
                    }
                });
            });
        </script>
    @endpush
@endsection