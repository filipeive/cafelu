@extends('layouts.app')

@section('title', 'Relatório Especializado de Despesas')

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
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Relatório de Despesas</h1>
                    <p class="text-gray-500 dark:text-gray-400">Controle e análise de gastos operacionais.</p>
                </div>
            </div>

            <form action="{{ route('reports.expensesReport') }}" method="GET" class="flex flex-wrap items-center gap-3">
                <div
                    class="flex items-center gap-2 bg-gray-50 dark:bg-gray-700/50 p-2 rounded-xl border border-gray-200 dark:border-gray-600">
                    <input type="date" name="date_from" value="{{ $dateFrom }}"
                        class="bg-transparent border-none text-sm focus:ring-0 text-gray-700 dark:text-gray-200">
                    <span class="text-gray-400">até</span>
                    <input type="date" name="date_to" value="{{ $dateTo }}"
                        class="bg-transparent border-none text-sm focus:ring-0 text-gray-700 dark:text-gray-200">
                </div>
                <select name="category_id"
                    class="bg-gray-50 dark:bg-gray-700/50 border-gray-200 dark:border-gray-600 rounded-xl text-sm focus:ring-blue-500 text-gray-700 dark:text-gray-200">
                    <option value="all">Todas Categorias</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ $categoryId == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
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
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total de Despesas</p>
                <h3 class="text-2xl font-bold text-red-600 mt-1">MT {{ number_format($totalExpenses, 2) }}</h3>
                <div
                    class="mt-2 flex items-center gap-1 text-xs {{ $expenseGrowth <= 0 ? 'text-green-600' : 'text-red-600' }}">
                    <i class="mdi {{ $expenseGrowth <= 0 ? 'mdi-arrow-down' : 'mdi-arrow-up' }}"></i>
                    {{ abs(number_format($expenseGrowth, 1)) }}% vs anterior
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Quantidade</p>
                <h3 class="text-2xl font-bold text-gray-800 dark:text-white mt-1">{{ $expenseCount }}</h3>
                <p class="text-xs text-gray-400 mt-2">Média por despesa: MT {{ number_format($averageExpense, 2) }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Maior Categoria</p>
                @php $topCat = $expensesByCategory->first(); @endphp
                <h3 class="text-2xl font-bold text-gray-800 dark:text-white mt-1">{{ $topCat['category'] ?? 'N/A' }}</h3>
                <p class="text-xs text-gray-400 mt-2">MT {{ number_format($topCat['total'] ?? 0, 2) }}
                    ({{ number_format($topCat['percentage'] ?? 0, 1) }}%)</p>
            </div>
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Responsável Principal</p>
                @php $topUser = $expensesByUser->first(); @endphp
                <h3 class="text-2xl font-bold text-gray-800 dark:text-white mt-1">{{ $topUser['user'] ?? 'N/A' }}</h3>
                <p class="text-xs text-gray-400 mt-2">MT {{ number_format($topUser['total'] ?? 0, 2) }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Expenses by Category -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-6">Distribuição por Categoria</h3>
                <div class="h-64">
                    <canvas id="expensesCategoryChart"></canvas>
                </div>
            </div>

            <!-- Expenses Trend -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-6">Evolução Diária</h3>
                <div class="h-64">
                    <canvas id="expensesDayChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Detailed Table -->
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white">Detalhamento de Despesas</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-xs font-bold text-gray-400 uppercase tracking-wider bg-gray-50 dark:bg-gray-700/50">
                            <th class="py-4 px-6">Data</th>
                            <th class="py-4 px-6">Descrição</th>
                            <th class="py-4 px-6">Categoria</th>
                            <th class="py-4 px-6">Responsável</th>
                            <th class="py-4 px-6 text-right">Valor</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                        @foreach($expenses as $expense)
                            <tr class="text-sm hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                <td class="py-4 px-6 text-gray-500">
                                    {{ \Carbon\Carbon::parse($expense->expense_date)->format('d/m/Y') }}</td>
                                <td class="py-4 px-6 font-medium text-gray-800 dark:text-white">{{ $expense->description }}</td>
                                <td class="py-4 px-6">
                                    <span
                                        class="px-2 py-1 rounded-lg bg-red-50 dark:bg-red-900/20 text-red-600 text-xs font-medium">{{ $expense->category }}</span>
                                </td>
                                <td class="py-4 px-6 text-gray-600 dark:text-gray-400">{{ $expense->user->name ?? 'N/A' }}</td>
                                <td class="py-4 px-6 text-right font-bold text-red-600">MT
                                    {{ number_format($expense->amount, 2) }}</td>
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
                // Category Chart
                const catCtx = document.getElementById('expensesCategoryChart').getContext('2d');
                new Chart(catCtx, {
                    type: 'doughnut',
                    data: {
                        labels: @json($expensesByCategory->pluck('category')),
                        datasets: [{
                            data: @json($expensesByCategory->pluck('total')),
                            backgroundColor: ['#EF4444', '#F59E0B', '#3B82F6', '#10B981', '#8B5CF6', '#EC4899']
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { position: 'right' } }
                    }
                });

                // Daily Trend Chart
                const dayCtx = document.getElementById('expensesDayChart').getContext('2d');
                new Chart(dayCtx, {
                    type: 'bar',
                    data: {
                        labels: @json($expensesByDay->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d/m'))),
                        datasets: [{
                            label: 'Gastos',
                            data: @json($expensesByDay->pluck('total')),
                            backgroundColor: '#EF4444',
                            borderRadius: 4
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