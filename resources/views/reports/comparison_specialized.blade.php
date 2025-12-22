@extends('layouts.app')

@section('title', 'Comparativo de Períodos Especializado')

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
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Análise Comparativa</h1>
                    <p class="text-gray-500 dark:text-gray-400">Compare o desempenho entre diferentes períodos.</p>
                </div>
            </div>

            <form action="{{ route('reports.comparisonReport') }}" method="GET" class="flex flex-wrap items-center gap-3">
                <select name="type"
                    class="bg-gray-50 dark:bg-gray-700/50 border-gray-200 dark:border-gray-600 rounded-xl text-sm focus:ring-blue-500 text-gray-700 dark:text-gray-200">
                    <option value="monthly" {{ $type == 'monthly' ? 'selected' : '' }}>Mensal</option>
                    <option value="quarterly" {{ $type == 'quarterly' ? 'selected' : '' }}>Trimestral</option>
                    <option value="yearly" {{ $type == 'yearly' ? 'selected' : '' }}>Anual</option>
                </select>
                <select name="year"
                    class="bg-gray-50 dark:bg-gray-700/50 border-gray-200 dark:border-gray-600 rounded-xl text-sm focus:ring-blue-500 text-gray-700 dark:text-gray-200">
                    @for($i = now()->year; $i >= now()->year - 2; $i--)
                        <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl font-medium transition-all">
                    Comparar
                </button>
            </form>
        </div>

        <!-- Comparison Chart -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-6">Tendência de Faturamento</h3>
            <div class="h-80">
                <canvas id="comparisonChart"></canvas>
            </div>
        </div>

        <!-- Detailed Comparison Table -->
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white">Detalhamento por Período</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-xs font-bold text-gray-400 uppercase tracking-wider bg-gray-50 dark:bg-gray-700/50">
                            <th class="py-4 px-6">Período</th>
                            <th class="py-4 px-6 text-right">Vendas</th>
                            <th class="py-4 px-6 text-right">Faturamento</th>
                            <th class="py-4 px-6 text-right">Crescimento</th>
                            <th class="py-4 px-6 text-right">Ticket Médio</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                        @foreach($comparisons as $comp)
                            <tr class="text-sm hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                <td class="py-4 px-6 font-medium text-gray-800 dark:text-white">{{ $comp['label'] }}</td>
                                <td class="py-4 px-6 text-right">{{ $comp['sales_count'] }}</td>
                                <td class="py-4 px-6 text-right font-bold">MT {{ number_format($comp['revenue'], 2) }}</td>
                                <td class="py-4 px-6 text-right">
                                    @if($comp['growth'] !== null)
                                        <span
                                            class="flex items-center justify-end gap-1 font-bold {{ $comp['growth'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                            <i class="mdi {{ $comp['growth'] >= 0 ? 'mdi-arrow-up' : 'mdi-arrow-down' }}"></i>
                                            {{ abs(number_format($comp['growth'], 1)) }}%
                                        </span>
                                    @else
                                        <span class="text-gray-400">---</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-right text-gray-500">MT {{ number_format($comp['avg_ticket'], 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Forecasts Section -->
        <div class="bg-blue-600 rounded-2xl p-8 text-white shadow-lg shadow-blue-200 dark:shadow-none">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center">
                    <i class="mdi mdi-crystal-ball text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold">Previsão para o Próximo Período</h3>
                    <p class="text-blue-100 text-sm">Baseado na tendência histórica calculada.</p>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <p class="text-blue-100 text-xs uppercase font-bold tracking-wider mb-1">Faturamento Estimado</p>
                    <h4 class="text-3xl font-black">MT {{ number_format($forecasts['revenue'], 2) }}</h4>
                </div>
                <div>
                    <p class="text-blue-100 text-xs uppercase font-bold tracking-wider mb-1">Tendência de Crescimento</p>
                    <h4 class="text-3xl font-black">{{ number_format($trends['growth_rate'], 1) }}%</h4>
                </div>
                <div>
                    <p class="text-blue-100 text-xs uppercase font-bold tracking-wider mb-1">Consistência dos Dados</p>
                    <h4 class="text-3xl font-black">{{ $trends['consistency'] }}</h4>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const ctx = document.getElementById('comparisonChart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: @json(collect($comparisons)->pluck('label')),
                        datasets: [{
                            label: 'Faturamento (MT)',
                            data: @json(collect($comparisons)->pluck('revenue')),
                            backgroundColor: '#3B82F6',
                            borderRadius: 6
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