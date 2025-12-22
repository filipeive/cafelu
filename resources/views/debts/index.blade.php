@extends('layouts.app')

@section('title', 'Contas a Receber')

@section('content')
    <div class="p-6 space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Contas a Receber</h1>
                <p class="text-gray-500 dark:text-gray-400">Gerencie dívidas de clientes e pagamentos parciais.</p>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform">
                    <i class="mdi mdi-account-cash text-6xl text-red-600"></i>
                </div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total em Aberto</p>
                <h3 class="text-2xl font-bold text-gray-800 dark:text-white mt-1">MT {{ number_format($totalOutstanding, 2) }}</h3>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform">
                    <i class="mdi mdi-check-circle text-6xl text-green-600"></i>
                </div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Recebido</p>
                <h3 class="text-2xl font-bold text-gray-800 dark:text-white mt-1">MT {{ number_format($totalPaid, 2) }}</h3>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform">
                    <i class="mdi mdi-account-group text-6xl text-blue-600"></i>
                </div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Clientes com Dívida</p>
                <h3 class="text-2xl font-bold text-gray-800 dark:text-white mt-1">{{ $debts->where('status', '!=', 'paid')->count() }}</h3>
            </div>
        </div>

        <!-- Debts Table -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white">Lista de Dívidas</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-900/50 border-b border-gray-100 dark:border-gray-700">
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Cliente</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Data Origem</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Restante</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($debts as $debt)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center text-orange-600 font-bold">
                                            {{ substr($debt->customer_name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-800 dark:text-white">{{ $debt->customer_name }}</p>
                                            <p class="text-xs text-gray-500">Pedido #{{ $debt->order_id }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                    {{ $debt->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-800 dark:text-white">
                                    MT {{ number_format($debt->total_amount, 2) }}
                                </td>
                                <td class="px-6 py-4 text-sm font-bold text-red-600 dark:text-red-400">
                                    MT {{ number_format($debt->remaining_amount, 2) }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($debt->status === 'paid')
                                        <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">Pago</span>
                                    @elseif($debt->status === 'partial')
                                        <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">Parcial</span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">Pendente</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('debts.show', $debt->id) }}" class="p-2 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition-colors" title="Ver Detalhes">
                                            <i class="mdi mdi-eye"></i>
                                        </a>
                                        @if($debt->status !== 'paid')
                                            <button @click="$dispatch('open-pay-modal', { id: {{ $debt->id }}, amount: {{ $debt->remaining_amount }}, name: '{{ $debt->customer_name }}' })" 
                                                class="p-2 text-green-600 hover:bg-green-50 dark:hover:bg-green-900/30 rounded-lg transition-colors" title="Registrar Pagamento">
                                                <i class="mdi mdi-cash-register"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                    <i class="mdi mdi-account-cash-outline text-4xl mb-2 block"></i>
                                    Nenhuma dívida registrada.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('debts._payment_modal')
@endsection
