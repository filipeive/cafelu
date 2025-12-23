@extends('layouts.app')

@section('content')
    @php
        $pageTitle = 'Pedidos';

        function get_status_class_tailwind($status, $payment_status = null)
        {
            if ($payment_status === 'awaiting_confirmation') {
                return 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300';
            }

            $classes = [
                'completed' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
                'active' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
                'canceled' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
                'paid' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
                'pending_cancel' => 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300',
            ];
            return $classes[$status] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
        }
    @endphp

    <div class="w-full" x-data="{ 
                search: '{{ addslashes(old('search', $search)) }}',
                performSearch() {
                    window.location.href = '{{ route('orders.index') }}?search=' + encodeURIComponent(this.search);
                }
            }">
        <!-- Stats Cards Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Orders -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm p-6 border border-gray-100 dark:border-gray-700 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-3 opacity-10 group-hover:scale-110 transition-transform">
                    <i class="mdi mdi-receipt text-6xl text-orange-500"></i>
                </div>
                <div class="relative z-10">
                    <p class="text-xs font-black uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-1">Total Pedidos</p>
                    <h3 class="text-3xl font-black text-gray-900 dark:text-white mb-1">{{ $total_orders }}</h3>
                    <div class="flex items-center gap-1 text-[10px] font-bold text-orange-500">
                        <i class="mdi mdi-trending-up"></i>
                        <span>Histórico acumulado</span>
                    </div>
                </div>
            </div>

            <!-- Orders Today -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm p-6 border border-gray-100 dark:border-gray-700 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-3 opacity-10 group-hover:scale-110 transition-transform">
                    <i class="mdi mdi-calendar-today text-6xl text-green-500"></i>
                </div>
                <div class="relative z-10">
                    <p class="text-xs font-black uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-1">Pedidos Hoje</p>
                    <h3 class="text-3xl font-black text-gray-900 dark:text-white mb-1">{{ $totalTodayCount ?? $orders->where('created_at', '>=', today())->count() }}</h3>
                    <div class="flex items-center gap-1 text-[10px] font-bold text-green-500">
                        <i class="mdi mdi-check-circle"></i>
                        <span>Novos hoje</span>
                    </div>
                </div>
            </div>

            <!-- Total Value Today -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm p-6 border border-gray-100 dark:border-gray-700 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-3 opacity-10 group-hover:scale-110 transition-transform">
                    <i class="mdi mdi-cash-multiple text-6xl text-blue-500"></i>
                </div>
                <div class="relative z-10">
                    <p class="text-xs font-black uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-1">Faturamento Hoje</p>
                    <h3 class="text-3xl font-black text-gray-900 dark:text-white mb-1"><small class="text-sm font-bold">MZN</small> {{ number_format($totalToday, 2) }}</h3>
                    <div class="flex items-center gap-1 text-[10px] font-bold text-blue-500">
                        <i class="mdi mdi-currency-usd"></i>
                        <span>Total do dia</span>
                    </div>
                </div>
            </div>

            <!-- Active Orders -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm p-6 border border-gray-100 dark:border-gray-700 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-3 opacity-10 group-hover:scale-110 transition-transform">
                    <i class="mdi mdi-clock-alert text-6xl text-yellow-500"></i>
                </div>
                <div class="relative z-10">
                    <p class="text-xs font-black uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-1">Em Aberto</p>
                    <h3 class="text-3xl font-black text-gray-900 dark:text-white mb-1">{{ $totalOpen }}</h3>
                    <div class="flex items-center gap-1 text-[10px] font-bold text-yellow-500">
                        <i class="mdi mdi-timer-sand"></i>
                        <span>Aguardando preparo</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders List -->
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <!-- Header -->
            <div class="p-6 border-b border-gray-50 dark:border-gray-700 flex flex-col lg:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-orange-500 rounded-2xl flex items-center justify-center shadow-lg shadow-orange-500/20">
                        <i class="mdi mdi-format-list-bulleted text-white text-xl"></i>
                    </div>
                    <div>
                        <h4 class="text-xl font-black text-gray-900 dark:text-white">Gestão de Pedidos</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400 font-bold uppercase tracking-widest">{{ $total_orders }} registros encontrados</p>
                    </div>
                </div>

                <!-- Search and Filters -->
                <div class="flex flex-col md:flex-row items-center gap-4 w-full lg:w-auto">
                    <!-- Filter Tabs -->
                    <div class="flex bg-gray-100 dark:bg-gray-900/50 p-1.5 rounded-2xl w-full md:w-auto">
                        <a href="{{ route('orders.index', ['filter' => '', 'search' => $search]) }}" 
                           class="flex-1 md:flex-none px-6 py-2 text-xs font-black uppercase tracking-wider rounded-xl transition-all {{ !$filter ? 'bg-white dark:bg-gray-800 text-orange-500 shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200' }}">
                            Todos
                        </a>
                        <a href="{{ route('orders.index', ['filter' => 'in-house', 'search' => $search]) }}" 
                           class="flex-1 md:flex-none px-6 py-2 text-xs font-black uppercase tracking-wider rounded-xl transition-all {{ $filter === 'in-house' ? 'bg-white dark:bg-gray-800 text-orange-500 shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200' }}">
                            Presencial
                        </a>
                        <a href="{{ route('orders.index', ['filter' => 'online', 'search' => $search]) }}" 
                           class="flex-1 md:flex-none px-6 py-2 text-xs font-black uppercase tracking-wider rounded-xl transition-all {{ $filter === 'online' ? 'bg-white dark:bg-gray-800 text-orange-500 shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200' }}">
                            Online
                        </a>
                    </div>

                    <!-- Search -->
                    <div class="relative w-full md:w-72">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="mdi mdi-magnify text-gray-400 text-lg"></i>
                        </span>
                        <input type="text" x-model="search" @keydown.enter="performSearch()"
                            class="w-full pl-12 pr-4 py-3 rounded-2xl border-none bg-gray-100 dark:bg-gray-900/50 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 text-sm font-medium placeholder-gray-400"
                            placeholder="Buscar por ID ou cliente...">
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 dark:bg-gray-900/20 border-b border-gray-50 dark:border-gray-700">
                            <th class="px-6 py-5 text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500">ID & Origem</th>
                            <th class="px-6 py-5 text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500">Cliente</th>
                            <th class="px-6 py-5 text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500 text-center">Itens</th>
                            <th class="px-6 py-5 text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500">Total</th>
                            <th class="px-6 py-5 text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500">Status</th>
                            <th class="px-6 py-5 text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500 text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                        @forelse ($orders as $order)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/20 transition-colors group">
                                <td class="px-6 py-5">
                                    <div class="flex flex-col gap-1.5">
                                        <span class="font-mono text-orange-600 dark:text-orange-400 font-black text-base">
                                            #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                                        </span>
                                        @if($order->table_id)
                                            <span class="inline-flex w-fit items-center px-2 py-0.5 rounded-lg text-[9px] font-black uppercase bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400 border border-blue-100 dark:border-blue-800/50">
                                                <i class="mdi mdi-table-furniture mr-1"></i> Mesa {{ $order->table_id }}
                                            </span>
                                        @else
                                            <span class="inline-flex w-fit items-center px-2 py-0.5 rounded-lg text-[9px] font-black uppercase bg-purple-50 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400 border border-purple-100 dark:border-purple-800/50">
                                                <i class="mdi mdi-web mr-1"></i> Online
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-400 group-hover:bg-orange-500 group-hover:text-white transition-all">
                                            <i class="mdi mdi-account text-xl"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-black text-gray-900 dark:text-white">
                                                {{ $order->customer_name ?? 'Cliente Anônimo' }}
                                            </div>
                                            <div class="text-[10px] font-bold text-gray-400 dark:text-gray-500 flex items-center gap-2">
                                                <i class="mdi mdi-clock-outline"></i>
                                                {{ $order->created_at->format('H:i') }} • {{ $order->created_at->format('d/m/Y') }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <span class="px-3 py-1 bg-gray-100 dark:bg-gray-900/50 rounded-full text-xs font-black text-gray-600 dark:text-gray-400">
                                        {{ $order->items->sum('quantity') }}
                                    </span>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-black text-gray-900 dark:text-white">
                                            MZN {{ number_format($order->total_amount, 2) }}
                                        </span>
                                        <span class="text-[9px] font-black uppercase tracking-tighter {{ $order->payment_status === 'paid' ? 'text-green-500' : 'text-yellow-500' }}">
                                            {{ $order->payment_status === 'paid' ? 'Pago' : 'Pendente' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-wider {{ get_status_class_tailwind($order->status, $order->payment_status) }}">
                                        <i class="mdi mdi-circle-medium mr-1"></i>
                                        @if($order->status === 'pending_cancel')
                                            Solicitação de Cancelamento
                                        @else
                                            {{ $order->payment_status === 'awaiting_confirmation' ? 'Aguardando Confirmação' : ucfirst($order->status) }}
                                        @endif
                                    </span>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('orders.show', $order->id) }}"
                                            class="w-9 h-9 flex items-center justify-center rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white dark:bg-blue-900/20 dark:text-blue-400 dark:hover:bg-blue-600 transition-all"
                                            title="Ver Detalhes">
                                            <i class="mdi mdi-eye text-lg"></i>
                                        </a>

                                        @if ($order->payment_status == 'awaiting_confirmation')
                                            <form action="{{ route('orders.confirm-payment', $order->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit"
                                                    class="w-9 h-9 flex items-center justify-center rounded-xl bg-green-50 text-green-600 hover:bg-green-600 hover:text-white dark:bg-green-900/20 dark:text-green-400 dark:hover:bg-green-600 transition-all"
                                                    title="Confirmar Pagamento">
                                                    <i class="mdi mdi-check-decagram text-lg"></i>
                                                </button>
                                            </form>
                                        @endif

                                        @if ($order->status == 'completed' && $order->payment_status != 'awaiting_confirmation' && $order->payment_status != 'paid')
                                            <button type="button"
                                                @click="$dispatch('open-payment-modal', { orderId: {{ $order->id }}, total: {{ $order->total_amount }} })"
                                                class="w-9 h-9 flex items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white dark:bg-emerald-900/20 dark:text-emerald-400 dark:hover:bg-emerald-600 transition-all"
                                                title="Registrar Pagamento">
                                                <i class="mdi mdi-cash text-lg"></i>
                                            </button>
                                        @endif

                                        @if ($order->status == 'paid')
                                            <button onclick="printRecibo({{ $order->id }})"
                                                class="w-9 h-9 flex items-center justify-center rounded-xl bg-cyan-50 text-cyan-600 hover:bg-cyan-600 hover:text-white dark:bg-cyan-900/20 dark:text-cyan-400 dark:hover:bg-cyan-600 transition-all"
                                                title="Imprimir">
                                                <i class="mdi mdi-printer text-lg"></i>
                                            </button>
                                        @endif

                                        @if ($order->status != 'completed' && $order->status != 'canceled' && $order->status != 'paid' && $order->status != 'pending_cancel')
                                            <a href="{{ route('orders.edit', $order->id) }}"
                                                class="w-9 h-9 flex items-center justify-center rounded-xl bg-yellow-50 text-yellow-600 hover:bg-yellow-600 hover:text-white dark:bg-yellow-900/20 dark:text-yellow-400 dark:hover:bg-yellow-600 transition-all"
                                                title="Editar">
                                                <i class="mdi mdi-pencil text-lg"></i>
                                            </a>

                                            @if ($order->status == 'active' && $order->items->count() > 0)
                                                <button onclick="completeOrder({{ $order->id }})"
                                                    class="w-9 h-9 flex items-center justify-center rounded-xl bg-green-50 text-green-600 hover:bg-green-600 hover:text-white dark:bg-green-900/20 dark:text-green-400 dark:hover:bg-green-600 transition-all"
                                                    title="Finalizar">
                                                    <i class="mdi mdi-check text-lg"></i>
                                                </button>
                                            @endif

                                            <button onclick="cancelOrder({{ $order->id }})"
                                                class="w-9 h-9 flex items-center justify-center rounded-xl bg-red-50 text-red-600 hover:bg-red-600 hover:text-white dark:bg-red-900/20 dark:text-red-400 dark:hover:bg-red-600 transition-all"
                                                title="Cancelar">
                                                <i class="mdi mdi-close text-lg"></i>
                                            </button>
                                        @endif

                                        @if($order->status === 'pending_cancel')
                                            <button onclick="approveCancellation({{ $order->id }}, '{{ addslashes($order->cancellation_reason) }}')"
                                                class="w-9 h-9 flex items-center justify-center rounded-xl bg-green-50 text-green-600 hover:bg-green-600 hover:text-white dark:bg-green-900/20 dark:text-green-400 dark:hover:bg-green-600 transition-all"
                                                title="Aprovar Cancelamento">
                                                <i class="mdi mdi-check-circle text-lg"></i>
                                            </button>
                                            <button onclick="rejectCancellation({{ $order->id }})"
                                                class="w-9 h-9 flex items-center justify-center rounded-xl bg-red-50 text-red-600 hover:bg-red-600 hover:text-white dark:bg-red-900/20 dark:text-red-400 dark:hover:bg-red-600 transition-all"
                                                title="Rejeitar Cancelamento">
                                                <i class="mdi mdi-close-circle text-lg"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-20 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-20 h-20 bg-gray-50 dark:bg-gray-900/50 rounded-full flex items-center justify-center mb-4">
                                            <i class="mdi mdi-receipt-text-remove text-4xl text-gray-300 dark:text-gray-600"></i>
                                        </div>
                                        <h3 class="text-lg font-black text-gray-900 dark:text-white">Nenhum pedido encontrado</h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Tente ajustar seus filtros ou busca.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($orders->hasPages())
                <div class="px-6 py-6 border-t border-gray-50 dark:border-gray-700 bg-gray-50/30 dark:bg-gray-900/10">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </div>
 
    <!-- Payment Modal -->
    @include('orders._payment_modal')
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function completeOrder(orderId) {
            Swal.fire({
                title: 'Finalizar Pedido',
                text: 'Tem certeza que deseja finalizar este pedido e gerar uma venda?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sim, finalizar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#10B981',
                cancelButtonColor: '#6B7280',
                background: document.documentElement.classList.contains('dark') ? '#1F2937' : '#FFFFFF',
                color: document.documentElement.classList.contains('dark') ? '#FFFFFF' : '#1F2937'
            }).then((result) => {
                if (result.isConfirmed) {
                    showLoading();
                    fetch(`/orders/complete/${orderId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            hideLoading();
                            if (data.success) {
                                showToast(data.message, 'success');
                                setTimeout(() => window.location.reload(), 1000);
                            } else {
                                showToast(data.message, 'error');
                            }
                        })
                        .catch(error => {
                            hideLoading();
                            console.error('Erro:', error);
                            showToast('Ocorreu um erro ao processar a requisição', 'error');
                        });
                }
            });
        }

        function cancelOrder(orderId) {
            Swal.fire({
                title: 'Cancelar Pedido',
                text: 'Tem certeza que deseja cancelar este pedido?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sim, cancelar',
                cancelButtonText: 'Não',
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#6B7280',
                background: document.documentElement.classList.contains('dark') ? '#1F2937' : '#FFFFFF',
                color: document.documentElement.classList.contains('dark') ? '#FFFFFF' : '#1F2937'
            }).then((result) => {
                if (result.isConfirmed) {
                    showLoading();
                    fetch(`/orders/cancel/${orderId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            hideLoading();
                            if (data.success) {
                                showToast(data.message || 'O pedido foi cancelado com sucesso.', 'success');
                                setTimeout(() => window.location.reload(), 1000);
                            } else {
                                showToast(data.message || 'Erro ao cancelar o pedido.', 'error');
                            }
                        })
                        .catch(error => {
                            hideLoading();
                            console.error('Erro:', error);
                            showToast('Ocorreu um erro ao processar a requisição', 'error');
                        });
                }
            });
        }

        function approveCancellation(orderId, reason) {
            Swal.fire({
                title: 'Aprovar Cancelamento',
                html: `<p class="mb-4">Deseja aprovar o cancelamento deste pedido?</p>
                       <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg text-left text-sm italic">
                           <strong>Motivo do cliente:</strong><br>${reason}
                       </div>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sim, aprovar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#10B981',
                cancelButtonColor: '#6B7280',
                background: document.documentElement.classList.contains('dark') ? '#1F2937' : '#FFFFFF',
                color: document.documentElement.classList.contains('dark') ? '#FFFFFF' : '#1F2937'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/orders/${orderId}/approve-cancellation`;
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = document.querySelector('meta[name="csrf-token"]').content;
                    form.appendChild(csrfToken);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        function rejectCancellation(orderId) {
            Swal.fire({
                title: 'Rejeitar Cancelamento',
                text: 'Deseja rejeitar o pedido de cancelamento? O pedido voltará ao status Ativo.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sim, rejeitar cancelamento',
                cancelButtonText: 'Voltar',
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#6B7280',
                background: document.documentElement.classList.contains('dark') ? '#1F2937' : '#FFFFFF',
                color: document.documentElement.classList.contains('dark') ? '#FFFFFF' : '#1F2937'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/orders/${orderId}/reject-cancellation`;
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = document.querySelector('meta[name="csrf-token"]').content;
                    form.appendChild(csrfToken);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
@endpush