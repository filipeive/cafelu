@extends('layouts.app')

@section('title', 'Meus Pedidos')

@section('content')
    <div class="w-full pb-12">
        <!-- Header Section -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">Meus Pedidos 📦</h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Gerencie e acompanhe todos os seus pedidos realizados.</p>
            </div>
            <a href="{{ route('welcome') }}#menu"
                class="inline-flex items-center justify-center px-6 py-3 bg-orange-500 text-white rounded-xl font-bold hover:bg-orange-600 transition-all shadow-lg shadow-orange-500/30">
                <i class="mdi mdi-plus-circle mr-2"></i> Novo Pedido
            </a>
        </div>

        <!-- Filters -->
        <div class="mb-8 flex flex-wrap gap-2">
            <a href="{{ route('customer.orders') }}"
                class="px-4 py-2 rounded-lg text-sm font-bold transition-all {{ !request('status') ? 'bg-orange-500 text-white shadow-md' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 border border-gray-100 dark:border-gray-700 hover:bg-gray-50' }}">
                Todos
            </a>
            @foreach(['active' => 'Ativos', 'preparing' => 'Em Preparo', 'ready' => 'Prontos', 'completed' => 'Finalizados', 'canceled' => 'Cancelados'] as $status => $label)
                <a href="{{ route('customer.orders', ['status' => $status]) }}"
                    class="px-4 py-2 rounded-lg text-sm font-bold transition-all {{ request('status') === $status ? 'bg-orange-500 text-white shadow-md' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 border border-gray-100 dark:border-gray-700 hover:bg-gray-50' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <!-- Orders List -->
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-gray-50 dark:bg-gray-900/50 text-xs uppercase text-gray-500 dark:text-gray-400 font-semibold">
                            <th class="px-6 py-4">Pedido</th>
                            <th class="px-6 py-4">Data</th>
                            <th class="px-6 py-4">Itens</th>
                            <th class="px-6 py-4">Total</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($orders as $order)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                                <td class="px-6 py-4">
                                                    <span
                                                        class="font-mono text-orange-600 dark:text-orange-400 font-bold">#{{ $order->id }}</span>
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                                    {{ $order->created_at->format('d/m/Y H:i') }}
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="flex -space-x-2 overflow-hidden">
                                                        @foreach($order->items->take(3) as $item)
                                                            <div class="inline-block h-8 w-8 rounded-full ring-2 ring-white dark:ring-gray-800 bg-gray-100 dark:bg-gray-700 flex items-center justify-center overflow-hidden"
                                                                title="{{ $item->product->name }}">
                                                                @if($item->product->image)
                                                                    <img src="{{ asset('storage/' . $item->product->image) }}" alt=""
                                                                        class="h-full w-full object-cover">
                                                                @else
                                                                    <i class="mdi mdi-food text-xs text-gray-400"></i>
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                        @if($order->items->count() > 3)
                                                            <div
                                                                class="inline-block h-8 w-8 rounded-full ring-2 ring-white dark:ring-gray-800 bg-gray-200 dark:bg-gray-600 flex items-center justify-center text-[10px] font-bold text-gray-600 dark:text-gray-300">
                                                                +{{ $order->items->count() - 3 }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">
                                                    {{ number_format($order->total_amount, 2) }} MT
                                                </td>
                                                <td class="px-6 py-4">
                                                    @php
                                                        $statusClasses = [
                                                            'active' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
                                                            'preparing' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
                                                            'ready' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400',
                                                            'completed' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-400',
                                                            'paid' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                                                            'canceled' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
                                                        ];
                                                        $statusLabels = [
                                                            'active' => 'Recebido',
                                                            'preparing' => 'Preparando',
                                                            'ready' => 'Pronto',
                                                            'completed' => 'Entregue',
                                                            'paid' => 'Pago',
                                                            'canceled' => 'Cancelado',
                                                        ];
                                                    @endphp
                               <span
                                                        class="px-3 py-1 rounded-full text-[10px] font-black uppercase {{ $statusClasses[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                        {{ $statusLabels[$order->status] ?? $order->status }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 text-right">
                                                    <div class="flex justify-end gap-2">
                                                        @if($order->status === 'active')
                                                            <form action="{{ route('customer.order.cancel', $order) }}" method="POST"
                                                                onsubmit="return confirm('Tem certeza que deseja cancelar este pedido?')">
                                                                @csrf
                                                                <button type="submit"
                                                                    class="p-2 rounded-lg bg-red-50 dark:bg-red-900/20 text-red-500 hover:bg-red-500 hover:text-white transition-all"
                                                                    title="Cancelar Pedido">
                                                                    <i class="mdi mdi-close-circle"></i>
                                                                </button>
                                                            </form>
                                                        @endif

                                                        @if(!in_array($order->status, ['paid', 'completed', 'canceled']))
                                                            <button onclick="window.openPaymentModal({{ $order->id }}, {{ $order->total_amount }})"
                                                                class="p-2 rounded-lg bg-green-50 dark:bg-green-900/20 text-green-500 hover:bg-green-500 hover:text-white transition-all"
                                                                title="Pagar">
                                                                <i class="mdi mdi-cash-multiple"></i>
                                                            </button>
                                                        @endif

                                                        <form action="{{ route('customer.order.reorder', $order) }}" method="POST">
                                                            @csrf
                                                            <button type="submit"
                                                                class="p-2 rounded-lg bg-blue-50 dark:bg-blue-900/20 text-blue-500 hover:bg-blue-500 hover:text-white transition-all"
                                                                title="Repetir Pedido">
                                                                <i class="mdi mdi-refresh"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center text-gray-400">
                                        <i class="mdi mdi-cart-outline text-5xl mb-4"></i>
                                        <p class="text-lg">Nenhum pedido encontrado.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($orders->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Re-use Payment Modal from Dashboard (or move to a component) -->
    @include('customer.partials.payment-modal')
@endsection