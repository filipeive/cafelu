@extends('layouts.app')

@section('title', 'PDV - ' . \App\Models\Setting::get('company_name', __('messages.pos_system')))

@section('content')
    <div class="h-[calc(100vh-5.5rem)] flex flex-col lg:flex-row gap-5" x-data="posSystem({ 
        products: {{ json_encode($products) }}, 
        categories: {{ json_encode($categories) }},
        csrfToken: '{{ csrf_token() }}'
    })">

        <!-- Products Area (Left Column) -->
        <div class="w-full lg:flex-1 min-w-0 flex flex-col h-full space-y-3.5">
            <!-- Search & Categories Card -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl p-4 border border-gray-100 dark:border-gray-700/80 shadow-xs">
                <div class="flex flex-col md:flex-row gap-3 items-center">
                    <div class="relative flex-grow w-full">
                        <i class="mdi mdi-magnify absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                        <input type="text" x-model="searchQuery"
                            class="w-full pl-10 pr-9 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-2xl text-gray-900 dark:text-white text-xs font-medium focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all"
                            placeholder="{{ __('messages.search_products') }}...">
                        <button x-show="searchQuery" @click="searchQuery = ''" type="button" 
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 cursor-pointer">
                            <i class="mdi mdi-close-circle text-sm"></i>
                        </button>
                    </div>

                    <!-- Mobile Category Select -->
                    <select x-model="selectedCategory"
                        class="w-full md:w-auto px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-2xl text-gray-700 dark:text-gray-200 text-xs font-bold focus:ring-2 focus:ring-orange-500 focus:border-transparent md:hidden">
                        <option value="all">{{ __('messages.all_categories') }}</option>
                        <template x-for="category in categories" :key="category.id">
                            <option :value="category.id" x-text="category.name"></option>
                        </template>
                    </select>
                </div>

                <!-- Category Buttons (Desktop) -->
                <div class="hidden md:flex flex-wrap gap-2 mt-3 overflow-x-auto pb-1 scrollbar-thin">
                    <button type="button" @click="selectedCategory = 'all'"
                        class="px-3.5 py-2 rounded-2xl text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer"
                        :class="selectedCategory === 'all' 
                            ? 'bg-orange-500 text-white shadow-md shadow-orange-500/20' 
                            : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'">
                        <i class="mdi mdi-view-grid-outline"></i>
                        <span>{{ __('messages.all') }}</span>
                        <span class="px-1.5 py-0.2 rounded-full text-[10px]"
                            :class="selectedCategory === 'all' ? 'bg-white/20 text-white' : 'bg-gray-200 dark:bg-gray-600 text-gray-600 dark:text-gray-300'"
                            x-text="products.length"></span>
                    </button>

                    <template x-for="category in categories" :key="category.id">
                        <button type="button" @click="selectedCategory = category.id"
                            class="px-3.5 py-2 rounded-2xl text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer"
                            :class="selectedCategory == category.id 
                                ? 'bg-orange-500 text-white shadow-md shadow-orange-500/20' 
                                : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'">
                            <i :class="['mdi', getCategoryIcon(category.id)]"></i>
                            <span x-text="category.name"></span>
                            <span class="px-1.5 py-0.2 rounded-full text-[10px]"
                                :class="selectedCategory == category.id ? 'bg-white/20 text-white' : 'bg-gray-200 dark:bg-gray-600 text-gray-600 dark:text-gray-300'"
                                x-text="products.filter(p => p.category_id == category.id).length"></span>
                        </button>
                    </template>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="flex-grow overflow-y-auto pr-1 custom-scrollbar">
                <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-3.5">
                    <template x-for="product in filteredProducts" :key="product.id">
                        <div class="group bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700/80 shadow-xs hover:shadow-xl hover:-translate-y-1 transition-all duration-200 cursor-pointer flex flex-col justify-between overflow-hidden select-none"
                            :class="{'opacity-60 cursor-not-allowed': product.stock_quantity <= 0}"
                            @click="addToCart(product)">
                            
                            <!-- Image / Default Icon Container -->
                            <div class="relative h-32 overflow-hidden bg-gray-50 dark:bg-gray-900 flex items-center justify-center">
                                <!-- If product has valid image_url: show image -->
                                <template x-if="product.image_url">
                                    <img :src="product.image_url" :alt="product.name"
                                        x-on:error="product.image_url = null"
                                        class="absolute inset-0 w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-300">
                                </template>

                                <!-- If product does not have an image: show Category / Default Icon -->
                                <template x-if="!product.image_url">
                                    <div class="absolute inset-0 w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-orange-50 via-amber-50/50 to-orange-100/60 dark:from-gray-800 dark:via-gray-800 dark:to-gray-750 p-3">
                                        <div class="w-12 h-12 rounded-2xl bg-white/80 dark:bg-gray-700/80 shadow-xs flex items-center justify-center text-orange-500 dark:text-orange-400 group-hover:scale-110 transition-transform duration-300">
                                            <i :class="['mdi text-2xl', getProductIcon(product)]"></i>
                                        </div>
                                        <span class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mt-1.5 truncate max-w-full px-2" x-text="getCategoryName(product.category_id)"></span>
                                    </div>
                                </template>

                                <!-- Hover Quick Add Overlay -->
                                <div class="absolute inset-0 bg-black/25 backdrop-blur-[1px] flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200 z-10"
                                     x-show="product.stock_quantity > 0">
                                    <span class="px-3 py-1.5 rounded-full bg-orange-500 text-white text-xs font-bold shadow-lg flex items-center gap-1">
                                        <i class="mdi mdi-plus"></i> Adicionar
                                    </span>
                                </div>

                                <!-- Stock Badge -->
                                <div class="absolute top-2 right-2 z-20">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold shadow-xs backdrop-blur-md"
                                        :class="{
                                            'bg-emerald-50/90 text-emerald-700 border border-emerald-200/60 dark:bg-emerald-950/80 dark:text-emerald-300': product.stock_quantity > 10,
                                            'bg-amber-50/90 text-amber-700 border border-amber-200/60 dark:bg-amber-950/80 dark:text-amber-300': product.stock_quantity <= 10 && product.stock_quantity > 0,
                                            'bg-rose-50/90 text-rose-700 border border-rose-200/60 dark:bg-rose-950/80 dark:text-rose-300': product.stock_quantity <= 0
                                        }">
                                        <span x-text="product.stock_quantity > 0 ? product.stock_quantity + ' un.' : 'Esgotado'"></span>
                                    </span>
                                </div>
                            </div>

                            <!-- Product Info -->
                            <div class="p-3.5 flex flex-col justify-between flex-grow text-center">
                                <h6 class="text-xs font-bold text-gray-800 dark:text-gray-100 line-clamp-2 leading-snug mb-1.5"
                                    :title="product.name" x-text="product.name"></h6>
                                <div>
                                    <span class="text-sm font-extrabold text-orange-600 dark:text-orange-400">
                                        MZN <span x-text="formatMoney(product.selling_price || product.price)"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Empty State -->
                    <div x-show="filteredProducts.length === 0" class="col-span-full text-center py-12 bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700/80">
                        <i class="mdi mdi-package-variant-closed text-4xl text-gray-300 dark:text-gray-600 mb-2 block"></i>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('messages.no_products_found') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cart & Checkout Area (Right Column) -->
        <div class="w-full lg:w-[420px] xl:w-[460px] flex-shrink-0 flex flex-col h-full bg-white dark:bg-gray-800 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700/80 overflow-hidden">
            <!-- Cart Header -->
            <div class="p-3.5 px-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50/80 dark:bg-gray-900/40 flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-xl bg-orange-50 dark:bg-orange-950/40 text-orange-500 flex items-center justify-center">
                        <i class="mdi mdi-cart-outline text-lg"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-black uppercase tracking-wider text-gray-900 dark:text-white leading-tight">
                            {{ __('messages.current_order') }}
                        </h4>
                        <span class="text-[10px] text-gray-400" x-text="cart.length + (cart.length === 1 ? ' item adicionado' : ' itens adicionados')"></span>
                    </div>
                </div>

                <!-- Customer Input (Inline & Compact) -->
                <div class="flex items-center gap-1.5 px-2.5 py-1 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 text-xs w-44">
                    <i class="mdi mdi-account-outline text-gray-400 text-xs"></i>
                    <input type="text" x-model="customerName" placeholder="Cliente..." 
                           class="w-full bg-transparent border-0 p-0 text-xs text-gray-800 dark:text-gray-200 font-medium focus:ring-0 placeholder:text-gray-400">
                </div>

                <!-- Clear Cart -->
                <button @click="clearCart()" type="button"
                    class="text-rose-500 hover:text-rose-700 hover:bg-rose-50 dark:hover:bg-rose-950/40 p-1.5 rounded-xl transition-colors cursor-pointer disabled:opacity-30 disabled:cursor-not-allowed"
                    title="{{ __('messages.clear') }}"
                    :disabled="cart.length === 0">
                    <i class="mdi mdi-trash-can-outline text-base"></i>
                </button>
            </div>

            <!-- Cart Items List (Spacious & Scrollable) -->
            <div class="flex-1 overflow-y-auto p-3.5 space-y-2 custom-scrollbar min-h-[160px]">
                <template x-for="(item, index) in cart" :key="index">
                    <div class="flex items-center justify-between p-2.5 bg-gray-50/70 dark:bg-gray-900/40 hover:bg-orange-50/30 dark:hover:bg-gray-750 rounded-2xl border border-gray-150 dark:border-gray-700/60 transition-colors">
                        <div class="flex-grow min-w-0 mr-2">
                            <h6 class="text-xs font-bold text-gray-800 dark:text-white truncate" x-text="item.name"></h6>
                            <div class="text-[11px] text-gray-400 mt-0.5">
                                MZN <span x-text="formatMoney(item.price)"></span> un.
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <!-- Stepper -->
                            <div class="flex items-center bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-600 h-7 overflow-hidden shadow-2xs">
                                <button type="button" @click="updateQuantity(index, -1)"
                                    class="w-7 h-full flex items-center justify-center text-gray-500 hover:text-orange-600 hover:bg-orange-50 dark:hover:bg-orange-950/30 transition-colors cursor-pointer">
                                    <i class="mdi mdi-minus text-xs"></i>
                                </button>
                                <span class="w-7 text-center text-xs font-black text-gray-800 dark:text-white"
                                    x-text="item.quantity"></span>
                                <button type="button" @click="updateQuantity(index, 1)"
                                    class="w-7 h-full flex items-center justify-center text-gray-500 hover:text-orange-600 hover:bg-orange-50 dark:hover:bg-orange-950/30 transition-colors cursor-pointer">
                                    <i class="mdi mdi-plus text-xs"></i>
                                </button>
                            </div>

                            <!-- Line Total -->
                            <div class="text-right min-w-[65px]">
                                <span class="text-xs font-black text-gray-900 dark:text-white">
                                    MZN <span x-text="formatMoney(item.quantity * item.price)"></span>
                                </span>
                            </div>

                            <!-- Remove Button -->
                            <button type="button" @click="removeFromCart(index)"
                                class="text-gray-300 hover:text-rose-500 p-1 rounded-lg transition-colors cursor-pointer"
                                title="Remover item">
                                <i class="mdi mdi-close text-sm"></i>
                            </button>
                        </div>
                    </div>
                </template>

                <div x-show="cart.length === 0" class="text-center py-12 text-gray-400 dark:text-gray-500">
                    <div class="w-14 h-14 rounded-2xl bg-gray-50 dark:bg-gray-750 mx-auto flex items-center justify-center mb-2.5 text-gray-300 dark:text-gray-600">
                        <i class="mdi mdi-cart-arrow-down text-2xl"></i>
                    </div>
                    <p class="text-xs font-bold text-gray-600 dark:text-gray-300">{{ __('messages.empty_cart') }}</p>
                    <span class="text-[11px] text-gray-400 block mt-0.5">Selecione os produtos ao lado para iniciar a venda</span>
                </div>
            </div>

            <!-- Cart Footer & Checkout Controls (Compact, Clean & Always Visible) -->
            <div class="p-3.5 border-t border-gray-100 dark:border-gray-700 bg-gray-50/90 dark:bg-gray-900/70 space-y-2.5 flex-shrink-0">
                <!-- Totals Row -->
                <div class="flex items-baseline justify-between px-1">
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Total do Pedido:</span>
                    <span class="text-xl font-black text-orange-600 dark:text-orange-400">
                        MZN <span x-text="formatMoney(cartTotal)"></span>
                    </span>
                </div>

                <!-- Segmented Payment Methods (4 tabs in 1 compact row) -->
                <div class="grid grid-cols-4 gap-1 p-1 bg-gray-200/60 dark:bg-gray-800 rounded-2xl">
                    <template x-for="method in paymentMethods" :key="method.id">
                        <button type="button" @click="selectPayment(method.id)"
                            class="py-1.5 px-1 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-1 cursor-pointer select-none"
                            :class="paymentMethod === method.id 
                                ? 'bg-white dark:bg-gray-700 text-orange-600 dark:text-orange-400 shadow-xs' 
                                : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white'">
                            <i :class="['mdi text-sm', method.icon, paymentMethod === method.id ? 'text-orange-500' : '']"></i>
                            <span class="text-[11px] truncate" x-text="method.name"></span>
                        </button>
                    </template>
                </div>

                <!-- Dynamic Payment Details -->
                <div x-show="paymentMethod === 'cash'" class="space-y-1.5">
                    <div class="flex items-center gap-2">
                        <div class="relative flex-1">
                            <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-[10px] font-bold text-gray-400">MZN</span>
                            <input type="number" x-model="payments.cash" step="any"
                                class="w-full pl-10 pr-2.5 py-1 text-xs font-bold border border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-1 focus:ring-orange-500"
                                placeholder="Valor recebido...">
                        </div>
                        <template x-if="changeAmount > 0">
                            <div class="px-2.5 py-1 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200/60 text-emerald-700 dark:text-emerald-300 text-xs font-black flex items-center gap-1 whitespace-nowrap">
                                <i class="mdi mdi-cash-refund text-sm"></i>
                                <span>Troco: MZN <span x-text="formatMoney(changeAmount)"></span></span>
                            </div>
                        </template>
                    </div>

                    <!-- Quick Cash Tender Chips -->
                    <div x-show="cartTotal > 0" class="flex items-center gap-1 overflow-x-auto pb-0.5 scrollbar-thin">
                        <button type="button" @click="setCashAmount('exact')"
                            class="px-2 py-0.5 rounded-lg text-[10px] font-bold bg-emerald-100/70 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300 hover:bg-emerald-200 transition-colors cursor-pointer">
                            Exato
                        </button>
                        <template x-for="amt in [50, 100, 200, 500, 1000]" :key="amt">
                            <button type="button" @click="setCashAmount(amt)"
                                class="px-2 py-0.5 rounded-lg text-[10px] font-bold bg-gray-200/80 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors cursor-pointer">
                                <span x-text="'+' + amt"></span>
                            </button>
                        </template>
                    </div>
                </div>

                <div x-show="paymentMethod !== 'cash'" class="text-[11px] text-gray-500 dark:text-gray-400 flex items-center justify-between px-1">
                    <span>Pagamento integral via <strong class="capitalize" x-text="paymentMethod"></strong></span>
                    <span class="font-bold text-gray-800 dark:text-gray-200">MZN <span x-text="formatMoney(cartTotal)"></span></span>
                </div>

                <!-- Primary Big Finish Button (ALWAYS in view) -->
                <button type="button" @click="processSale()" :disabled="cart.length === 0 || isLoading"
                    class="w-full py-3 px-4 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-black text-sm shadow-md shadow-emerald-600/25 transition-all flex items-center justify-between disabled:opacity-40 disabled:cursor-not-allowed cursor-pointer">
                    <span class="flex items-center gap-2">
                        <i class="mdi text-lg" :class="isLoading ? 'mdi-loading mdi-spin' : 'mdi-check-circle-outline'"></i>
                        <span x-text="isLoading ? 'Processando...' : 'Concluir Venda'"></span>
                    </span>
                    <span class="text-xs font-black bg-emerald-700/60 px-2.5 py-0.5 rounded-lg" x-text="'MZN ' + formatMoney(cartTotal)"></span>
                </button>

                <!-- Auxiliary Action Buttons Row -->
                <div class="grid grid-cols-3 gap-1.5 pt-0.5">
                    <button type="button" @click="holdOrder()" :disabled="cart.length === 0 || isLoading"
                        class="py-1.5 px-2 rounded-xl bg-gray-200/70 hover:bg-gray-200 dark:bg-gray-750 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-[11px] font-bold transition-all flex items-center justify-center gap-1 disabled:opacity-30 disabled:cursor-not-allowed cursor-pointer"
                        title="Guardar em mesa ou cliente temporário">
                        <i class="mdi mdi-pause-circle-outline text-amber-500"></i>
                        <span>Guardar</span>
                    </button>

                    <button type="button" @click="fetchHeldOrders()"
                        class="py-1.5 px-2 rounded-xl bg-gray-200/70 hover:bg-gray-200 dark:bg-gray-750 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-[11px] font-bold transition-all flex items-center justify-center gap-1 cursor-pointer"
                        title="Recuperar pedidos pendentes">
                        <i class="mdi mdi-clipboard-text-clock-outline text-blue-500"></i>
                        <span>Recuperar</span>
                    </button>

                    <button type="button" @click="registerAsDebt()" :disabled="cart.length === 0 || isLoading"
                        class="py-1.5 px-2 rounded-xl bg-gray-200/70 hover:bg-gray-200 dark:bg-gray-750 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-[11px] font-bold transition-all flex items-center justify-center gap-1 disabled:opacity-30 disabled:cursor-not-allowed cursor-pointer"
                        title="Registrar como dívida / conta corrente">
                        <i class="mdi mdi-account-cash-outline text-orange-500"></i>
                        <span>Dívida</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Held Orders Modal -->
        <div x-show="showHeldOrdersModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="showHeldOrdersModal = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                <div class="relative inline-block align-middle bg-white dark:bg-gray-800 rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:max-w-lg w-full border border-gray-100 dark:border-gray-700">
                    <div class="px-6 py-5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <i class="mdi mdi-clipboard-text-clock-outline text-2xl"></i>
                            <h3 class="text-base font-bold leading-tight">{{ __('messages.held_orders') }}</h3>
                        </div>
                        <button type="button" @click="showHeldOrdersModal = false" class="text-white/80 hover:text-white rounded-lg p-1 cursor-pointer">
                            <i class="mdi mdi-close text-xl"></i>
                        </button>
                    </div>

                    <div class="p-6">
                        <div class="max-h-80 overflow-y-auto space-y-2 custom-scrollbar pr-1">
                            <template x-for="order in heldOrders" :key="order.id">
                                <div class="p-3 bg-gray-50 dark:bg-gray-900/60 hover:bg-blue-50/60 dark:hover:bg-blue-950/40 rounded-2xl border border-gray-100 dark:border-gray-700/60 transition-all cursor-pointer flex items-center justify-between"
                                    @click="retrieveOrder(order.id)">
                                    <div>
                                        <p class="text-xs font-bold text-gray-900 dark:text-white"
                                            x-text="order.customer_name || 'Cliente Geral'"></p>
                                        <div class="flex items-center gap-2 text-[11px] text-gray-500 mt-0.5">
                                            <span class="flex items-center gap-1">
                                                <i class="mdi mdi-table-furniture"></i>
                                                <span x-show="order.table_number">{{ __('messages.tables') }}: <strong x-text="order.table_number"></strong></span>
                                                <span x-show="order.is_temporary" class="text-amber-600">(Temp)</span>
                                                <span x-show="!order.table_number">{{ __('messages.no_table') }}</span>
                                            </span>
                                            <span>•</span>
                                            <span x-text="new Date(order.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})"></span>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs font-black text-orange-600 dark:text-orange-400">
                                            MZN <span x-text="formatMoney(order.total_amount)"></span>
                                        </p>
                                        <span class="text-[10px] bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-300 border border-amber-200/50 px-2 py-0.5 rounded-full font-bold">
                                            {{ __('messages.pending') }}
                                        </span>
                                    </div>
                                </div>
                            </template>

                            <div x-show="heldOrders.length === 0" class="text-center py-8 text-gray-400 text-xs">
                                {{ __('messages.no_held_orders') }}
                            </div>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-100 dark:border-gray-700 flex justify-end">
                        <button type="button" @click="showHeldOrdersModal = false"
                            class="px-5 py-2 rounded-xl border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 font-bold text-xs hover:bg-gray-100 dark:hover:bg-gray-800 transition-all cursor-pointer">
                            {{ __('messages.close') }}
                        </button>
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
                background: rgba(0, 0, 0, 0.03);
            }

            .custom-scrollbar::-webkit-scrollbar-thumb {
                background: rgba(0, 0, 0, 0.15);
                border-radius: 9999px;
            }

            .dark .custom-scrollbar::-webkit-scrollbar-track {
                background: rgba(255, 255, 255, 0.03);
            }

            .dark .custom-scrollbar::-webkit-scrollbar-thumb {
                background: rgba(255, 255, 255, 0.15);
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
                        { id: 'cash', name: '{{ __('messages.cash') }}', icon: 'mdi-cash-multiple', colorClass: 'text-emerald-500' },
                        { id: 'card', name: '{{ __('messages.card') }}', icon: 'mdi-credit-card', colorClass: 'text-blue-500' },
                        { id: 'mpesa', name: '{{ __('messages.mpesa') }}', icon: 'mdi-cellphone-text', colorClass: 'text-rose-500' },
                        { id: 'emola', name: '{{ __('messages.emola') }}', icon: 'mdi-wallet-outline', colorClass: 'text-amber-500' }
                    ],

                    init() {
                        const savedCart = localStorage.getItem('pos_cart');
                        if (savedCart) {
                            try {
                                this.cart = JSON.parse(savedCart);
                            } catch (e) {
                                this.cart = [];
                            }
                        }
                    },

                    get filteredProducts() {
                        return this.products.filter(product => {
                            const matchesCategory = this.selectedCategory === 'all' || product.category_id == this.selectedCategory;
                            const matchesSearch = !this.searchQuery || product.name.toLowerCase().includes(this.searchQuery.toLowerCase());
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
                        return parseFloat(value || 0).toFixed(2).replace('.', ',');
                    },

                    getProductIcon(product) {
                        const catId = parseInt(product.category_id);
                        switch (catId) {
                            case 1: return 'mdi-glass-cocktail';
                            case 2: return 'mdi-bottle-soda-outline';
                            case 3: return 'mdi-silverware-fork-knife';
                            case 4: return 'mdi-glass-mug-variant';
                            case 5: return 'mdi-cupcake';
                            default: return 'mdi-food-fork-drink';
                        }
                    },

                    getCategoryName(categoryId) {
                        const cat = this.categories.find(c => c.id == categoryId);
                        return cat ? cat.name : 'Produto';
                    },

                    getCategoryIcon(categoryId) {
                        const catId = parseInt(categoryId);
                        switch (catId) {
                            case 1: return 'mdi-glass-cocktail';
                            case 2: return 'mdi-bottle-soda-outline';
                            case 3: return 'mdi-silverware-fork-knife';
                            case 4: return 'mdi-glass-mug-variant';
                            case 5: return 'mdi-cupcake';
                            default: return 'mdi-tag-outline';
                        }
                    },

                    setCashAmount(amount) {
                        if (amount === 'exact') {
                            this.payments.cash = this.cartTotal;
                        } else {
                            const current = parseFloat(this.payments.cash) || 0;
                            this.payments.cash = current + amount;
                        }
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
                        if (this.paymentMethod === 'cash' && (!this.payments.cash || parseFloat(this.payments.cash) === 0)) {
                            this.payments.cash = this.cartTotal;
                        }
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
                            this.paymentMethod = 'cash';
                            this.lastOrderId = null;
                        }
                    },

                    saveCart() {
                        localStorage.setItem('pos_cart', JSON.stringify(this.cart));
                    },

                    selectPayment(method) {
                        this.paymentMethod = method;
                        if (method !== 'cash') {
                            this.payments[method] = this.cartTotal;
                        } else {
                            if (!this.payments.cash || parseFloat(this.payments.cash) === 0) {
                                this.payments.cash = this.cartTotal;
                            }
                        }
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

                        const cash = parseFloat(this.payments.cash) || 0;
                        const card = parseFloat(this.payments.card) || 0;
                        const mpesa = parseFloat(this.payments.mpesa) || 0;
                        const emola = parseFloat(this.payments.emola) || 0;

                        const totalPaid = cash + card + mpesa + emola;

                        if (totalPaid < this.cartTotal) {
                            const userRole = "{{ auth()->user()->role }}";
                            if (userRole !== 'admin' && userRole !== 'manager') {
                                showToast('Apenas administradores e gerentes podem registrar vendas com pagamento parcial (dívidas).', 'error');
                                return;
                            }

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
                                    return;
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

                        let method = 'multiple';
                        const methodsUsed = [cash > 0, card > 0, mpesa > 0, emola > 0].filter(Boolean).length;

                        if (methodsUsed === 1) {
                            if (cash > 0) method = 'cash';
                            else if (card > 0) method = 'card';
                            else if (mpesa > 0) method = 'mpesa';
                            else if (emola > 0) method = 'emola';
                        } else if (methodsUsed === 0) {
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
                                    this.paymentMethod = 'cash';
                                    this.lastSaleId = data.sale_id;
                                    this.lastOrderId = null;
                                    this.customerName = '';

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

                        this.isLoading = true;
                        fetch("{{ route('pos.tables') }}")
                            .then(res => res.json())
                            .then(tables => {
                                this.isLoading = false;

                                const tableOptions = tables.reduce((acc, table) => {
                                    acc[table.id] = `{{ __('messages.table') }} ${table.number} (${table.capacity} {{ __('messages.seats') }})`;
                                    return acc;
                                }, { '': '{{ __('messages.temporary_table_auto') }}' });

                                Swal.fire({
                                    title: '{{ __('messages.hold_order') }}',
                                    html: `
                                        <div class="text-left space-y-4">
                                            <div>
                                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">{{ __('messages.customer_name') }}</label>
                                                <input id="swal-customer-name" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white text-xs font-medium focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-colors" placeholder="{{ __('messages.customer_name') }}">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">{{ __('messages.select_table') }}</label>
                                                <select id="swal-table-select" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white text-xs font-medium focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-colors">
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
                                                    this.resetPayments();
                                                    this.paymentMethod = 'cash';
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
                                return;
                            }
                        }

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
