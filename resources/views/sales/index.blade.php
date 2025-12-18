@extends('layouts.app')

@section('title', 'Vendas')

@section('content')
    <div class="w-full">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Total Sales -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border-l-4 border-orange-500">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Vendas Totais</p>
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-1">MZN
                            {{ number_format($totalSalesAmount, 2) }}</h3>
                        <p class="text-xs text-green-500 flex items-center">
                            <i class="mdi mdi-trending-up mr-1"></i>
                            Total acumulado
                        </p>
                    </div>
                    <div class="p-3 bg-orange-50 dark:bg-orange-900/20 rounded-full">
                        <i class="mdi mdi-cash-multiple text-orange-500 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Today Sales -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border-l-4 border-green-500">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Vendas Hoje</p>
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-1">MZN
                            {{ number_format($todaySalesAmount, 2) }}</h3>
                        <p class="text-xs text-green-500 flex items-center">
                            <i class="mdi mdi-clock mr-1"></i>
                            Hoje
                        </p>
                    </div>
                    <div class="p-3 bg-green-50 dark:bg-green-900/20 rounded-full">
                        <i class="mdi mdi-calendar-today text-green-500 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Total Transactions -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border-l-4 border-blue-500">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Total Transações</p>
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-1">{{ $totalSales }}</h3>
                        <p class="text-xs text-blue-500 flex items-center">
                            <i class="mdi mdi-chart-line mr-1"></i>
                            Transações realizadas
                        </p>
                    </div>
                    <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-full">
                        <i class="mdi mdi-receipt text-blue-500 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Pending Sales -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border-l-4 border-yellow-500">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Pendentes</p>
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-1">{{ $pendingSalesCount }}</h3>
                        <p class="text-xs text-yellow-500 flex items-center">
                            <i class="mdi mdi-alert-circle mr-1"></i>
                            Aguardando processamento
                        </p>
                    </div>
                    <div class="p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-full">
                        <i class="mdi mdi-clock-alert text-yellow-500 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sales Table -->
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <!-- Header -->
            <div
                class="p-6 border-b border-gray-200 dark:border-gray-700 flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="flex items-center gap-3">
                    <h4 class="text-lg font-bold text-gray-800 dark:text-white">Histórico de Vendas</h4>
                    <span
                        class="px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300">
                        {{ $totalSales }} vendas
                    </span>
                </div>

                <div class="flex items-center gap-3 flex-wrap">
                    <!-- Search -->
                    <div class="relative w-full md:w-64">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="mdi mdi-magnify text-gray-400"></i>
                        </span>
                        <input type="text" id="salesSearch"
                            class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm"
                            placeholder="Pesquisar vendas...">
                    </div>

                    <!-- New Sale Button -->
                    <a href="{{ route('pos.index') }}"
                        class="px-4 py-2 rounded-lg bg-orange-600 hover:bg-orange-700 text-white font-medium transition-colors flex items-center gap-2">
                        <i class="mdi mdi-plus"></i>
                        Nova Venda
                    </a>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-gray-50 dark:bg-gray-900/50 border-b border-gray-200 dark:border-gray-700 text-xs uppercase text-gray-500 dark:text-gray-400 font-semibold">
                            <th class="px-6 py-3">ID</th>
                            <th class="px-6 py-3">Data</th>
                            <th class="px-6 py-3">Total</th>
                            <th class="px-6 py-3">Método de Pagamento</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3 text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($sales as $sale)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="font-medium text-orange-600 dark:text-orange-400">
                                        #{{ str_pad($sale->id, 5, '0', STR_PAD_LEFT) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <span class="p-2 bg-orange-50 dark:bg-orange-900/20 rounded">
                                            <i class="mdi mdi-calendar text-orange-500"></i>
                                        </span>
                                        <div>
                                            <div class="font-medium text-gray-900 dark:text-white">
                                                {{ \Carbon\Carbon::parse($sale->sale_date)->format('d/m/Y') }}
                                            </div>
                                            <small class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ \Carbon\Carbon::parse($sale->sale_date)->format('H:i') }}
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900 dark:text-white">MZN
                                        {{ number_format($sale->total_amount, 2) }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <span class="p-2 bg-green-50 dark:bg-green-900/20 rounded">
                                            <i class="mdi {{ get_payment_icon_mdi($sale->payment_method) }} text-green-500"></i>
                                        </span>
                                        <span
                                            class="font-medium text-gray-700 dark:text-gray-300">{{ ucfirst($sale->payment_method) }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="{{ get_status_class_staradmins($sale->status) }} px-3 py-1 rounded-full text-xs font-medium">
                                        <i class="mdi mdi-circle-medium mr-1"></i>
                                        {{ ucfirst($sale->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('sales.show', $sale->id) }}"
                                            class="p-2 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 dark:bg-blue-900/20 dark:text-blue-400 dark:hover:bg-blue-900/40 transition-colors"
                                            title="Ver Detalhes">
                                            <i class="mdi mdi-eye"></i>
                                        </a>
                                        <button onclick="window.printSaleRecibo({{ $sale->id }})"
                                            class="p-2 rounded-lg bg-cyan-50 text-cyan-600 hover:bg-cyan-100 dark:bg-cyan-900/20 dark:text-cyan-400 dark:hover:bg-cyan-900/40 transition-colors"
                                            title="Imprimir">
                                            <i class="mdi mdi-printer"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-500 dark:text-gray-400">
                                        <i class="mdi mdi-cash-remove text-6xl mb-3 opacity-50"></i>
                                        <p class="text-lg font-medium mb-1">Nenhuma venda encontrada</p>
                                        <p class="text-sm">As vendas realizadas aparecerão aqui</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div
                class="p-6 border-t border-gray-200 dark:border-gray-700 flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    Mostrando <span class="font-medium text-gray-700 dark:text-gray-300">{{ $sales->firstItem() }}</span>
                    até
                    <span class="font-medium text-gray-700 dark:text-gray-300">{{ $sales->lastItem() }}</span> de
                    <span class="font-medium text-gray-700 dark:text-gray-300">{{ $sales->total() }}</span> registros
                </div>
                {{ $sales->links() }}
            </div>
        </div>
    </div>
@endsection