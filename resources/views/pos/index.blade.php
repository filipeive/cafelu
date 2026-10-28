@extends('layouts.app')

@section('title', 'PDV - ' . \App\Models\Setting::get('company_name', __('messages.pos_system')))

@section('content')
    <div class="h-[calc(100vh-5.5rem)] flex flex-col lg:flex-row gap-5" x-data="posSystem({ 
        products: {{ json_encode($products) }}, 
        categories: {{ json_encode($categories) }},
        operators: {{ json_encode($operators) }},
        currentShift: {{ json_encode($currentShift) }},
        currentUser: {{ json_encode(['id' => auth()->id(), 'name' => auth()->user()->name, 'role' => auth()->user()->role]) }},
        csrfToken: '{{ csrf_token() }}'
    })">

        <!-- Products Area (Left Column) -->
        <div class="w-full lg:flex-1 min-w-0 flex flex-col h-full space-y-3.5">
            <!-- Operator & Shift Control Bar -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl px-4 py-2.5 border border-gray-100 dark:border-gray-700/80 shadow-xs flex flex-wrap items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <!-- Current Operator -->
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-xl bg-orange-50 dark:bg-orange-950/40 text-orange-600 dark:text-orange-400 flex items-center justify-center font-black text-xs">
                            <i class="mdi mdi-account-circle text-lg"></i>
                        </div>
                        <div>
                            <span class="text-[10px] uppercase font-bold text-gray-400 block leading-tight">Operador</span>
                            <span class="text-xs font-black text-gray-800 dark:text-gray-200" x-text="currentUser.name"></span>
                        </div>
                    </div>

                    <div class="h-6 w-px bg-gray-200 dark:bg-gray-700 hidden sm:block"></div>

                    <!-- Shift Status Badge -->
                    <div class="flex items-center gap-2">
                        <template x-if="activeShift">
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-1 rounded-xl text-[11px] font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200/60 flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                    <span>Turno Aberto #<span x-text="activeShift.id"></span></span>
                                </span>
                                <span class="text-[11px] text-gray-500 hidden md:inline">
                                    Fundo: <strong>MZN <span x-text="formatMoney(activeShift.opening_balance)"></span></strong>
                                </span>
                            </div>
                        </template>
                        <template x-if="!activeShift">
                            <span class="px-2.5 py-1 rounded-xl text-[11px] font-bold bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-300 border border-amber-200/60 flex items-center gap-1.5">
                                <i class="mdi mdi-alert-circle-outline"></i>
                                <span>Sem Turno Aberto</span>
                            </span>
                        </template>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-2">
                    <template x-if="!activeShift">
                        <button type="button" @click="showOpenShiftModal = true"
                            class="px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs flex items-center gap-1.5 transition-all shadow-xs cursor-pointer">
                            <i class="mdi mdi-lock-open-variant-outline"></i>
                            <span>Abrir Turno</span>
                        </button>
                    </template>
                    <template x-if="activeShift">
                        <button type="button" @click="showCloseShiftModal = true"
                            class="px-3 py-1.5 rounded-xl bg-gray-100 hover:bg-rose-50 hover:text-rose-600 dark:bg-gray-700 dark:hover:bg-rose-950/50 dark:hover:text-rose-400 text-gray-700 dark:text-gray-200 font-bold text-xs flex items-center gap-1.5 transition-all cursor-pointer">
                            <i class="mdi mdi-lock-outline"></i>
                            <span>Fechar Turno</span>
                        </button>
                    </template>

                    <button type="button" @click="showSwitchUserModal = true"
                        class="px-3 py-1.5 rounded-xl border border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 font-bold text-xs flex items-center gap-1.5 transition-all cursor-pointer"
                        title="Trocar operador de caixa / garçom">
                        <i class="mdi mdi-account-switch-outline"></i>
                        <span class="hidden sm:inline">Trocar Operador</span>
                    </button>

                    <a href="{{ route('shifts.index') }}" target="_blank"
                        class="p-1.5 rounded-xl border border-gray-200 dark:border-gray-700 text-gray-400 hover:text-orange-500 hover:bg-orange-50 dark:hover:bg-orange-950/40 transition-colors"
                        title="Histórico de Turnos & Fecho Z">
                        <i class="mdi mdi-history text-base"></i>
                    </a>
                </div>
            </div>

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

            <!-- Documento Fiscal & NUIT Bar (Compact) -->
            <div class="px-4 py-2 bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700/80 grid grid-cols-12 gap-2 items-center">
                <div class="col-span-7 flex items-center gap-1.5">
                    <i class="mdi mdi-file-document-outline text-orange-500 text-sm"></i>
                    <select x-model="invoiceType"
                        class="w-full py-1 px-2 text-[11px] font-bold rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200 focus:ring-1 focus:ring-orange-500">
                        <option value="receipt">Recibo de Pagamento (RC)</option>
                        <option value="cash_invoice">Factura-Recibo (FR)</option>
                        <option value="invoice">Factura Comercial (FT)</option>
                    </select>
                </div>
                <div class="col-span-5 flex items-center gap-1 px-2 py-1 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                    <span class="text-[10px] font-bold text-gray-400">NUIT:</span>
                    <input type="text" x-model="customerNuit" placeholder="Opcional..."
                        class="w-full bg-transparent border-0 p-0 text-[11px] text-gray-800 dark:text-gray-200 font-semibold focus:ring-0 placeholder:text-gray-400">
                </div>
            </div>

            <!-- Retrieved Order Link Banner -->
            <div x-show="lastOrderId" x-cloak class="flex items-center justify-between px-4 py-1.5 bg-blue-50/90 dark:bg-blue-950/50 border-b border-blue-100 dark:border-blue-900/40 text-[11px] text-blue-800 dark:text-blue-300">
                <span class="flex items-center gap-1.5 font-bold">
                    <i class="mdi mdi-clipboard-text-clock-outline text-blue-600 dark:text-blue-400"></i>
                    <span>Pedido Guardado #<span x-text="lastOrderId"></span> carregado</span>
                </span>
                <button type="button" @click="lastOrderId = null" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200 text-[10px] font-semibold underline cursor-pointer">
                    Desvincular
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
                                class="text-rose-400 hover:text-white hover:bg-rose-500 p-1 rounded-lg transition-colors cursor-pointer"
                                title="Remover item">
                                <i class="mdi mdi-close text-sm"></i>
                            </button>
                        </div>
                    </div>
                </template>

                <div x-show="cart.length === 0" class="text-center py-12 text-gray-400 dark:text-gray-500">
                    <div class="w-14 h-14 rounded-2xl bg-gray-50 dark:bg-gray-700 mx-auto flex items-center justify-center mb-2.5 text-gray-300 dark:text-gray-600">
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
                <div x-show="paymentMethod === 'cash'" class="space-y-2">
                    <div>
                        <div class="flex items-center justify-between mb-1 px-0.5">
                            <label class="text-[11px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Valor Recebido
                            </label>
                            <template x-if="payments.cash && parseFloat(payments.cash) > 0">
                                <button type="button" @click="payments.cash = ''" class="text-[10px] font-bold text-gray-400 hover:text-rose-500 transition-colors cursor-pointer">
                                    Limpar
                                </button>
                            </template>
                        </div>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs font-black text-gray-400 dark:text-gray-500">MZN</span>
                            <input type="number" x-model="payments.cash" step="any" min="0"
                                class="w-full pl-12 pr-8 py-2 text-sm font-black border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-all shadow-xs"
                                placeholder="0,00">
                            <button type="button" x-show="payments.cash" @click="payments.cash = ''"
                                class="absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 cursor-pointer">
                                <i class="mdi mdi-close-circle text-base"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Troco Display (quando troco > 0) -->
                    <template x-if="changeAmount > 0">
                        <div class="p-2 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 text-xs font-black flex items-center justify-between">
                            <span class="flex items-center gap-1.5">
                                <i class="mdi mdi-cash-refund text-base text-emerald-600"></i>
                                <span>Troco a devolver:</span>
                            </span>
                            <span class="text-sm font-black text-emerald-600 dark:text-emerald-400">MZN <span x-text="formatMoney(changeAmount)"></span></span>
                        </div>
                    </template>

                    <!-- Alerta se valor recebido for menor que o total -->
                    <template x-if="payments.cash && parseFloat(payments.cash) < cartTotal && parseFloat(payments.cash) > 0">
                        <div class="p-2 rounded-xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800 text-amber-900 dark:text-amber-200 text-xs font-bold flex items-center justify-between">
                            <span class="flex items-center gap-1">
                                <i class="mdi mdi-alert-circle text-base text-amber-600"></i>
                                <span>Falta Pagar:</span>
                            </span>
                            <span class="text-xs font-black text-rose-600 dark:text-rose-400">MZN <span x-text="formatMoney(cartTotal - parseFloat(payments.cash))"></span></span>
                        </div>
                    </template>

                    <!-- Quick Cash Tender Chips (Atalhos de Cédulas) -->
                    <div x-show="cartTotal > 0" class="flex items-center gap-1.5 overflow-x-auto pb-0.5 scrollbar-thin">
                        <button type="button" @click="setCashAmount('exact')"
                            class="px-2.5 py-1 rounded-lg text-xs font-black bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300 hover:bg-emerald-200 transition-colors cursor-pointer whitespace-nowrap shadow-2xs">
                            Exato
                        </button>
                        <template x-for="amt in [50, 100, 200, 500, 1000]" :key="amt">
                            <button type="button" @click="setCashAmount(amt)"
                                :class="parseFloat(payments.cash) === amt ? 'bg-orange-500 text-white font-black' : 'bg-gray-200/90 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-bold hover:bg-gray-300 dark:hover:bg-gray-600'"
                                class="px-2 py-1 rounded-lg text-[11px] transition-colors cursor-pointer whitespace-nowrap shadow-2xs">
                                <span x-text="'MZN ' + amt"></span>
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
                    class="w-full py-3 px-4 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-black text-sm shadow-md shadow-emerald-600/25 transition-all flex items-center justify-between disabled:bg-emerald-300 dark:disabled:bg-emerald-900/50 disabled:shadow-none disabled:cursor-not-allowed cursor-pointer">
                    <span class="flex items-center gap-2">
                        <i class="mdi text-lg" :class="isLoading ? 'mdi-loading mdi-spin' : 'mdi-check-circle-outline'"></i>
                        <span x-text="isLoading ? 'Processando...' : 'Concluir Venda'"></span>
                    </span>
                    <span class="text-xs font-black bg-emerald-700/60 px-2.5 py-0.5 rounded-lg" x-text="'MZN ' + formatMoney(cartTotal)"></span>
                </button>

                <!-- Auxiliary Action Buttons Row -->
                <div class="grid grid-cols-3 gap-2 pt-0.5">
                    <button type="button" @click="holdOrder()" :disabled="cart.length === 0 || isLoading"
                        class="py-2 px-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold shadow-sm shadow-amber-500/25 transition-all flex items-center justify-center gap-1.5 disabled:bg-amber-200 disabled:text-amber-50 dark:disabled:bg-amber-900/40 dark:disabled:text-amber-200/50 disabled:shadow-none disabled:cursor-not-allowed cursor-pointer"
                        title="Guardar em mesa ou cliente temporário">
                        <i class="mdi mdi-pause-circle-outline text-sm"></i>
                        <span>Guardar</span>
                    </button>

                    <button type="button" @click="fetchHeldOrders()"
                        class="py-2 px-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-sm shadow-blue-600/25 transition-all flex items-center justify-center gap-1.5 cursor-pointer"
                        title="Recuperar pedidos pendentes">
                        <i class="mdi mdi-clipboard-text-clock-outline text-sm"></i>
                        <span>Recuperar</span>
                    </button>

                    <button type="button" @click="registerAsDebt()" :disabled="cart.length === 0 || isLoading"
                        class="py-2 px-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold shadow-sm shadow-rose-600/25 transition-all flex items-center justify-center gap-1.5 disabled:bg-rose-200 disabled:text-rose-50 dark:disabled:bg-rose-900/40 dark:disabled:text-rose-200/50 disabled:shadow-none disabled:cursor-not-allowed cursor-pointer"
                        title="Registrar como dívida / conta corrente">
                        <i class="mdi mdi-account-cash-outline text-sm"></i>
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
                                <div class="p-3 bg-gray-50 dark:bg-gray-900/60 hover:bg-blue-50/60 dark:hover:bg-blue-950/40 rounded-2xl border border-gray-100 dark:border-gray-700/60 transition-all flex items-center justify-between group">
                                    <div class="flex-1 cursor-pointer" @click="retrieveOrder(order.id)">
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
                                    <div class="flex items-center gap-3">
                                        <div class="text-right cursor-pointer" @click="retrieveOrder(order.id)">
                                            <p class="text-xs font-black text-orange-600 dark:text-orange-400">
                                                MZN <span x-text="formatMoney(order.total_amount)"></span>
                                            </p>
                                            <span class="text-[10px] bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-300 border border-amber-200/50 px-2 py-0.5 rounded-full font-bold">
                                                {{ __('messages.pending') }}
                                            </span>
                                        </div>
                                        <button type="button" @click.stop="discardHeldOrder(order.id)"
                                            class="p-2 text-gray-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/50 rounded-xl transition-all cursor-pointer"
                                            title="Descartar pedido guardado">
                                            <i class="mdi mdi-trash-can-outline text-base"></i>
                                        </button>
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

        <!-- Open Shift Modal -->
        <div x-show="showOpenShiftModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen p-4 bg-black/60 backdrop-blur-xs">
                <div class="bg-white dark:bg-gray-800 rounded-3xl max-w-md w-full overflow-hidden shadow-2xl border border-gray-100 dark:border-gray-700 p-6 space-y-4">
                    <div class="flex justify-between items-center">
                        <h3 class="text-sm font-black text-gray-900 dark:text-white flex items-center gap-2">
                            <i class="mdi mdi-lock-open-variant text-emerald-500 text-lg"></i>
                            <span>Abertura de Turno de Caixa</span>
                        </h3>
                        <button type="button" @click="showOpenShiftModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 cursor-pointer">
                            <i class="mdi mdi-close text-lg"></i>
                        </button>
                    </div>

                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Insira o valor do fundo de maneio inicial em dinheiro presente na gaveta do caixa.
                    </p>

                    <div class="space-y-3">
                        <div>
                            <label class="block text-[11px] font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">
                                Fundo de Maneio Inicial (MZN)
                            </label>
                            <input type="number" step="0.01" min="0" x-model="openingBalance"
                                class="w-full text-base font-bold rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white px-3.5 py-2.5 focus:ring-2 focus:ring-emerald-500">
                        </div>

                        <!-- Quick presets -->
                        <div class="flex items-center gap-1.5 flex-wrap">
                            <template x-for="preset in [0, 100, 200, 500, 1000, 2000]" :key="preset">
                                <button type="button" @click="openingBalance = preset"
                                    class="px-2.5 py-1 rounded-lg text-xs font-bold transition-all cursor-pointer"
                                    :class="openingBalance == preset ? 'bg-emerald-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-200'">
                                    <span x-text="'MZN ' + preset"></span>
                                </button>
                            </template>
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">
                                Observações (Opcional)
                            </label>
                            <input type="text" x-model="shiftNotes" placeholder="Ex: Turno da manhã..."
                                class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white px-3 py-2">
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" @click="showOpenShiftModal = false"
                            class="px-4 py-2 rounded-xl border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 text-xs font-bold hover:bg-gray-100 cursor-pointer">
                            Cancelar
                        </button>
                        <button type="button" @click="openShift()" :disabled="isLoading"
                            class="px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition-all shadow-sm cursor-pointer disabled:opacity-50">
                            <span x-text="isLoading ? 'Abrindo...' : 'Confirmar Abertura'"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Close Shift Modal (Fecho Cego) -->
        <div x-show="showCloseShiftModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen p-4 bg-black/60 backdrop-blur-xs">
                <div class="bg-white dark:bg-gray-800 rounded-3xl max-w-md w-full overflow-hidden shadow-2xl border border-gray-100 dark:border-gray-700 p-6 space-y-4">
                    <div class="flex justify-between items-center">
                        <h3 class="text-sm font-black text-gray-900 dark:text-white flex items-center gap-2">
                            <i class="mdi mdi-lock text-rose-500 text-lg"></i>
                            <span>Fecho Cego de Caixa (Turno #<span x-text="activeShift ? activeShift.id : ''"></span>)</span>
                        </h3>
                        <button type="button" @click="showCloseShiftModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 cursor-pointer">
                            <i class="mdi mdi-close text-lg"></i>
                        </button>
                    </div>

                    <div class="p-3 bg-amber-50 dark:bg-amber-950/40 rounded-2xl border border-amber-200/50 text-[11px] text-amber-800 dark:text-amber-200">
                        <p class="font-bold flex items-center gap-1.5">
                            <i class="mdi mdi-information-outline text-amber-500"></i>
                            <span>Procedimento de Fecho Cego</span>
                        </p>
                        <p class="mt-1">
                            Conte fisicamente todo o dinheiro em notas e moedas presente na gaveta e digite abaixo. O sistema confrontará a sua contagem com as vendas registradas e emitirá o talão de Fecho Z com a quebra/sobra.
                        </p>
                    </div>

                    <div class="space-y-3">
                        <div>
                            <label class="block text-[11px] font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">
                                Valor Físico Contado na Gaveta (MZN) *
                            </label>
                            <input type="number" step="0.01" min="0" x-model="closingBalanceActual" placeholder="0.00"
                                class="w-full text-lg font-black rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white px-3.5 py-2.5 focus:ring-2 focus:ring-rose-500">
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">
                                Observações de Fecho (Opcional)
                            </label>
                            <input type="text" x-model="shiftNotes" placeholder="Ex: Deixado fundo para próximo turno..."
                                class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white px-3 py-2">
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" @click="showCloseShiftModal = false"
                            class="px-4 py-2 rounded-xl border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 text-xs font-bold hover:bg-gray-100 cursor-pointer">
                            Cancelar
                        </button>
                        <button type="button" @click="closeShift()" :disabled="isLoading"
                            class="px-5 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold transition-all shadow-sm cursor-pointer disabled:opacity-50">
                            <span x-text="isLoading ? 'Fechando...' : 'Concluir e Emitir Fecho Z'"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Switch Operator Modal -->
        <div x-show="showSwitchUserModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen p-4 bg-black/60 backdrop-blur-xs">
                <div class="bg-white dark:bg-gray-800 rounded-3xl max-w-sm w-full overflow-hidden shadow-2xl border border-gray-100 dark:border-gray-700 p-6 space-y-4">
                    <div class="flex justify-between items-center">
                        <h3 class="text-sm font-black text-gray-900 dark:text-white flex items-center gap-2">
                            <i class="mdi mdi-account-switch text-orange-500 text-lg"></i>
                            <span>Trocar Operador / Garçom</span>
                        </h3>
                        <button type="button" @click="showSwitchUserModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 cursor-pointer">
                            <i class="mdi mdi-close text-lg"></i>
                        </button>
                    </div>

                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Selecione o membro da equipe que está assumindo o atendimento neste terminal:
                    </p>

                    <div class="space-y-2 max-h-60 overflow-y-auto custom-scrollbar">
                        <template x-for="op in operators" :key="op.id">
                            <div @click="selectedOperatorId = op.id"
                                class="p-3 rounded-2xl border transition-all cursor-pointer flex items-center justify-between"
                                :class="selectedOperatorId == op.id ? 'border-orange-500 bg-orange-50/50 dark:bg-orange-950/40 text-orange-700 dark:text-orange-300' : 'border-gray-100 dark:border-gray-700/60 bg-gray-50 dark:bg-gray-900/40 hover:bg-gray-100'">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-xl bg-white dark:bg-gray-700 flex items-center justify-center font-bold text-xs shadow-2xs">
                                        <span x-text="op.name.charAt(0)"></span>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-gray-900 dark:text-white" x-text="op.name"></p>
                                        <p class="text-[10px] text-gray-400 capitalize" x-text="op.role"></p>
                                    </div>
                                </div>
                                <i class="mdi text-lg" :class="selectedOperatorId == op.id ? 'mdi-check-circle text-orange-500' : 'mdi-circle-outline text-gray-300'"></i>
                            </div>
                        </template>
                    </div>

                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" @click="showSwitchUserModal = false"
                            class="px-4 py-2 rounded-xl border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 text-xs font-bold hover:bg-gray-100 cursor-pointer">
                            Cancelar
                        </button>
                        <button type="button" @click="switchOperator()" :disabled="isLoading"
                            class="px-5 py-2 rounded-xl bg-orange-500 hover:bg-orange-600 text-white text-xs font-bold transition-all shadow-sm cursor-pointer disabled:opacity-50">
                            <span x-text="isLoading ? 'Trocando...' : 'Assumir Caixa'"></span>
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
                    operators: config.operators || [],
                    currentUser: config.currentUser || {},
                    activeShift: config.currentShift || null,
                    csrfToken: config.csrfToken,

                    showOpenShiftModal: false,
                    showCloseShiftModal: false,
                    showSwitchUserModal: false,
                    openingBalance: 0,
                    closingBalanceActual: '',
                    shiftNotes: '',
                    selectedOperatorId: config.currentUser ? config.currentUser.id : '',

                    searchQuery: '',
                    customerName: '',
                    customerNuit: '',
                    invoiceType: 'receipt',
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
                        } else if (typeof amount === 'number') {
                            this.payments.cash = amount;
                        } else {
                            const current = parseFloat(this.payments.cash) || 0;
                            this.payments.cash = current + parseFloat(amount);
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

                        if (!this.activeShift) {
                            const confirmNoShift = await Swal.fire({
                                title: 'Caixa Sem Turno Aberto',
                                text: 'Não há um turno de caixa aberto registrado no momento. Deseja abrir o turno agora?',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Abrir Turno',
                                cancelButtonText: 'Continuar sem Turno',
                                confirmButtonColor: '#10B981',
                                cancelButtonColor: '#6B7280',
                                background: document.documentElement.classList.contains('dark') ? '#1F2937' : '#FFFFFF',
                                color: document.documentElement.classList.contains('dark') ? '#FFFFFF' : '#1F2937',
                            });

                            if (confirmNoShift.isConfirmed) {
                                this.showOpenShiftModal = true;
                                return;
                            }
                        }

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
                            invoice_type: this.invoiceType || 'receipt',
                            amount_paid: totalPaid,
                            total_amount: this.cartTotal,
                            customer_name: this.customerName || 'Cliente Geral',
                            customer_nuit: this.customerNuit || null,
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
                                    this.customerNuit = '';
                                    this.invoiceType = 'receipt';

                                    if (data.sale_id) {
                                        window.printSaleRecibo(data.sale_id);
                                    }

                                    this.refreshShiftStatus();
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

                                        if (this.lastOrderId) {
                                            payload.order_id = this.lastOrderId;
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
                                                    this.lastOrderId = null;
                                                    this.customerName = '';
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
                                    this.customerName = data.customer_name || '';
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

                    discardHeldOrder(orderId) {
                        Swal.fire({
                            title: 'Descartar Pedido?',
                            text: 'Deseja realmente cancelar este pedido guardado?',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Sim, descartar',
                            cancelButtonText: 'Cancelar',
                            confirmButtonColor: '#EF4444',
                            background: document.documentElement.classList.contains('dark') ? '#1F2937' : '#FFFFFF',
                            color: document.documentElement.classList.contains('dark') ? '#FFFFFF' : '#1F2937',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                this.isLoading = true;
                                fetch(`/pos/discard-order/${orderId}`, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': this.csrfToken,
                                        'Accept': 'application/json'
                                    }
                                })
                                    .then(res => res.json())
                                    .then(data => {
                                        this.isLoading = false;
                                        if (data.success) {
                                            this.heldOrders = this.heldOrders.filter(o => o.id !== orderId);
                                            if (this.lastOrderId === orderId) {
                                                this.lastOrderId = null;
                                            }
                                            showToast(data.message, 'success');
                                        } else {
                                            showToast(data.message || 'Erro ao descartar pedido', 'error');
                                        }
                                    })
                                    .catch(err => {
                                        this.isLoading = false;
                                        console.error(err);
                                        showToast('Erro de conexão ao descartar pedido', 'error');
                                    });
                            }
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
                    },

                    async openShift() {
                        this.isLoading = true;
                        try {
                            const res = await fetch("{{ route('pos.shift.open') }}", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': this.csrfToken,
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    opening_balance: parseFloat(this.openingBalance) || 0,
                                    notes: this.shiftNotes
                                })
                            });
                            const data = await res.json();
                            this.isLoading = false;
                            if (data.success) {
                                showToast(data.message, 'success');
                                this.showOpenShiftModal = false;
                                this.openingBalance = 0;
                                this.shiftNotes = '';
                                await this.refreshShiftStatus();
                            } else {
                                showToast(data.message || 'Erro ao abrir turno', 'error');
                            }
                        } catch (err) {
                            this.isLoading = false;
                            showToast('Erro de conexão ao abrir turno', 'error');
                        }
                    },

                    async closeShift() {
                        if (!this.activeShift) return;
                        if (this.closingBalanceActual === '' || isNaN(this.closingBalanceActual)) {
                            showToast('Informe o valor físico contado em caixa.', 'warning');
                            return;
                        }

                        this.isLoading = true;
                        try {
                            const res = await fetch(`/pos/shift/${this.activeShift.id}/close`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': this.csrfToken,
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    closing_balance_actual: parseFloat(this.closingBalanceActual),
                                    notes: this.shiftNotes
                                })
                            });
                            const data = await res.json();
                            this.isLoading = false;
                            if (data.success) {
                                this.showCloseShiftModal = false;
                                this.closingBalanceActual = '';
                                this.shiftNotes = '';
                                this.activeShift = null;

                                await Swal.fire({
                                    title: 'Turno Fechado!',
                                    html: `
                                        <div class="text-left text-xs space-y-2 p-2">
                                            <p><strong>Esperado em Gaveta:</strong> MZN ${this.formatMoney(data.expected_cash)}</p>
                                            <p><strong>Contagem Informada:</strong> MZN ${this.formatMoney(data.actual_cash)}</p>
                                            <p><strong>Diferença:</strong> <span class="${data.difference < 0 ? 'text-red-500 font-bold' : (data.difference > 0 ? 'text-blue-500 font-bold' : 'text-green-500 font-bold')}">MZN ${this.formatMoney(data.difference)}</span></p>
                                        </div>
                                    `,
                                    icon: 'success',
                                    confirmButtonText: 'Imprimir Fecho Z',
                                    showCancelButton: true,
                                    cancelButtonText: 'Concluir',
                                    confirmButtonColor: '#F97316'
                                }).then((result) => {
                                    if (result.isConfirmed && data.receipt_url) {
                                        window.open(data.receipt_url, '_blank');
                                    }
                                });

                                await this.refreshShiftStatus();
                            } else {
                                showToast(data.message || 'Erro ao fechar turno', 'error');
                            }
                        } catch (err) {
                            this.isLoading = false;
                            showToast('Erro de conexão ao fechar turno', 'error');
                        }
                    },

                    async switchOperator() {
                        if (!this.selectedOperatorId) return;
                        this.isLoading = true;
                        try {
                            const res = await fetch("{{ route('pos.switch-operator') }}", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': this.csrfToken,
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    user_id: this.selectedOperatorId
                                })
                            });
                            const data = await res.json();
                            this.isLoading = false;
                            if (data.success) {
                                showToast(data.message, 'success');
                                this.showSwitchUserModal = false;
                                this.currentUser = {
                                    id: data.user_id,
                                    name: data.user_name,
                                    role: data.user_role
                                };
                                await this.refreshShiftStatus();
                            } else {
                                showToast(data.message || 'Erro ao trocar operador', 'error');
                            }
                        } catch (err) {
                            this.isLoading = false;
                            showToast('Erro de conexão ao trocar operador', 'error');
                        }
                    },

                    async refreshShiftStatus() {
                        try {
                            const res = await fetch("{{ route('pos.shift.current') }}");
                            const data = await res.json();
                            if (data.is_open) {
                                this.activeShift = {
                                    id: data.shift_id,
                                    operator_name: data.operator_name,
                                    opened_at: data.opened_at,
                                    opening_balance: data.opening_balance,
                                    cash_sales: data.cash_sales,
                                    total_sales: data.total_sales,
                                    expected_cash: data.expected_cash
                                };
                            } else {
                                this.activeShift = null;
                            }
                        } catch (e) {
                            console.error('Error refreshing shift status:', e);
                        }
                    }
                }));
            });
        </script>
    @endpush
@endsection
