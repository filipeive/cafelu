@extends('layouts.app')

@section('content')
    @php
        $pageTitle = 'Pedidos';

        function get_status_class_tailwind($status)
        {
            $classes = [
                'completed' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
                'active' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
                'canceled' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
                'paid' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
            ];
            return $classes[$status] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
        }
    @endphp

    <div class="w-full" x-data="{ 
                search: '{{ old('search', $search) }}',
                performSearch() {
                    window.location.href = '{{ route('orders.index') }}?search=' + encodeURIComponent(this.search);
                }
            }">
        <!-- Stats Cards Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Total Orders -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border-l-4 border-orange-500">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Total Pedidos</p>
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-1">{{ $total_orders }}</h3>
                        <p class="text-xs text-orange-500 flex items-center">
                            <i class="mdi mdi-receipt mr-1"></i>
                            Pedidos acumulados
                        </p>
                    </div>
                    <div class="p-3 bg-orange-50 dark:bg-orange-900/20 rounded-full">
                        <i class="mdi mdi-receipt text-orange-500 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Orders Today -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border-l-4 border-green-500">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Pedidos Hoje</p>
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-1">{{ $totalToday }}</h3>
                        <p class="text-xs text-green-500 flex items-center">
                            <i class="mdi mdi-calendar-today mr-1"></i>
                            Hoje
                        </p>
                    </div>
                    <div class="p-3 bg-green-50 dark:bg-green-900/20 rounded-full">
                        <i class="mdi mdi-calendar-today text-green-500 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Total Value -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border-l-4 border-blue-500">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Valor Total</p>
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-1">MZN
                            {{ number_format($totalToday, 2) }}
                        </h3>
                        <p class="text-xs text-blue-500 flex items-center">
                            <i class="mdi mdi-cash-multiple mr-1"></i>
                            Total do dia
                        </p>
                    </div>
                    <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-full">
                        <i class="mdi mdi-cash-multiple text-blue-500 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Active Orders -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border-l-4 border-yellow-500">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Pedidos Ativos</p>
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-1">{{ $totalOpen }}</h3>
                        <p class="text-xs text-yellow-500 flex items-center">
                            <i class="mdi mdi-clock-alert mr-1"></i>
                            Em andamento
                        </p>
                    </div>
                    <div class="p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-full">
                        <i class="mdi mdi-clock-alert text-yellow-500 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders List -->
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <!-- Header -->
            <div
                class="p-6 border-b border-gray-200 dark:border-gray-700 flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="flex items-center gap-3">
                    <h4 class="text-lg font-bold text-gray-800 dark:text-white">Lista de Pedidos</h4>
                    <span
                        class="px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300">
                        {{ $total_orders }} pedidos
                    </span>
                </div>

                <!-- Search -->
                <div class="relative w-full md:w-64">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="mdi mdi-magnify text-gray-400"></i>
                    </span>
                    <input type="text" x-model="search" @keydown.enter="performSearch()"
                        class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm"
                        placeholder="Pesquisar pedidos...">
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-gray-50 dark:bg-gray-900/50 border-b border-gray-200 dark:border-gray-700 text-xs uppercase text-gray-500 dark:text-gray-400 font-semibold">
                            <th class="px-6 py-4">ID</th>
                            <th class="px-6 py-4">Cliente</th>
                            <th class="px-6 py-4">Data</th>
                            <th class="px-6 py-4">Mesa</th>
                            <th class="px-6 py-4">Total</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($orders as $order)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="font-mono text-orange-600 dark:text-orange-400 font-medium">
                                        #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div
                                            class="flex-shrink-0 h-8 w-8 rounded bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-500 dark:text-gray-400 mr-3">
                                            <i class="mdi mdi-account"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $order->customer_name ?? 'Cliente Anônimo' }}
                                            </div>
                                            @if ($order->customer_phone)
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $order->customer_phone }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div
                                            class="flex-shrink-0 h-8 w-8 rounded bg-orange-50 dark:bg-orange-900/20 flex items-center justify-center text-orange-500 mr-3">
                                            <i class="mdi mdi-calendar"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $order->created_at->format('d/m/Y') }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $order->created_at->format('H:i') }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                        Mesa {{ $order->table_id }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-bold text-gray-900 dark:text-white">
                                        MZN {{ number_format($order->total_amount, 2) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ get_status_class_tailwind($order->status) }}">
                                        <i class="mdi mdi-circle-medium mr-1"></i>
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('orders.show', $order->id) }}"
                                            class="p-2 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 dark:bg-blue-900/20 dark:text-blue-400 dark:hover:bg-blue-900/40 transition-colors"
                                            title="Ver Detalhes">
                                            <i class="mdi mdi-eye"></i>
                                        </a>

                                        @if ($order->status == 'completed')
                                            <!-- Payment Button (Triggers Modal) -->
                                            <button type="button"
                                                @click="$dispatch('open-payment-modal', { orderId: {{ $order->id }}, total: {{ $order->total_amount }} })"
                                                class="p-2 rounded-lg bg-green-50 text-green-600 hover:bg-green-100 dark:bg-green-900/20 dark:text-green-400 dark:hover:bg-green-900/40 transition-colors"
                                                title="Registrar Pagamento">
                                                <i class="mdi mdi-cash"></i>
                                            </button>
                                        @endif

                                        @if ($order->status == 'paid')
                                            <button onclick="printRecibo({{ $order->id }})"
                                                class="p-2 rounded-lg bg-cyan-50 text-cyan-600 hover:bg-cyan-100 dark:bg-cyan-900/20 dark:text-cyan-400 dark:hover:bg-cyan-900/40 transition-colors"
                                                title="Imprimir">
                                                <i class="mdi mdi-printer"></i>
                                            </button>
                                        @endif

                                        @if ($order->status != 'completed' && $order->status != 'canceled' && $order->status != 'paid')
                                            <a href="{{ route('orders.edit', $order->id) }}"
                                                class="p-2 rounded-lg bg-yellow-50 text-yellow-600 hover:bg-yellow-100 dark:bg-yellow-900/20 dark:text-yellow-400 dark:hover:bg-yellow-900/40 transition-colors"
                                                title="Editar">
                                                <i class="mdi mdi-pencil"></i>
                                            </a>

                                            @if ($order->status == 'active' && $order->items->count() > 0)
                                                <button onclick="completeOrder({{ $order->id }})"
                                                    class="p-2 rounded-lg bg-green-50 text-green-600 hover:bg-green-100 dark:bg-green-900/20 dark:text-green-400 dark:hover:bg-green-900/40 transition-colors"
                                                    title="Finalizar">
                                                    <i class="mdi mdi-check"></i>
                                                </button>
                                            @endif

                                            <button onclick="cancelOrder({{ $order->id }})"
                                                class="p-2 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 dark:bg-red-900/20 dark:text-red-400 dark:hover:bg-red-900/40 transition-colors"
                                                title="Cancelar">
                                                <i class="mdi mdi-close"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-500 dark:text-gray-400">
                                        <i class="mdi mdi-alert-circle-outline text-4xl mb-3"></i>
                                        <p class="text-lg font-medium">Nenhum pedido encontrado</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($orders->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Payment Modal (Alpine.js) -->
    <div x-data="{ 
                    show: false, 
                    orderId: null, 
                    totalAmount: 0,
                    paymentMethod: '',
                    notes: '',
                    isLoading: false,
                    init() {
                        window.addEventListener('open-payment-modal', (e) => {
                            this.orderId = e.detail.orderId;
                            this.totalAmount = e.detail.total;
                            this.show = true;
                            this.paymentMethod = '';
                            this.notes = '';
                        });
                    },
                    submitPayment() {
                        if (!this.paymentMethod) {
                            showToast('Selecione um método de pagamento', 'warning');
                            return;
                        }

                        this.isLoading = true;

                        fetch(`/orders/${this.orderId}/pay`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name=\'csrf-token\']').content,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                payment_method: this.paymentMethod,
                                amount_paid: this.totalAmount,
                                notes: this.notes
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            this.isLoading = false;
                            if (data.success) {
                                this.show = false;
                                showToast('Pagamento registrado com sucesso!', 'success');
                                setTimeout(() => window.location.reload(), 1000);
                            } else {
                                showToast(data.message || 'Erro ao registrar pagamento', 'error');
                            }
                        })
                        .catch(error => {
                            this.isLoading = false;
                            console.error('Error:', error);
                            showToast('Ocorreu um erro ao processar o pagamento', 'error');
                        });
                    }
                }" x-show="show" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

        <!-- Backdrop -->
        <div class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity" @click="show = false"></div>

        <!-- Modal Panel -->
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div
                class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-gray-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-gray-200 dark:border-gray-700">

                <!-- Header -->
                <div
                    class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <i class="mdi mdi-cash-register text-green-500"></i>
                        Registrar Pagamento #<span x-text="orderId"></span>
                    </h3>
                    <button type="button" @click="show = false"
                        class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 transition-colors">
                        <i class="mdi mdi-close text-xl"></i>
                    </button>
                </div>

                <!-- Body -->
                <div class="p-6 space-y-4">
                    <!-- Total Display -->
                    <div
                        class="bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800 rounded-lg p-4 flex justify-between items-center">
                        <span class="text-blue-800 dark:text-blue-200 font-medium">Total a Pagar:</span>
                        <span class="text-xl font-bold text-blue-600 dark:text-blue-300">MZN <span
                                x-text="Number(totalAmount).toLocaleString('pt-BR', {minimumFractionDigits: 2})"></span></span>
                    </div>

                    <!-- Payment Method -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Método de Pagamento
                            *</label>
                        <select x-model="paymentMethod"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm">
                            <option value="">Selecione um método</option>
                            <option value="cash">Dinheiro</option>
                            <option value="card">Cartão</option>
                            <option value="mpesa">M-Pesa</option>
                            <option value="emola">E-Mola</option>
                            <option value="mkesh">M-Kesh</option>
                        </select>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Observações</label>
                        <textarea x-model="notes" rows="3"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm"
                            placeholder="Opcional..."></textarea>
                    </div>
                </div>

                <!-- Footer -->
                <div
                    class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 flex justify-end gap-3">
                    <button type="button" @click="show = false"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600">
                        Cancelar
                    </button>
                    <button type="button" @click="submitPayment()" :disabled="isLoading"
                        class="px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 shadow-sm flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="mdi mdi-check-circle" x-show="!isLoading"></i>
                        <i class="mdi mdi-loading mdi-spin" x-show="isLoading"></i>
                        <span x-text="isLoading ? 'Processando...' : 'Confirmar Pagamento'"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
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
    </script>
@endpush