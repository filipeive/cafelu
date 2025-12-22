@extends('layouts.app')

@section('title', 'Detalhes da Dívida')

@section('content')
    <div class="p-6 space-y-6">
        <!-- Header -->
        <div
            class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="flex items-center gap-4">
                <a href="{{ route('debts.index') }}"
                    class="p-2 bg-gray-50 dark:bg-gray-700 rounded-xl text-gray-500 hover:text-gray-700 dark:hover:text-gray-200 transition-all">
                    <i class="mdi mdi-arrow-left text-xl"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Dívida de {{ $debt->customer_name }}</h1>
                    <p class="text-gray-500 dark:text-gray-400">Origem: Pedido #{{ $debt->order_id }} em
                        {{ $debt->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                @if($debt->status !== 'paid')
                    <button
                        @click="$dispatch('open-pay-modal', { id: {{ $debt->id }}, amount: {{ $debt->remaining_amount }}, name: '{{ $debt->customer_name }}' })"
                        class="px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl transition-all shadow-lg shadow-green-600/20 flex items-center gap-2">
                        <i class="mdi mdi-cash-register"></i> Registrar Pagamento
                    </button>
                @endif
                <form action="{{ route('debts.destroy', $debt->id) }}" method="POST"
                    onsubmit="return confirm('Tem certeza que deseja remover este registro de dívida?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="p-2.5 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-xl transition-all">
                        <i class="mdi mdi-trash-can-outline text-xl"></i>
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Debt Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Summary Card -->
                <div
                    class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Valor Total</p>
                            <p class="text-2xl font-bold text-gray-800 dark:text-white">MT
                                {{ number_format($debt->total_amount, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Valor Pago</p>
                            <p class="text-2xl font-bold text-green-600 dark:text-green-400">MT
                                {{ number_format($debt->total_amount - $debt->remaining_amount, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Saldo Devedor</p>
                            <p class="text-2xl font-bold text-red-600 dark:text-red-400">MT
                                {{ number_format($debt->remaining_amount, 2) }}</p>
                        </div>
                    </div>

                    <div class="mt-8 pt-8 border-t border-gray-100 dark:border-gray-700">
                        <h4 class="font-bold text-gray-800 dark:text-white mb-4">Itens do Pedido Original</h4>
                        <div class="space-y-3">
                            @if($debt->sale && $debt->sale->saleItems)
                                @foreach($debt->sale->saleItems as $item)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 rounded-lg bg-white dark:bg-gray-800 flex items-center justify-center border border-gray-100 dark:border-gray-600">
                                                <i class="mdi mdi-package-variant text-gray-400"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-gray-800 dark:text-white">
                                                    {{ $item->product->name ?? 'Produto Removido' }}</p>
                                                <p class="text-xs text-gray-500">{{ $item->quantity }} x MT
                                                    {{ number_format($item->unit_price, 2) }}</p>
                                            </div>
                                        </div>
                                        <p class="text-sm font-bold text-gray-800 dark:text-white">MT
                                            {{ number_format($item->total_price, 2) }}</p>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-sm text-gray-500 italic">Detalhes dos itens não disponíveis.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Payment History -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white">Histórico de Pagamentos</h3>
                    </div>
                    <div class="p-6">
                        @if($debt->payments->count() > 0)
                            <div class="relative">
                                <div class="absolute top-0 bottom-0 left-4 w-0.5 bg-gray-100 dark:bg-gray-700"></div>
                                <div class="space-y-8">
                                    @foreach($debt->payments->sortByDesc('payment_date') as $payment)
                                        <div class="relative pl-10">
                                            <div
                                                class="absolute left-2.5 top-1.5 w-3.5 h-3.5 rounded-full bg-green-500 border-4 border-white dark:border-gray-800">
                                            </div>
                                            <div class="flex items-center justify-between mb-1">
                                                <p class="text-sm font-bold text-gray-800 dark:text-white">Pagamento Recebido</p>
                                                <p class="text-xs text-gray-500">{{ $payment->payment_date->format('d/m/Y') }}</p>
                                            </div>
                                            <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-2xl">
                                                <div class="flex items-center justify-between">
                                                    <div>
                                                        <p class="text-lg font-bold text-green-600 dark:text-green-400">MT
                                                            {{ number_format($payment->amount, 2) }}</p>
                                                        <p class="text-xs text-gray-500 uppercase font-bold">
                                                            {{ $payment->payment_method }}</p>
                                                    </div>
                                                    @if($payment->notes)
                                                        <p class="text-sm text-gray-600 dark:text-gray-400 italic">
                                                            "{{ $payment->notes }}"</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <i class="mdi mdi-history text-4xl text-gray-300 mb-2 block"></i>
                                <p class="text-gray-500">Nenhum pagamento registrado ainda.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar Info -->
            <div class="space-y-6">
                <!-- Customer Card -->
                <div
                    class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                    <h4 class="font-bold text-gray-800 dark:text-white mb-4">Informações do Cliente</h4>
                    <div class="flex items-center gap-4 mb-6">
                        <div
                            class="w-16 h-16 rounded-2xl bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center text-orange-600 text-2xl font-bold">
                            {{ substr($debt->customer_name, 0, 1) }}
                        </div>
                        <div>
                            <p class="text-lg font-bold text-gray-800 dark:text-white">{{ $debt->customer_name }}</p>
                            <p class="text-sm text-gray-500">{{ $debt->user ? 'Cliente Registrado' : 'Cliente Avulso' }}</p>
                        </div>
                    </div>

                    @if($debt->user)
                        <div class="space-y-3">
                            <div class="flex items-center gap-3 text-sm text-gray-600 dark:text-gray-400">
                                <i class="mdi mdi-email-outline"></i>
                                {{ $debt->user->email }}
                            </div>
                            @if($debt->user->phone)
                                <div class="flex items-center gap-3 text-sm text-gray-600 dark:text-gray-400">
                                    <i class="mdi mdi-phone-outline"></i>
                                    {{ $debt->user->phone }}
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Notes Card -->
                <div
                    class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                    <h4 class="font-bold text-gray-800 dark:text-white mb-2">Observações</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                        {{ $debt->notes ?: 'Nenhuma observação registrada.' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    @include('debts._payment_modal')
@endsection