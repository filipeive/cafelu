@extends('layouts.app')

@section('title', 'Vendas por Método de Pagamento')

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
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Vendas por Método de Pagamento</h1>
                    <p class="text-gray-500 dark:text-gray-400">Análise da preferência de pagamento dos clientes.</p>
                </div>
            </div>

            <form action="{{ route('reports.salesByPaymentMethod') }}" method="GET"
                class="flex flex-wrap items-center gap-3">
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
            <!-- Payment Chart -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-6">Distribuição de Pagamentos</h3>
                <div class="h-80">
                    <canvas id="paymentChart"></canvas>
                </div>
            </div>

            <!-- Detailed Table -->
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white">Resumo por Método</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr
                                class="text-xs font-bold text-gray-400 uppercase tracking-wider bg-gray-50 dark:bg-gray-700/50">
                                <th class="py-4 px-6">Método</th>
                                <th class="py-4 px-6">Qtd Vendas</th>
                                <th class="py-4 px-6">Receita Total</th>
                                <th class="py-4 px-6">Ticket Médio</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                            @foreach($sales as $method)
                                <tr class="text-sm hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                    <td class="py-4 px-6">
                                        <span
                                            class="font-bold text-gray-800 dark:text-white">{{ ucfirst($method->payment_method) }}</span>
                                    </td>
                                    <td class="py-4 px-6 text-gray-600 dark:text-gray-400">{{ $method->sales_count }}</td>
                                    <td class="py-4 px-6 font-medium text-gray-800 dark:text-white">MT
                                        {{ number_format($method->total_revenue, 2) }}</td>
                                    <td class="py-4 px-6 text-gray-500">
                                        MT {{ number_format($method->total_revenue / $method->sales_count, 2) }}
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
                const ctx = document.getElementById('paymentChart').getContext('2d');
                new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: @json($sales->pluck('payment_method')->map(fn($m) => ucfirst($m))),
                        datasets: [{
                            data: @json($sales->pluck('total_revenue')),
                            backgroundColor: ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6'],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { position: 'bottom' } }
                    }
                });
            });
        </script>
    @endpush
@endsection