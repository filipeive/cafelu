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

        <!-- Orders Grid (Mobile & Desktop) -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @forelse($orders as $order)
                <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col hover:shadow-md transition-shadow">
                    <!-- Card Header -->
                    <div class="p-5 border-b border-gray-50 dark:border-gray-700/50 flex justify-between items-center bg-gray-50/50 dark:bg-gray-900/20">
                        <div>
                            <span class="text-[10px] font-black uppercase text-gray-400 dark:text-gray-500 block mb-1">Pedido</span>
                            <span class="font-mono text-orange-600 dark:text-orange-400 font-bold text-lg">#{{ $order->id }}</span>
                        </div>
                        <div class="text-right">
                            <span class="text-[10px] font-black uppercase text-gray-400 dark:text-gray-500 block mb-1">{{ $order->created_at->format('d/m/Y') }}</span>
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-300">{{ $order->created_at->format('H:i') }}</span>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-5 flex-1">
                        <!-- Items Preview -->
                        <div class="flex items-center gap-3 mb-6">
                            <div class="flex -space-x-3 overflow-hidden">
                                @foreach($order->items->take(4) as $item)
                                    <div class="inline-block h-10 w-10 rounded-2xl ring-4 ring-white dark:ring-gray-800 bg-gray-100 dark:bg-gray-700 flex items-center justify-center overflow-hidden shadow-sm"
                                        title="{{ $item->product->name }}">
                                        @if($item->product->image)
                                            <img src="{{ asset('storage/' . $item->product->image) }}" alt=""
                                                class="h-full w-full object-cover">
                                        @else
                                            <i class="mdi mdi-food text-gray-400"></i>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            @if($order->items->count() > 4)
                                <span class="text-xs font-bold text-gray-500 dark:text-gray-400">+{{ $order->items->count() - 4 }} itens</span>
                            @else
                                <span class="text-xs font-bold text-gray-500 dark:text-gray-400">{{ $order->items->count() }} {{ $order->items->count() > 1 ? 'itens' : 'item' }}</span>
                            @endif
                        </div>

                        <!-- Status & Total -->
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <span class="text-[10px] font-black uppercase text-gray-400 dark:text-gray-500 block mb-2">Status</span>
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
                                <span class="px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-wider {{ $statusClasses[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $statusLabels[$order->status] ?? $order->status }}
                                </span>
                            </div>
                            <div class="text-right">
                                <span class="text-[10px] font-black uppercase text-gray-400 dark:text-gray-500 block mb-1">Total</span>
                                <span class="text-xl font-black text-gray-900 dark:text-white">{{ number_format($order->total_amount, 2) }} <small class="text-xs font-bold">MT</small></span>
                            </div>
                        </div>

                        <!-- Payment Status -->
                        @if($order->payment_status === 'awaiting_confirmation')
                            <div class="p-3 bg-purple-50 dark:bg-purple-900/20 border border-purple-100 dark:border-purple-800/50 rounded-2xl flex items-center gap-3 text-purple-600 dark:text-purple-400">
                                <div class="w-8 h-8 rounded-xl bg-purple-100 dark:bg-purple-800/50 flex items-center justify-center">
                                    <i class="mdi mdi-clock-outline animate-spin-slow"></i>
                                </div>
                                <span class="text-[10px] font-black uppercase tracking-wider">Aguardando Confirmação</span>
                            </div>
                        @endif
                    </div>

                    <!-- Card Footer -->
                    <div class="p-5 bg-gray-50/50 dark:bg-gray-900/20 border-t border-gray-50 dark:border-gray-700/50 grid grid-cols-2 gap-3">
                        @if($order->status === 'active')
                            <button onclick="confirmCancel({{ $order->id }})" 
                                class="flex items-center justify-center gap-2 px-4 py-2.5 bg-white dark:bg-gray-800 border border-red-100 dark:border-red-900/30 text-red-500 rounded-2xl text-xs font-bold hover:bg-red-500 hover:text-white transition-all">
                                <i class="mdi mdi-close-circle-outline text-base"></i> Cancelar
                            </button>
                            <form id="cancel-form-{{ $order->id }}" action="{{ route('customer.order.cancel', $order) }}" method="POST" class="hidden">
                                @csrf
                            </form>
                        @endif

                        @if(!in_array($order->status, ['paid', 'completed', 'canceled']) && $order->payment_status !== 'awaiting_confirmation')
                            <button onclick="window.openPaymentModal({{ $order->id }}, {{ $order->total_amount }})"
                                class="flex items-center justify-center gap-2 px-4 py-2.5 bg-green-500 text-white rounded-2xl text-xs font-bold hover:bg-green-600 transition-all shadow-lg shadow-green-500/20 {{ $order->status !== 'active' ? 'col-span-2' : '' }}">
                                <i class="mdi mdi-cash-multiple text-base"></i> Pagar Agora
                            </button>
                        @endif

                        @if(in_array($order->status, ['paid', 'completed', 'canceled']))
                            <button onclick="confirmReorder({{ $order->id }})" 
                                class="col-span-2 flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-500 text-white rounded-2xl text-xs font-bold hover:bg-blue-600 transition-all shadow-lg shadow-blue-500/20">
                                <i class="mdi mdi-refresh text-base"></i> Repetir Pedido
                            </button>
                            <form id="reorder-form-{{ $order->id }}" action="{{ route('customer.order.reorder', $order) }}" method="POST" class="hidden">
                                @csrf
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white dark:bg-gray-800 rounded-3xl p-12 text-center border border-dashed border-gray-200 dark:border-gray-700">
                    <div class="w-20 h-20 bg-gray-50 dark:bg-gray-900/50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="mdi mdi-cart-outline text-4xl text-gray-300 dark:text-gray-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Nenhum pedido encontrado</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-8">Parece que você ainda não realizou nenhum pedido com este status.</p>
                    <a href="{{ route('welcome') }}#menu"
                        class="inline-flex items-center px-6 py-3 bg-orange-500 text-white rounded-2xl font-bold hover:bg-orange-600 transition-all shadow-lg shadow-orange-500/30">
                        Explorar Cardápio
                    </a>
                </div>
            @endforelse
        </div>

        @if($orders->hasPages())
            <div class="mt-8">
                {{ $orders->links() }}
            </div>
        @endif
    </div>

    @push('scripts')
    <script>
        function confirmCancel(orderId) {
            Swal.fire({
                title: '{{ __('messages.confirm_cancel_order') }}',
                text: '{{ __('messages.cancel_order_warning') }}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#9CA3AF',
                confirmButtonText: '{{ __('messages.yes_cancel') }}',
                cancelButtonText: '{{ __('messages.cancel') }}',
                background: document.documentElement.classList.contains('dark') ? '#1F2937' : '#FFFFFF',
                color: document.documentElement.classList.contains('dark') ? '#FFFFFF' : '#111827',
                borderRadius: '1.5rem'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('cancel-form-' + orderId).submit();
                }
            });
        }

        function confirmReorder(orderId) {
            Swal.fire({
                title: '{{ __('messages.confirm_reorder') }}',
                text: '{{ __('messages.reorder_warning') }}',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3B82F6',
                cancelButtonColor: '#9CA3AF',
                confirmButtonText: '{{ __('messages.yes_reorder') }}',
                cancelButtonText: '{{ __('messages.cancel') }}',
                background: document.documentElement.classList.contains('dark') ? '#1F2937' : '#FFFFFF',
                color: document.documentElement.classList.contains('dark') ? '#FFFFFF' : '#111827',
                borderRadius: '1.5rem'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('reorder-form-' + orderId).submit();
                }
            });
        }
    </script>
    @endpush

    <!-- Re-use Payment Modal from Dashboard (or move to a component) -->
    @include('customer.partials.payment-modal')
@endsection