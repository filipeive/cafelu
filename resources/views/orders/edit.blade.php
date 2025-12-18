@extends('layouts.app')

@section('title', 'Editar Pedido')

@section('content')
    <div class="w-full" x-data="{ 
        activeTab: '{{ $categories->first()->id }}',
        paymentModalOpen: false,
        paymentMethod: '',
        cashAmount: '',
        changeAmount: '0.00',
        totalAmount: {{ $order->total_amount }},
        
        calculateChange() {
            const received = parseFloat(this.cashAmount) || 0;
            const change = received - this.totalAmount;
            this.changeAmount = change >= 0 ? change.toFixed(2) : '0.00';
        }
    }">
        <!-- Header -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div class="flex items-center gap-4">
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-white flex items-center gap-2">
                        <i class="mdi mdi-receipt text-orange-500"></i>
                        Pedido #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                    </h2>
                    @php
                        $statusClasses = [
                            'active' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
                            'completed' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
                            'paid' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
                            'canceled' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
                        ];
                    @endphp
                    <span class="px-3 py-1 rounded-full text-sm font-medium {{ $statusClasses[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>

                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('tables.index') }}" class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors flex items-center gap-2">
                        <i class="mdi mdi-table"></i> Mesas
                    </a>
                    <a href="{{ route('orders.index') }}" class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors flex items-center gap-2">
                        <i class="mdi mdi-clipboard-list"></i> Pedidos
                    </a>
                    
                    @if ($order->status === 'completed' && !$order->is_paid)
                        <button @click="paymentModalOpen = true" class="px-4 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white transition-colors flex items-center gap-2">
                            <i class="mdi mdi-cash-multiple"></i> Pagamento
                        </button>
                    @endif

                    @if ($order->status != 'active')
                        <button onclick="printRecibo({{ $order->id }})" class="px-4 py-2 rounded-lg bg-cyan-500 hover:bg-cyan-600 text-white transition-colors flex items-center gap-2">
                            <i class="mdi mdi-printer"></i> Imprimir
                        </button>
                    @endif

                    @if ($order->status == 'active')
                        <form action="{{ route('orders.cancel', $order) }}" method="POST" class="inline-block" onsubmit="return confirm('Tem certeza que deseja cancelar este pedido?')">
                            @csrf
                            <button type="submit" class="px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white transition-colors flex items-center gap-2">
                                <i class="mdi mdi-delete"></i> Cancelar
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Info Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                <div class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-4 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-2 mb-2 text-gray-600 dark:text-gray-400">
                        <i class="mdi mdi-clock-outline"></i>
                        <span>{{ $order->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="flex items-center gap-2 mb-2 text-green-600 dark:text-green-400 font-bold text-lg">
                        <i class="mdi mdi-cash"></i>
                        <span>MZN {{ number_format($order->total_amount, 2, ',', '.') }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-blue-600 dark:text-blue-400">
                        <i class="mdi mdi-account"></i>
                        <span>{{ $order->customer_name ?: 'Cliente não identificado' }}</span>
                    </div>
                </div>

                <div class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-4 border border-gray-100 dark:border-gray-700">
                    @if ($order->table)
                        <div class="flex items-center gap-2 mb-2 text-gray-700 dark:text-gray-300">
                            <i class="mdi mdi-table-furniture text-orange-500"></i>
                            <span>Mesa {{ $order->table->number }}
                                @if ($order->table->group_id)
                                    <span class="ml-2 px-2 py-0.5 rounded text-xs bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                        <i class="mdi mdi-link-variant"></i>
                                        {{ $order->table->groupedTablesNumbers ? 'Unida: ' . $order->table->groupedTablesNumbers : '' }}
                                    </span>
                                @endif
                            </span>
                        </div>
                    @endif
                    @if ($order->notes)
                        <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400 italic">
                            <i class="mdi mdi-note-text text-yellow-500"></i>
                            <span>{{ $order->notes }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Order Items & Quick Menu -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Order Items -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white flex items-center gap-2">
                            <i class="mdi mdi-format-list-bulleted text-orange-500"></i> Itens do Pedido
                        </h3>
                        <span class="px-3 py-1 rounded-full bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300 text-xs font-bold">
                            {{ $order->items->count() }} itens
                        </span>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-gray-900/50 border-b border-gray-200 dark:border-gray-700 text-xs uppercase text-gray-500 dark:text-gray-400 font-semibold">
                                    <th class="px-6 py-3">Produto</th>
                                    <th class="px-6 py-3 text-center">Qtd</th>
                                    <th class="px-6 py-3 text-right">Total</th>
                                    <th class="px-6 py-3 text-center">Status</th>
                                    <th class="px-6 py-3 text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($order->items as $item)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-gray-900 dark:text-white">{{ $item->product->name }}</div>
                                            @if ($item->notes)
                                                <div class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                                    <i class="mdi mdi-note-text"></i> {{ $item->notes }}
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center text-gray-600 dark:text-gray-300">{{ $item->quantity }}</td>
                                        <td class="px-6 py-4 text-right font-bold text-gray-900 dark:text-white">
                                            {{ number_format($item->total_price, 2, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <form action="{{ route('orders.update-item-status', $item) }}" method="POST">
                                                @csrf
                                                <select name="status" onchange="this.form.submit()" 
                                                    class="text-xs rounded-full border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 focus:ring-orange-500 focus:border-orange-500 py-1 pl-2 pr-6">
                                                    <option value="pending" @selected($item->status === 'pending')>Pendente</option>
                                                    <option value="preparing" @selected($item->status === 'preparing')>Preparando</option>
                                                    <option value="ready" @selected($item->status === 'ready')>Pronto</option>
                                                    <option value="delivered" @selected($item->status === 'delivered')>Entregue</option>
                                                    <option value="cancelled" @selected($item->status === 'cancelled')>Cancelado</option>
                                                </select>
                                            </form>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <form id="remove-item-{{ $item->id }}" action="{{ route('orders.remove-item', $item->id) }}" method="POST" class="inline-block">
                                                @csrf
                                            </form>
                                            <button onclick="removeItem({{ $item->id }})" class="text-red-500 hover:text-red-700 transition-colors p-1 rounded hover:bg-red-50 dark:hover:bg-red-900/20">
                                                <i class="mdi mdi-delete text-lg"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                            <i class="mdi mdi-cart-outline text-4xl mb-2 block"></i>
                                            Nenhum item adicionado ao pedido
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Quick Menu -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white flex items-center gap-2">
                            <i class="mdi mdi-food text-orange-500"></i> Menu Rápido
                        </h3>
                        <div class="relative w-64">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="mdi mdi-magnify text-gray-400"></i>
                            </span>
                            <input type="text" id="quickMenuSearch"
                                class="w-full pl-10 pr-4 py-1.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm focus:ring-orange-500 focus:border-orange-500"
                                placeholder="Buscar produtos...">
                        </div>
                    </div>

                    <div class="p-4">
                        <!-- Categories -->
                        <div class="flex gap-2 overflow-x-auto pb-4 scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-600">
                            @foreach ($categories as $category)
                                <button @click="activeTab = '{{ $category->id }}'"
                                    :class="{ 'bg-orange-500 text-white shadow-md': activeTab === '{{ $category->id }}', 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600': activeTab !== '{{ $category->id }}' }"
                                    class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 whitespace-nowrap flex items-center gap-2">
                                    {{ $category->name }}
                                    <span class="bg-white/20 px-1.5 rounded text-xs">{{ $category->products->count() }}</span>
                                </button>
                            @endforeach
                        </div>

                        <!-- Products Grid -->
                        <div class="mt-2">
                            @foreach ($categories as $category)
                                <div x-show="activeTab === '{{ $category->id }}'" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 product-container">
                                    @foreach ($category->products as $product)
                                        <div class="product-item bg-white dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600 p-3 hover:shadow-md transition-shadow cursor-pointer group"
                                            onclick="addProduct({{ $product->id }})">
                                            <h6 class="product-name font-medium text-gray-800 dark:text-white mb-2 line-clamp-2 text-sm h-10">{{ $product->name }}</h6>
                                            <div class="flex justify-between items-center mt-auto">
                                                <span class="text-orange-600 dark:text-orange-400 font-bold text-sm">
                                                    MZN {{ number_format($product->price, 2, ',', '.') }}
                                                </span>
                                                <button class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 dark:bg-orange-900/30 dark:text-orange-400 flex items-center justify-center group-hover:bg-orange-500 group-hover:text-white transition-colors">
                                                    <i class="mdi mdi-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Customer Info & Summary -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Customer Info -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                        <i class="mdi mdi-account text-blue-500"></i> Informações do Cliente
                    </h3>
                    <form action="{{ route('orders.update', $order) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nome do Cliente</label>
                                <input type="text" name="customer_name" value="{{ $order->customer_name }}"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-orange-500 focus:border-orange-500"
                                    placeholder="Nome do Cliente">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Observações</label>
                                <textarea name="notes" rows="3"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-orange-500 focus:border-orange-500"
                                    placeholder="Observações">{{ $order->notes }}</textarea>
                            </div>
                            <button type="submit" class="w-full py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors flex items-center justify-center gap-2">
                                <i class="mdi mdi-content-save"></i> Salvar Alterações
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Summary & Actions -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                        <i class="mdi mdi-cash-register text-green-500"></i> Resumo
                    </h3>
                    
                    <div class="flex justify-between items-center mb-6 p-4 bg-gray-50 dark:bg-gray-900/50 rounded-lg">
                        <span class="text-gray-600 dark:text-gray-400 font-medium">Total</span>
                        <span class="text-2xl font-bold text-orange-600 dark:text-orange-400">
                            MZN {{ number_format($order->total_amount, 2, ',', '.') }}
                        </span>
                    </div>

                    <div class="space-y-3">
                        @if ($order->status === 'completed' && !$order->is_paid)
                            <button @click="paymentModalOpen = true" class="w-full py-3 px-4 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg shadow-sm transition-colors flex items-center justify-center gap-2">
                                <i class="mdi mdi-cash-multiple"></i> Registrar Pagamento
                            </button>
                        @endif

                        @if ($order->status === 'active' && $order->items->count() > 0)
                            <form action="{{ route('orders.complete', $order) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full py-3 px-4 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg shadow-sm transition-colors flex items-center justify-center gap-2">
                                    <i class="mdi mdi-check-circle"></i> Finalizar Pedido
                                </button>
                            </form>
                        @endif

                        @if ($order->status === 'completed' && $order->is_paid)
                            <button disabled class="w-full py-3 px-4 bg-gray-400 text-white font-bold rounded-lg cursor-not-allowed flex items-center justify-center gap-2">
                                <i class="mdi mdi-check-circle"></i> Pedido Pago
                            </button>
                        @endif

                        @if ($order->status === 'active')
                            <form action="{{ route('orders.cancel', $order) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja cancelar?')">
                                @csrf
                                <button type="submit" class="w-full py-3 px-4 bg-red-600 hover:bg-red-700 text-white font-bold rounded-lg shadow-sm transition-colors flex items-center justify-center gap-2">
                                    <i class="mdi mdi-delete"></i> Cancelar Pedido
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Modal -->
        <div x-show="paymentModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">
            
            <div class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity" @click="paymentModalOpen = false"></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-gray-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-gray-200 dark:border-gray-700">
                    <form action="{{ route('orders.pay', $order) }}" method="POST">
                        @csrf
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 flex justify-between items-center">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                <i class="mdi mdi-cash-multiple text-green-500"></i>
                                Registrar Pagamento
                            </h3>
                            <button type="button" @click="paymentModalOpen = false" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 transition-colors">
                                <i class="mdi mdi-close text-xl"></i>
                            </button>
                        </div>

                        <div class="p-6 space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Método de Pagamento</label>
                                <select name="payment_method" x-model="paymentMethod" required
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-orange-500 focus:border-orange-500">
                                    <option value="">Selecione um método</option>
                                    <option value="cash">Dinheiro</option>
                                    <option value="card">Cartão</option>
                                    <option value="mpesa">M-Pesa</option>
                                    <option value="emola">E-Mola</option>
                                    <option value="mkesh">M-Kesh</option>
                                </select>
                            </div>

                            <div x-show="paymentMethod === 'cash'" class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Valor Recebido</label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">MZN</span>
                                        <input type="number" name="cash_amount" x-model="cashAmount" @input="calculateChange()" step="0.01" min="{{ $order->total_amount }}"
                                            class="w-full pl-12 rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-orange-500 focus:border-orange-500">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Troco</label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">MZN</span>
                                        <input type="text" x-model="changeAmount" readonly
                                            class="w-full pl-12 rounded-lg border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-600 text-gray-900 dark:text-white cursor-not-allowed">
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="amount_paid" value="{{ $order->total_amount }}">

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Observações</label>
                                <textarea name="notes" rows="2" class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-orange-500 focus:border-orange-500"></textarea>
                            </div>

                            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800 rounded-lg p-4 flex justify-between items-center">
                                <span class="text-blue-800 dark:text-blue-200 font-medium">Total a Pagar:</span>
                                <span class="text-xl font-bold text-blue-600 dark:text-blue-300">MZN {{ number_format($order->total_amount, 2, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 flex justify-end gap-3">
                            <button type="button" @click="paymentModalOpen = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600">
                                Cancelar
                            </button>
                            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 flex items-center gap-2">
                                <i class="mdi mdi-check-circle"></i> Confirmar Pagamento
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function removeItem(itemId) {
            Swal.fire({
                title: 'Confirmar remoção',
                text: "Deseja remover este item do pedido?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sim, remover',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#6B7280',
                background: document.documentElement.classList.contains('dark') ? '#1F2937' : '#FFFFFF',
                color: document.documentElement.classList.contains('dark') ? '#FFFFFF' : '#1F2937'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`remove-item-${itemId}`).submit();
                }
            });
        }

        document.getElementById('quickMenuSearch').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            document.querySelectorAll('.product-item').forEach(item => {
                const productName = item.querySelector('.product-name').textContent.toLowerCase();
                item.style.display = productName.includes(searchTerm) ? '' : 'none';
            });
        });

        function addProduct(productId) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `{{ route('orders.add-item', $order->id) }}`;

            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';

            const product = document.createElement('input');
            product.type = 'hidden';
            product.name = 'product_id';
            product.value = productId;

            const quantity = document.createElement('input');
            quantity.type = 'hidden';
            quantity.name = 'quantity';
            quantity.value = '1';

            form.appendChild(csrf);
            form.appendChild(product);
            form.appendChild(quantity);
            document.body.appendChild(form);
            form.submit();
        }
    </script>
    @endpush
@endsection
