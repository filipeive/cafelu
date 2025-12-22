@extends('layouts.app')

@section('title', 'Vendas Mensais')

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
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Vendas Mensais</h1>
                    <p class="text-gray-500 dark:text-gray-400">Visão histórica do faturamento mês a mês.</p>
                </div>
            </div>
        </div>

        <!-- Chart -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-6">Evolução Mensal</h3>
            <div class="h-80">
                <canvas id="monthlySalesChart"></canvas>
            </div>
        </div>

        <!-- Table -->
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white">Histórico de Faturamento</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-xs font-bold text-gray-400 uppercase tracking-wider bg-gray-50 dark:bg-gray-700/50">
                            <th class="py-4 px-6">Mês/Ano</th>
                            <th class="py-4 px-6">Total Faturado</th>
                            <th class="py-4 px-6">Crescimento</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                        @foreach($sales as $index => $sale)
                            <tr class="text-sm hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                <td class="py-4 px-6 font-medium text-gray-800 dark:text-white">
                                    {{ \Carbon\Carbon::parse($sale->month . '-01')->format('F Y') }}
                                </td>
                                <td class="py-4 px-6 font-bold text-gray-800 dark:text-white">MT
                                    {{ number_format($sale->total, 2) }}</td>
                                <td class="py-4 px-6">
                                    @if(isset($sales[$index + 1]))
                                        @php 
                                                                        $prevTotal = $sales[$index + 1]->total;
                                            $growth = $prevTotal > 0 ? (($sale->total - $prevTotal) / $prevTotal) * 100 : 0;
                                        @endphp
                                        <span class="flex items-center gap-1 font-bold {{ $growth >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                                <i class="mdi {{ $growth >= 0 ? 'mdi-arrow-up' : 'mdi-arrow-down' }}"></i>
                                            {{ abs(number_format($growth, 1)) }}%
                                            </span>
                                    @else
                                        <span class="text-gray-400">---</span>
                                    @endif
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
                const ctx = document.getElementById('monthlySalesChart').getContext('2d');
                const data = @json($sales->reverse()->values());
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.map(s => s.month),
                        datasets: [{
                            label: 'Faturamento (MT)',
                            data: data.map(s => s.total),
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
