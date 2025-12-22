@extends('layouts.app')

@section('title', 'Rentabilidade por Cliente')

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
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Rentabilidade por Cliente</h1>
                    <p class="text-gray-500 dark:text-gray-400">Identifique seus clientes mais valiosos e lucrativos.</p>
                </div>
            </div>

            <form action="{{ route('reports.customerProfitability') }}" method="GET"
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

        <!-- Top Customers Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($customerAnalysis->take(3) as $customer)
                <div
                    class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 relative overflow-hidden group">
                    <div
                        class="absolute -top-4 -right-4 w-24 h-24 bg-blue-500/5 rounded-full group-hover:scale-150 transition-transform duration-500">
                    </div>
                    <div class="flex items-center gap-4 mb-4">
                        <div
                            class="w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 font-bold text-xl">
                            {{ substr($customer['customer_name'], 0, 1) }}
                        </div>
                        <div class="min-w-0">
                            <h3 class="text-lg font-bold text-gray-800 dark:text-white truncate">
                                {{ $customer['customer_name'] }}</h3>
                            <p class="text-xs text-gray-500">{{ $customer['phone'] ?? 'Sem telefone' }}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase">Receita</p>
                            <p class="text-sm font-bold text-gray-800 dark:text-white">MT
                                {{ number_format($customer['total_revenue'], 2) }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase">Lucro</p>
                            <p class="text-sm font-bold text-green-600">MT {{ number_format($customer['total_profit'], 2) }}</p>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-50 dark:border-gray-700 flex justify-between items-center">
                        <span class="text-xs text-gray-400">{{ $customer['sales_count'] }} pedidos</span>
                        <span class="text-xs font-bold text-blue-600">Margem:
                            {{ number_format($customer['profit_margin'], 1) }}%</span>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Detailed Table -->
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white">Ranking de Rentabilidade</h3>
                <button class="text-sm text-blue-600 font-medium hover:underline">Exportar Lista</button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-xs font-bold text-gray-400 uppercase tracking-wider bg-gray-50 dark:bg-gray-700/50">
                            <th class="py-4 px-6">Cliente</th>
                            <th class="py-4 px-6">Pedidos</th>
                            <th class="py-4 px-6">Ticket Médio</th>
                            <th class="py-4 px-6">Receita Total</th>
                            <th class="py-4 px-6">Lucro Total</th>
                            <th class="py-4 px-6">Margem</th>
                            <th class="py-4 px-6">Última Compra</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                        @foreach($customerAnalysis as $customer)
                            <tr class="text-sm hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors group">
                                <td class="py-4 px-6">
                                    <div class="flex flex-col">
                                        <span
                                            class="font-bold text-gray-800 dark:text-white group-hover:text-blue-600 transition-colors">{{ $customer['customer_name'] }}</span>
                                        <span class="text-xs text-gray-400">{{ $customer['phone'] ?? '---' }}</span>
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-gray-600 dark:text-gray-400">{{ $customer['sales_count'] }}</td>
                                <td class="py-4 px-6 text-gray-600 dark:text-gray-400">MT
                                    {{ number_format($customer['average_ticket'], 2) }}</td>
                                <td class="py-4 px-6 font-medium text-gray-800 dark:text-white">MT
                                    {{ number_format($customer['total_revenue'], 2) }}</td>
                                <td class="py-4 px-6 font-bold text-green-600">MT
                                    {{ number_format($customer['total_profit'], 2) }}</td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="flex-1 h-1.5 w-16 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                                            <div class="h-full bg-blue-500 rounded-full"
                                                style="width: {{ min(100, $customer['profit_margin']) }}%"></div>
                                        </div>
                                        <span
                                            class="text-xs font-bold text-gray-500">{{ number_format($customer['profit_margin'], 1) }}%</span>
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-gray-500 dark:text-gray-400 text-xs">
                                    {{ \Carbon\Carbon::parse($customer['last_purchase'])->format('d/m/Y') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection