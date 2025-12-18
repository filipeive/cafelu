@extends('layouts.app')

@section('title', 'Detalhes do Pedido')

@section('content')
    <div class="w-full">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white flex items-center gap-3">
                    Pedido #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                    @php
                        $statusClasses = [
                            'active' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
                            'completed' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
                            'paid' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
                            'canceled' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
                        ];
                        $statusLabel = [
                            'active' => 'Ativo',
                            'completed' => 'Finalizado',
                            'paid' => 'Pago',
                            'canceled' => 'Cancelado',
                        ];
                    @endphp
                    <span class="px-3 py-1 rounded-full text-sm font-medium {{ $statusClasses[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ $statusLabel[$order->status] ?? ucfirst($order->status) }}
                    </span>
                </h2>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Detalhes completos do pedido e itens associados.</p>
            </div>

            <div class="flex flex-wrap gap-2">
                <a href="{{ route('orders.index') }}" 
                   class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors flex items-center gap-2">
                    <i class="mdi mdi-arrow-left"></i> Voltar
                </a>

                @if ($order->status === 'active' || $order->status === 'completed')
                    <a href="{{ route('orders.edit', $order->id) }}" 
                       class="px-4 py-2 rounded-lg bg-yellow-500 hover:bg-yellow-600 text-white transition-colors flex items-center gap-2">
                        <i class="mdi mdi-pencil"></i> Editar
                    </a>
                @endif

                @if ($order->status === 'active' && $order->items->count() > 0)
                    <form action="{{ route('orders.complete', $order) }}" method="POST" class="inline-block" onsubmit="return confirm('Tem certeza que deseja finalizar este pedido?')">
                        @csrf
                        <button type="submit" class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white transition-colors flex items-center gap-2">
                            <i class="mdi mdi-check-circle"></i> Finalizar
                        </button>
                    </form>
                @endif

                @if ($order->status === 'completed')
                    <button type="button" onclick="$dispatch('open-payment-modal', { orderId: {{ $order->id }}, total: {{ $order->total_amount }} })"
                            class="px-4 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white transition-colors flex items-center gap-2">
                        <i class="mdi mdi-cash"></i> Pagamento
                    </button>
                @endif

                @if ($order->status !== 'canceled' && $order->status !== 'paid')
                    <form action="{{ route('orders.cancel', $order) }}" method="POST" class="inline-block" onsubmit="return confirm('Tem certeza que deseja cancelar este pedido?')">
                        @csrf
                        <button type="submit" class="px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white transition-colors flex items-center gap-2">
                            <i class="mdi mdi-close-circle"></i> Cancelar
                        </button>
                    </form>
                @endif

                @if (!in_array($order->status, ['paid', 'canceled', 'active']))
                    <button onclick="printRecibo({{ $order->id }})"
                            class="px-4 py-2 rounded-lg bg-cyan-500 hover:bg-cyan-600 text-white transition-colors flex items-center gap-2">
                        <i class="mdi mdi-printer"></i> Imprimir
                    </button>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6" x-data>
            <!-- Left Column: Order Info & Items -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Info Card -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                        <i class="mdi mdi-information-outline text-orange-500"></i> Informações Gerais
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center gap-3 p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                            <div class="p-2 bg-orange-100 dark:bg-orange-900/30 rounded-lg text-orange-600 dark:text-orange-400">
                                <i class="mdi mdi-table-furniture text-xl"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Mesa</p>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $order->table->number ?? 'Mesa Removida' }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                            <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg text-blue-600 dark:text-blue-400">
                                <i class="mdi mdi-account text-xl"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Cliente</p>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $order->customer_name ?? 'Não informado' }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                            <div class="p-2 bg-purple-100 dark:bg-purple-900/30 rounded-lg text-purple-600 dark:text-purple-400">
                                <i class="mdi mdi-calendar-clock text-xl"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Data & Hora</p>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                            <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-lg text-green-600 dark:text-green-400">
                                <i class="mdi mdi-account-circle text-xl"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Atendente</p>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $order->user->name }}</p>
                            </div>
                        </div>
                    </div>

                    @if ($order->status === 'paid')
                        <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                            <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300">
                                <i class="mdi mdi-credit-card text-gray-400"></i>
                                <strong>Método de Pagamento:</strong>
                                <span>
                                    @switch($order->payment_method)
                                        @case('cash') Dinheiro @break
                                        @case('card') Cartão @break
                                        @case('mpesa') M-Pesa @break
                                        @case('emola') E-Mola @break
                                        @case('mkesh') M-Kesh @break
                                        @default Não informado
                                    @endswitch
                                </span>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Items Table -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white flex items-center gap-2">
                            <i class="mdi mdi-format-list-bulleted text-orange-500"></i> Itens do Pedido
                        </h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-gray-900/50 border-b border-gray-200 dark:border-gray-700 text-xs uppercase text-gray-500 dark:text-gray-400 font-semibold">
                                    <th class="px-6 py-3">Produto</th>
                                    <th class="px-6 py-3 text-center">Qtd</th>
                                    <th class="px-6 py-3 text-right">Preço Unit.</th>
                                    <th class="px-6 py-3 text-right">Total</th>
                                    <th class="px-6 py-3 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($order->items as $item)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                        <td class="px-6 py-4 text-gray-900 dark:text-white font-medium">{{ $item->product->name }}</td>
                                        <td class="px-6 py-4 text-center text-gray-600 dark:text-gray-300">{{ $item->quantity }}</td>
                                        <td class="px-6 py-4 text-right text-gray-600 dark:text-gray-300">{{ number_format($item->unit_price, 2, ',', '.') }} MZN</td>
                                        <td class="px-6 py-4 text-right font-bold text-gray-900 dark:text-white">{{ number_format($item->total_price, 2, ',', '.') }} MZN</td>
                                        <td class="px-6 py-4 text-center">
                                            @php
                                                $itemStatusClasses = [
                                                    'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
                                                    'preparing' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
                                                    'ready' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300',
                                                    'delivered' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
                                                    'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
                                                ];
                                                $itemStatusLabel = [
                                                    'pending' => 'Pendente',
                                                    'preparing' => 'Preparando',
                                                    'ready' => 'Pronto',
                                                    'delivered' => 'Entregue',
                                                    'cancelled' => 'Cancelado',
                                                ];
                                            @endphp
                                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $itemStatusClasses[$item->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                {{ $itemStatusLabel[$item->status] ?? ucfirst($item->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                            Nenhum item adicionado ao pedido
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot class="bg-gray-50 dark:bg-gray-900/50 border-t border-gray-200 dark:border-gray-700">
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-right font-bold text-gray-700 dark:text-gray-300">Total:</td>
                                    <td class="px-6 py-4 text-right font-bold text-orange-600 dark:text-orange-400 text-lg">
                                        {{ number_format($order->total_amount, 2, ',', '.') }} MZN
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Right Column: Notes & Group Info -->
            <div class="space-y-6">
                <!-- Notes -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                        <i class="mdi mdi-note-text text-gray-500"></i> Observações
                    </h3>
                    <div class="p-4 bg-gray-50 dark:bg-gray-900/50 rounded-lg border border-gray-100 dark:border-gray-700 text-gray-600 dark:text-gray-300 text-sm italic">
                        @if ($order->notes)
                            {{ $order->notes }}
                        @else
                            <span class="text-gray-400 not-italic">Nenhuma observação registrada.</span>
                        @endif
                    </div>
                </div>

                <!-- Table Group Info -->
                @if ($order->table && $order->table->group_id)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                            <i class="mdi mdi-table-multiple text-indigo-500"></i> Grupo de Mesas
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center pb-2 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Mesa Principal</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $order->table->number }}</span>
                            </div>
                            <div class="flex justify-between items-center pb-2 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Capacidade Total</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $order->table->merged_capacity ?? $order->table->capacity }} lugares</span>
                            </div>
                            <div>
                                <span class="text-sm text-gray-500 dark:text-gray-400 block mb-1">Mesas Agrupadas</span>
                                @php
                                    $groupedTables = \App\Models\Table::where('group_id', $order->table->group_id)->get();
                                    $tableNumbers = $groupedTables->pluck('number')->implode(', ');
                                @endphp
                                <span class="font-medium text-indigo-600 dark:text-indigo-400">{{ $tableNumbers }}</span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Payment Modal (Reused from Index) -->
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
        }"
        x-show="show" 
        style="display: none;"
        class="fixed inset-0 z-50 overflow-y-auto"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">
        
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity" @click="show = false"></div>

        <!-- Modal Panel -->
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-gray-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-gray-200 dark:border-gray-700">
                
                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <i class="mdi mdi-cash-register text-green-500"></i>
                        Registrar Pagamento #<span x-text="orderId"></span>
                    </h3>
                    <button type="button" @click="show = false" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 transition-colors">
                        <i class="mdi mdi-close text-xl"></i>
                    </button>
                </div>

                <!-- Body -->
                <div class="p-6 space-y-4">
                    <!-- Total Display -->
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800 rounded-lg p-4 flex justify-between items-center">
                        <span class="text-blue-800 dark:text-blue-200 font-medium">Total a Pagar:</span>
                        <span class="text-xl font-bold text-blue-600 dark:text-blue-300">MZN <span x-text="Number(totalAmount).toLocaleString('pt-BR', {minimumFractionDigits: 2})"></span></span>
                    </div>

                    <!-- Payment Method -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Método de Pagamento *</label>
                        <select x-model="paymentMethod" class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm">
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
                        <textarea x-model="notes" rows="3" class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm" placeholder="Opcional..."></textarea>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 flex justify-end gap-3">
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
