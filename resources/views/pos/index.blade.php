@extends('layouts.app')

@section('title', 'POS - ' . __('messages.pos_system'))

@section('content')
    <div class="h-[calc(100vh-6rem)] flex flex-col lg:flex-row gap-6" x-data="posSystem({ 
                                                                                                products: {{ json_encode($products) }}, 
                                                                                                categories: {{ json_encode($categories) }},
                                                                                                csrfToken: '{{ csrf_token() }}'
                                                                                            })">

        <!-- Products Area -->
        <div class="w-full lg:w-2/3 flex flex-col h-full">
            <!-- Search & Filters -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-4">
                <div class="flex flex-col md:flex-row gap-4 items-center">
                    <div class="relative flex-grow w-full">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="mdi mdi-magnify text-gray-400 text-lg"></i>
                        </span>
                        <input type="text" x-model="searchQuery"
                            class="w-full pl-10 pr-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-colors"
                            placeholder="{{ __('messages.search_products') }}">
                    </div>
                    <select x-model="selectedCategory"
                        class="w-full md:w-auto px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-colors md:hidden">
                        <option value="all">{{ __('messages.all_categories') }}</option>
                        <template x-for="category in categories" :key="category.id">
                            <option :value="category.id" x-text="category.name"></option>
                        </template>
                    </select>
                </div>

                <!-- Category Buttons (Desktop) -->
                <div
                    class="hidden md:flex flex-wrap gap-2 mt-4 overflow-x-auto pb-2 scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-600">
                    <button @click="selectedCategory = 'all'" :class="{'active': selectedCategory === 'all'}"
                        class="category-btn px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 border border-transparent hover:shadow-md flex items-center gap-2 whitespace-nowrap">
                        <i class="mdi mdi-view-grid"></i>{{ __('messages.all') }}
                    </button>
                    <template x-for="category in categories" :key="category.id">
                        <button @click="selectedCategory = category.id"
                            :class="{'active': selectedCategory === category.id}"
                            class="category-btn px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 border border-transparent hover:shadow-md flex items-center gap-2 whitespace-nowrap">
                            <i class="mdi mdi-tag"></i>
                            <span x-text="category.name"></span>
                        </button>
                    </template>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="flex-grow overflow-y-auto pr-2 custom-scrollbar">
                <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4">
                    <template x-for="product in filteredProducts" :key="product.id">
                        <div class="product-item">
                            <div class="product-card group bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-200 cursor-pointer h-full flex flex-col overflow-hidden"
                                @click="addToCart(product)">
                                <div class="relative h-32 overflow-hidden bg-gray-100 dark:bg-gray-900">
                                    <!-- Placeholder (Always present as background/fallback) -->
                                    <div
                                        class="absolute inset-0 w-full h-full flex items-center justify-center bg-gradient-to-br from-indigo-50 to-orange-50 dark:from-gray-800 dark:to-gray-700">
                                        <span class="text-2xl font-bold text-gray-400 dark:text-gray-600"
                                            x-text="product.name.substring(0, 2).toUpperCase()"></span>
                                    </div>

                                    <!-- Image (Overlays placeholder) -->
                                    <template x-if="product.image_path">
                                        <img :src="'/storage/' + product.image_path" :alt="product.name"
                                            x-on:error="$el.remove()"
                                            class="absolute inset-0 w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500 z-10">
                                    </template>
                                    <div
                                        class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                        <i class="mdi mdi-plus-circle text-white text-4xl drop-shadow-lg"></i>
                                    </div>
                                    <!-- Stock Badge -->
                                    <div class="absolute top-2 right-2">
                                        <span class="px-1.5 py-0.5 rounded text-[10px] font-bold shadow-sm backdrop-blur-sm"
                                            :class="{
                                                                                                                                    'bg-green-100/90 text-green-700': product.stock_quantity > 10,
                                                                                                                                    'bg-yellow-100/90 text-yellow-700': product.stock_quantity <= 10 && product.stock_quantity > 5,
                                                                                                                                    'bg-red-100/90 text-red-700': product.stock_quantity <= 5
                                                                                                                                }">
                                            <span x-text="product.stock_quantity"></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="p-3 flex-grow flex flex-col justify-between text-center">
                                    <h6 class="text-sm font-semibold text-gray-800 dark:text-gray-100 mb-1 line-clamp-2"
                                        :title="product.name" x-text="product.name"></h6>
                                    <div>
                                        <p class="text-orange-600 font-bold">
                                            MZN <span x-text="formatMoney(product.selling_price || product.price)"></span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Empty State -->
                    <div x-show="filteredProducts.length === 0" class="col-span-full text-center py-12">
                        <i class="mdi mdi-package-variant-closed text-4xl text-gray-300 dark:text-gray-600 mb-2 block"></i>
                        <p class="text-gray-500 dark:text-gray-400">{{ __('messages.no_products_found') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cart Area -->
        <div
            class="w-full lg:w-1/3 flex flex-col h-full bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
            <!-- Cart Header -->
            <div
                class="p-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 flex justify-between items-center">
                <h4 class="text-lg font-bold text-gray-800 dark:text-white flex items-center gap-2">
                    <i class="mdi mdi-cart text-orange-500"></i>
                    {{ __('messages.current_order') }}
                </h4>
                <button @click="clearCart()"
                    class="text-red-500 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-900/20 px-3 py-1.5 rounded-lg text-sm font-medium transition-colors flex items-center gap-1"
                    :disabled="cart.length === 0">
                    <i class="mdi mdi-delete"></i> {{ __('messages.clear') }}
                </button>
            </div>

            <!-- Cart Items -->
            <div class="flex-grow overflow-y-auto p-4 space-y-3 custom-scrollbar">
                <template x-for="(item, index) in cart" :key="index">
                    <div
                        class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/30 rounded-lg border border-gray-100 dark:border-gray-700 group hover:border-orange-200 dark:hover:border-orange-900/50 transition-colors">
                        <div class="flex-grow min-w-0 mr-3">
                            <h6 class="text-sm font-medium text-gray-800 dark:text-white truncate" x-text="item.name"></h6>
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                <span x-text="item.quantity"></span> x MZN <span x-text="formatMoney(item.price)"></span>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div
                                class="flex items-center bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-600 h-8">
                                <button @click="updateQuantity(index, -1)"
                                    class="w-8 h-full flex items-center justify-center text-gray-500 hover:text-orange-600 hover:bg-orange-50 dark:hover:bg-orange-900/20 rounded-l-lg transition-colors">
                                    <i class="mdi mdi-minus text-xs"></i>
                                </button>
                                <span class="w-8 text-center text-sm font-bold text-gray-800 dark:text-white"
                                    x-text="item.quantity"></span>
                                <button @click="updateQuantity(index, 1)"
                                    class="w-8 h-full flex items-center justify-center text-gray-500 hover:text-orange-600 hover:bg-orange-50 dark:hover:bg-orange-900/20 rounded-r-lg transition-colors">
                                    <i class="mdi mdi-plus text-xs"></i>
                                </button>
                            </div>
                            <div class="text-right min-w-[80px]">
                                <div class="text-sm font-bold text-gray-800 dark:text-white">
                                    MZN <span x-text="formatMoney(item.quantity * item.price)"></span>
                                </div>
                            </div>
                            <button @click="removeFromCart(index)"
                                class="text-gray-400 hover:text-red-500 transition-colors">
                                <i class="mdi mdi-close"></i>
                            </button>
                        </div>
                    </div>
                </template>

                <div x-show="cart.length === 0" class="text-center py-8 text-gray-400 dark:text-gray-500">
                    <i class="mdi mdi-cart-outline text-4xl mb-2 block"></i>
                    <p>{{ __('messages.empty_cart') }}</p>
                </div>
            </div>

            <!-- Cart Footer -->
            <div class="p-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                <div class="space-y-2 mb-4">
                    <div class="flex justify-between text-gray-600 dark:text-gray-400">
                        <span>{{ __('messages.subtotal') }}:</span>
                        <span class="font-medium">MZN <span x-text="formatMoney(cartTotal)"></span></span>
                    </div>
                    <div class="flex justify-between text-xl font-bold text-gray-800 dark:text-white">
                        <span>{{ __('messages.total') }}:</span>
                        <span>MZN <span x-text="formatMoney(cartTotal)"></span></span>
                    </div>
                </div>

                <!-- Customer Name -->
                <div class="mb-4">
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1 uppercase tracking-wider">
                        {{ __('messages.customer_name') }}
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="mdi mdi-account text-gray-400"></i>
                        </span>
                        <input type="text" x-model="customerName"
                            class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                            placeholder="Nome do Cliente (opcional)">
                    </div>
                </div>

                <!-- Payment Methods -->
                <div class="mb-4">
                    <h5 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3 flex items-center gap-2">
                        <i class="mdi mdi-credit-card-outline"></i>
                        {{ __('messages.payment_method') }}
                    </h5>
                    <div class="grid grid-cols-2 gap-3">
                        <template x-for="method in paymentMethods" :key="method.id">
                            <div @click="selectPayment(method.id)" :class="{'selected': paymentMethod === method.id}"
                                class="payment-card cursor-pointer p-3 rounded-lg border border-gray-200 dark:border-gray-600 hover:border-orange-500 dark:hover:border-orange-500 transition-all bg-white dark:bg-gray-800 relative overflow-hidden">
                                <div class="flex items-center gap-2 mb-2 text-gray-700 dark:text-gray-200">
                                    <i :class="['mdi', method.icon, method.colorClass]"></i>
                                    <span class="text-sm font-medium" x-text="method.name"></span>
                                </div>
                                <input type="number" x-model="payments[method.id]" @click.stop
                                    x-show="paymentMethod === method.id"
                                    class="w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-1 focus:ring-orange-500 focus:border-orange-500"
                                    placeholder="0.00">
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Change Display -->
                <div
                    class="mb-4 p-3 bg-gray-100 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-600 flex items-center justify-between">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center gap-2">
                        <i class="mdi mdi-cash-refund text-green-500"></i>
                        {{ __('messages.change') }}:
                    </label>
                    <span class="text-right font-bold text-lg text-gray-800 dark:text-white w-32">
                        MZN <span x-text="formatMoney(changeAmount)"></span>
                    </span>
                </div>

                <!-- Action Buttons -->
                <div class="grid grid-cols-2 gap-2 mb-2">
                    <button @click="holdOrder()" :disabled="cart.length === 0 || isLoading"
                        class="flex flex-col items-center justify-center p-2 rounded-lg bg-yellow-100 text-yellow-700 hover:bg-yellow-200 dark:bg-yellow-900/30 dark:text-yellow-400 dark:hover:bg-yellow-900/50 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="mdi mdi-pause-circle-outline text-xl mb-1"></i>
                        <span class="text-xs font-medium">{{ __('messages.hold') }}</span>
                    </button>
                    <button @click="fetchHeldOrders()"
                        class="flex flex-col items-center justify-center p-2 rounded-lg bg-blue-100 text-blue-700 hover:bg-blue-200 dark:bg-blue-900/30 dark:text-blue-400 dark:hover:bg-blue-900/50 transition-colors">
                        <i class="mdi mdi-clipboard-text-clock-outline text-xl mb-1"></i>
                        <span class="text-xs font-medium">{{ __('messages.retrieve') }}</span>
                    </button>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <button @click="registerAsDebt()" :disabled="cart.length === 0 || isLoading"
                        class="flex flex-col items-center justify-center p-2 rounded-lg bg-orange-100 text-orange-700 hover:bg-orange-200 dark:bg-orange-900/30 dark:text-orange-400 dark:hover:bg-orange-900/50 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="mdi mdi-account-cash text-xl mb-1"></i>
                        <span class="text-xs font-medium">Registar Dívida</span>
                    </button>
                    <button @click="processSale()" :disabled="cart.length === 0 || isLoading"
                        class="flex flex-col items-center justify-center p-2 rounded-lg bg-green-600 text-white hover:bg-green-700 shadow-md hover:shadow-lg transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="mdi mdi-check-circle-outline text-xl mb-1"
                            :class="{'mdi-loading mdi-spin': isLoading, 'mdi-check-circle-outline': !isLoading}"></i>
                        <span class="text-xs font-medium"
                            x-text="isLoading ? '{{ __('messages.processing') }}' : '{{ __('messages.finish') }}'"></span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Held Orders Modal (inside Alpine scope) -->
        <div x-show="showHeldOrdersModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="showHeldOrdersModal = false">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div
                    class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">
                            {{ __('messages.held_orders') }}
                        </h3>
                        <div class="max-h-60 overflow-y-auto custom-scrollbar">
                            <template x-for="order in heldOrders" :key="order.id">
                                <div class="flex justify-between items-center p-3 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg cursor-pointer border-b border-gray-100 dark:border-gray-700"
                                    @click="retrieveOrder(order.id)">
                                    <div>
                                        <p class="font-medium text-gray-800 dark:text-white"
                                            x-text="order.customer_name || 'Cliente Geral'"></p>
                                        <p class="text-xs text-gray-500">
                                            <i class="mdi mdi-table-furniture"></i>
                                            <span x-show="order.table_number">{{ __('messages.tables') }}: <span
                                                    x-text="order.table_number"></span></span>
                                            <span x-show="order.is_temporary" class="ml-1 text-yellow-600">(Temp)</span>
                                            <span x-show="!order.table_number">{{ __('messages.no_table') }}</span>
                                        </p>
                                        <p class="text-xs text-gray-500"
                                            x-text="new Date(order.created_at).toLocaleString()">
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold text-orange-600">MZN <span
                                                x-text="formatMoney(order.total_amount)"></span></p>
                                        <span
                                            class="text-xs bg-yellow-100 text-yellow-800 px-2 py-0.5 rounded-full">{{ __('messages.pending') }}</span>
                                    </div>
                                </div>
                            </template>
                            <div x-show="heldOrders.length === 0" class="text-center py-4 text-gray-500">
                                {{ __('messages.no_held_orders') }}
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" @click="showHeldOrdersModal = false"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            {{ __('messages.close') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>

    @push('styles')
        <style>
            /* Custom Scrollbar for POS */
            .custom-scrollbar::-webkit-scrollbar {
                width: 6px;
                height: 6px;
            }

            .custom-scrollbar::-webkit-scrollbar-track {
                background: rgba(0, 0, 0, 0.05);
            }

            .custom-scrollbar::-webkit-scrollbar-thumb {
                background: rgba(0, 0, 0, 0.2);
                border-radius: 3px;
            }

            .dark .custom-scrollbar::-webkit-scrollbar-track {
                background: rgba(255, 255, 255, 0.05);
            }

            .dark .custom-scrollbar::-webkit-scrollbar-thumb {
                background: rgba(255, 255, 255, 0.2);
            }

            /* Active Category Button */
            .category-btn.active {
                background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
                color: white;
                box-shadow: 0 4px 6px -1px rgba(249, 115, 22, 0.4);
            }

            .category-btn:not(.active) {
                background-color: rgba(128, 128, 128, 0.1);
                color: var(--text-color, #4b5563);
            }

            .dark .category-btn:not(.active) {
                background-color: rgba(255, 255, 255, 0.1);
                color: #e5e7eb;
            }

            /* Selected Payment Card */
            .payment-card.selected {
                border-color: #f97316;
                background-color: rgba(249, 115, 22, 0.05);
                box-shadow: 0 0 0 1px #f97316;
            }

            .dark .payment-card.selected {
                background-color: rgba(249, 115, 22, 0.15);
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('posSystem', (config) => ({
                    products: config.products,
                    categories: config.categories,
                    csrfToken: config.csrfToken,

                    searchQuery: '',
                    customerName: '',
                    selectedCategory: 'all',
                    cart: [],
                    paymentMethod: 'cash',
                    payments: {
                        cash: '',
                        card: '',
                        mpesa: '',
                        emola: ''
                    },
                    isLoading: false,
                    lastSaleId: null,
                    lastOrderId: null,

                    // Held Orders
                    showHeldOrdersModal: false,
                    heldOrders: [],

                    paymentMethods: [
                        { id: 'cash', name: '{{ __('messages.cash') }}', icon: 'mdi-cash', colorClass: 'text-green-500' },
                        { id: 'card', name: '{{ __('messages.card') }}', icon: 'mdi-credit-card', colorClass: 'text-blue-500' },
                        { id: 'mpesa', name: '{{ __('messages.mpesa') }}', icon: 'mdi-phone', colorClass: 'text-red-500' },
                        { id: 'emola', name: '{{ __('messages.emola') }}', icon: 'mdi-wallet', colorClass: 'text-purple-500' }
                    ],

                    init() {
                        // Load cart from local storage if exists
                        const savedCart = localStorage.getItem('pos_cart');
                        if (savedCart) {
                            this.cart = JSON.parse(savedCart);
                        }
                    },

                    get filteredProducts() {
                        return this.products.filter(product => {
                            const matchesCategory = this.selectedCategory === 'all' || product.category_id == this.selectedCategory;
                            const matchesSearch = product.name.toLowerCase().includes(this.searchQuery.toLowerCase());
                            return matchesCategory && matchesSearch;
                        });
                    },

                    get cartTotal() {
                        return this.cart.reduce((total, item) => total + (item.price * item.quantity), 0);
                    },

                    get changeAmount() {
                        const paid = parseFloat(this.payments[this.paymentMethod]) || 0;
                        const total = this.cartTotal;
                        return Math.max(0, paid - total);
                    },

                    formatMoney(value) {
                        return parseFloat(value).toFixed(2).replace('.', ',');
                    },

                    addToCart(product) {
                        if (product.stock_quantity <= 0) {
                            showToast('{{ __('messages.out_of_stock') }}', 'error');
                            return;
                        }

                        const existingItem = this.cart.find(item => item.id === product.id);

                        if (existingItem) {
                            if (existingItem.quantity >= product.stock_quantity) {
                                showToast('{{ __('messages.insufficient_stock') }}', 'warning');
                                return;
                            }
                            existingItem.quantity++;
                        } else {
                            this.cart.push({
                                id: product.id,
                                name: product.name,
                                price: parseFloat(product.selling_price || product.price),
                                quantity: 1,
                                stock: product.stock_quantity
                            });
                        }
                        this.saveCart();
                    },

                    removeFromCart(index) {
                        this.cart.splice(index, 1);
                        this.saveCart();
                    },

                    updateQuantity(index, change) {
                        const item = this.cart[index];
                        const newQuantity = item.quantity + change;

                        if (newQuantity <= 0) {
                            this.removeFromCart(index);
                        } else if (newQuantity <= item.stock) {
                            item.quantity = newQuantity;
                            this.saveCart();
                        } else {
                            showToast('{{ __('messages.insufficient_stock') }}', 'warning');
                        }
                    },

                    clearCart() {
                        if (confirm('{{ __('messages.confirm_clear_cart') }}')) {
                            this.cart = [];
                            this.saveCart();
                            this.resetPayments();
                            this.paymentMethod = 'cash'; // Reset payment method
                            this.lastOrderId = null; // Clear last order context
                        }
                    },

                    saveCart() {
                        localStorage.setItem('pos_cart', JSON.stringify(this.cart));
                    },

                    selectPayment(method) {
                        this.paymentMethod = method;
                        // Optional: Auto-fill if empty? No, user wants manual control.
                    },

                    resetPayments() {
                        this.payments = {
                            cash: '',
                            card: '',
                            mpesa: '',
                            emola: ''
                        };
                    },

                    async processSale() {
                        if (this.cart.length === 0) return;

                        // Calculate total paid across all methods
                        const cash = parseFloat(this.payments.cash) || 0;
                        const card = parseFloat(this.payments.card) || 0;
                        const mpesa = parseFloat(this.payments.mpesa) || 0;
                        const emola = parseFloat(this.payments.emola) || 0;

                        const totalPaid = cash + card + mpesa + emola;

                        // Validation
                        if (totalPaid < this.cartTotal) {
                            const userRole = "{{ auth()->user()->role }}";
                            if (userRole !== 'admin' && userRole !== 'manager') {
                                showToast('Apenas administradores e gerentes podem registrar vendas com pagamento parcial (dívidas).', 'error');
                                return;
                            }

                            // If customer name is empty, prompt for it
                            if (!this.customerName || this.customerName.trim() === '') {
                                const { value: name } = await Swal.fire({
                                    title: 'Nome do Cliente',
                                    text: 'Para registrar uma dívida, é necessário informar o nome do cliente.',
                                    input: 'text',
                                    inputPlaceholder: 'Digite o nome do cliente...',
                                    showCancelButton: true,
                                    confirmButtonText: 'Confirmar',
                                    cancelButtonText: 'Cancelar',
                                    confirmButtonColor: '#F97316',
                                    background: document.documentElement.classList.contains('dark') ? '#1F2937' : '#FFFFFF',
                                    color: document.documentElement.classList.contains('dark') ? '#FFFFFF' : '#1F2937',
                                    inputValidator: (value) => {
                                        if (!value) {
                                            return 'Você precisa digitar um nome!';
                                        }
                                    }
                                });

                                if (name) {
                                    this.customerName = name;
                                } else {
                                    return; // User cancelled
                                }
                            }

                            const confirmed = await Swal.fire({
                                title: 'Pagamento Parcial',
                                text: `O valor pago (MZN ${this.formatMoney(totalPaid)}) é menor que o total (MZN ${this.formatMoney(this.cartTotal)}). Deseja registrar o restante como dívida para ${this.customerName}?`,
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Sim, registrar dívida',
                                cancelButtonText: 'Não, corrigir valor',
                                confirmButtonColor: '#F97316',
                                background: document.documentElement.classList.contains('dark') ? '#1F2937' : '#FFFFFF',
                                color: document.documentElement.classList.contains('dark') ? '#FFFFFF' : '#1F2937',
                            });

                            if (!confirmed.isConfirmed) return;
                        }

                        // Determine payment method string
                        let method = 'multiple';
                        const methodsUsed = [cash > 0, card > 0, mpesa > 0, emola > 0].filter(Boolean).length;

                        if (methodsUsed === 1) {
                            if (cash > 0) method = 'cash';
                            else if (card > 0) method = 'card';
                            else if (mpesa > 0) method = 'mpesa';
                            else if (emola > 0) method = 'emola';
                        } else if (methodsUsed === 0) {
                            // Default to cash if nothing entered but logic allows (e.g. exact change assumed?)
                            // But here we enforce explicit entry or at least one method.
                            // Actually, if totalPaid >= cartTotal, we are good.
                            // If user didn't type anything, totalPaid is 0, which fails above check.
                            // Exception: if cartTotal is 0? Unlikely.
                            method = 'cash';
                        }

                        this.isLoading = true;

                        const payload = {
                            items: this.cart.map(item => ({
                                id: item.id,
                                quantity: item.quantity,
                                price: item.price
                            })),
                            payment_method: method,
                            amount_paid: totalPaid,
                            total_amount: this.cartTotal,
                            customer_name: this.customerName || 'Cliente Geral',
                            order_id: this.lastOrderId,
                            cash_amount: cash,
                            card_amount: card,
                            mpesa_amount: mpesa,
                            emola_amount: emola
                        };

                        fetch("{{ route('sales.process') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': this.csrfToken,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(payload)
                        })
                            .then(res => res.json())
                            .then(data => {
                                this.isLoading = false;
                                if (data.success) {
                                    showToast('{{ __('messages.sale_completed') }}', 'success');
                                    this.cart = [];
                                    this.saveCart();
                                    this.resetPayments();
                                    this.paymentMethod = 'cash'; // Reset payment method
                                    this.lastSaleId = data.sale_id;
                                    this.lastOrderId = null; // Clear last order since it's now a sale
                                    this.customerName = ''; // Clear customer name

                                    // Auto print receipt
                                    if (data.sale_id) {
                                        window.printSaleRecibo(data.sale_id);
                                    }
                                } else {
                                    showToast(data.message || '{{ __('messages.error_processing_sale') }}', 'error');
                                }
                            })
                            .catch(err => {
                                this.isLoading = false;
                                console.error(err);
                                showToast('{{ __('messages.connection_error') }}', 'error');
                            });
                    },

                    holdOrder() {
                        if (this.cart.length === 0) return;

                        // First, fetch available tables
                        this.isLoading = true;
                        fetch("{{ route('pos.tables') }}")
                            .then(res => res.json())
                            .then(tables => {
                                this.isLoading = false;

                                // Build table options for select
                                const tableOptions = tables.reduce((acc, table) => {
                                    acc[table.id] = `{{ __('messages.table') }} ${table.number} (${table.capacity} {{ __('messages.seats') }})`;
                                    return acc;
                                }, { '': '{{ __('messages.temporary_table_auto') }}' });

                                Swal.fire({
                                    title: '{{ __('messages.hold_order') }}',
                                    html: `
                                                                                                                                                                                        <div class="text-left space-y-4">
                                                                                                                                                                                            <div>
                                                                                                                                                                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('messages.customer_name') }}</label>
                                                                                                                                                                                                <input id="swal-customer-name" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors" placeholder="{{ __('messages.customer_name') }}">
                                                                                                                                                                                            </div>
                                                                                                                                                                                            <div>
                                                                                                                                                                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('messages.select_table') }}</label>
                                                                                                                                                                                                <select id="swal-table-select" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors">
                                                                                                                                                                                                    ${Object.entries(tableOptions).map(([id, label]) => `<option value="${id}">${label}</option>`).join('')}
                                                                                                                                                                                                </select>
                                                                                                                                                                                            </div>
                                                                                                                                                                                        </div>
                                                                                                                                                                                    `,
                                    showCancelButton: true,
                                    confirmButtonText: '{{ __('messages.hold') }}',
                                    cancelButtonText: '{{ __('messages.cancel') }}',
                                    confirmButtonColor: '#F97316',
                                    background: document.documentElement.classList.contains('dark') ? '#1F2937' : '#FFFFFF',
                                    color: document.documentElement.classList.contains('dark') ? '#FFFFFF' : '#1F2937',
                                    preConfirm: () => {
                                        return {
                                            customerName: document.getElementById('swal-customer-name').value,
                                            tableId: document.getElementById('swal-table-select').value
                                        };
                                    }
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        this.isLoading = true;
                                        const payload = {
                                            items: this.cart.map(item => ({
                                                product_id: item.id,
                                                quantity: item.quantity,
                                                unit_price: item.price
                                            })),
                                            customer_name: result.value.customerName || 'Cliente Geral',
                                            total_amount: this.cartTotal
                                        };

                                        // Add table_id if selected
                                        if (result.value.tableId) {
                                            payload.table_id = parseInt(result.value.tableId);
                                        }

                                        fetch("{{ route('pos.hold') }}", {
                                            method: 'POST',
                                            headers: {
                                                'Content-Type': 'application/json',
                                                'X-CSRF-TOKEN': this.csrfToken
                                            },
                                            body: JSON.stringify(payload)
                                        })
                                            .then(res => res.json())
                                            .then(data => {
                                                this.isLoading = false;
                                                if (data.success) {
                                                    showToast('{{ __('messages.order_held_successfully') }}', 'success');
                                                    this.cart = [];
                                                    this.saveCart();
                                                    this.resetPayments(); // Clear amount paid
                                                    this.paymentMethod = 'cash'; // Reset payment method
                                                    this.lastOrderId = data.order_id;
                                                    this.lastSaleId = null;
                                                } else {
                                                    showToast('{{ __('messages.error_holding_order') }}', 'error');
                                                }
                                            })
                                            .catch(err => {
                                                this.isLoading = false;
                                                console.error(err);
                                                showToast('{{ __('messages.connection_error') }}', 'error');
                                            });
                                    }
                                });
                            })
                            .catch(err => {
                                this.isLoading = false;
                                console.error(err);
                                showToast('{{ __('messages.error_fetching_tables') }}', 'error');
                            });
                    },

                    fetchHeldOrders() {
                        this.isLoading = true;
                        fetch("{{ route('pos.held-orders') }}")
                            .then(res => res.json())
                            .then(data => {
                                this.heldOrders = data;
                                this.showHeldOrdersModal = true;
                                this.isLoading = false;
                            })
                            .catch(err => {
                                console.error(err);
                                this.isLoading = false;
                                showToast('{{ __('messages.error_fetching_orders') }}', 'error');
                            });
                    },

                    retrieveOrder(orderId) {
                        this.isLoading = true;
                        fetch(`/pos/retrieve-order/${orderId}`)
                            .then(res => res.json())
                            .then(data => {
                                this.isLoading = false;
                                if (data.success) {
                                    this.cart = data.cart;
                                    this.saveCart();
                                    this.lastOrderId = data.order_id;
                                    this.showHeldOrdersModal = false;
                                    showToast('{{ __('messages.order_retrieved') }}', 'success');
                                } else {
                                    showToast(data.message, 'error');
                                }
                            })
                            .catch(err => {
                                console.error(err);
                                this.isLoading = false;
                                showToast('{{ __('messages.error_retrieving_order') }}', 'error');
                            });
                    },

                    async registerAsDebt() {
                        if (this.cart.length === 0) return;

                        const userRole = "{{ auth()->user()->role }}";
                        if (userRole !== 'admin' && userRole !== 'manager') {
                            showToast('Apenas administradores e gerentes podem registrar dívidas.', 'error');
                            return;
                        }

                        // Ensure customer name is provided
                        if (!this.customerName || this.customerName.trim() === '') {
                            const { value: name } = await Swal.fire({
                                title: 'Nome do Cliente',
                                text: 'Para registrar uma dívida, é necessário informar o nome do cliente.',
                                input: 'text',
                                inputPlaceholder: 'Digite o nome do cliente...',
                                showCancelButton: true,
                                confirmButtonText: 'Confirmar',
                                cancelButtonText: 'Cancelar',
                                confirmButtonColor: '#F97316',
                                background: document.documentElement.classList.contains('dark') ? '#1F2937' : '#FFFFFF',
                                color: document.documentElement.classList.contains('dark') ? '#FFFFFF' : '#1F2937',
                                inputValidator: (value) => {
                                    if (!value) {
                                        return 'Você precisa digitar um nome!';
                                    }
                                }
                            });

                            if (name) {
                                this.customerName = name;
                            } else {
                                return; // User cancelled
                            }
                        }

                        // Confirm debt registration
                        const confirmed = await Swal.fire({
                            title: 'Registrar Dívida',
                            text: `Deseja registrar uma dívida de MZN ${this.formatMoney(this.cartTotal)} para ${this.customerName}?`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Sim, registrar',
                            cancelButtonText: 'Cancelar',
                            confirmButtonColor: '#F97316',
                            background: document.documentElement.classList.contains('dark') ? '#1F2937' : '#FFFFFF',
                            color: document.documentElement.classList.contains('dark') ? '#FFFFFF' : '#1F2937',
                        });

                        if (!confirmed.isConfirmed) return;

                        this.isLoading = true;

                        const payload = {
                            items: this.cart.map(item => ({
                                id: item.id,
                                name: item.name,
                                quantity: item.quantity,
                                price: item.price
                            })),
                            customer_name: this.customerName,
                            total_amount: this.cartTotal,
                            notes: `Dívida registrada via POS em ${new Date().toLocaleString('pt-BR')}`
                        };

                        fetch('{{ route('debts.register') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': this.csrfToken,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(payload)
                        })
                            .then(res => res.json())
                            .then(data => {
                                this.isLoading = false;
                                if (data.success) {
                                    showToast('Dívida registrada com sucesso!', 'success');
                                    this.cart = [];
                                    this.saveCart();
                                    this.customerName = '';
                                } else {
                                    showToast(data.message || 'Erro ao registrar dívida', 'error');
                                }
                            })
                            .catch(err => {
                                this.isLoading = false;
                                console.error(err);
                                showToast('Erro de conexão ao registrar dívida', 'error');
                            });
                    },

                    printLastReceipt() {
                        if (this.lastSaleId) {
                            window.printSaleRecibo(this.lastSaleId);
                        } else if (this.lastOrderId) {
                            window.printRecibo(this.lastOrderId);
                        } else {
                            showToast('{{ __('messages.no_recent_sale_or_order') }}', 'info');
                        }
                    }
                }));
            });
        </script>
    @endpush
@endsection