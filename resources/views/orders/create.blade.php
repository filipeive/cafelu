@extends('layouts.app')

@section('title', 'Novo Pedido')

@section('content')
    <div class="h-[calc(100vh-6rem)] flex flex-col lg:flex-row gap-6" x-data="orderSystem()">
        <!-- Products Area -->
        <div class="w-full lg:w-2/3 flex flex-col h-full">
            <!-- Header -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-4">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white flex items-center gap-2">
                            <i class="mdi mdi-cart-plus text-orange-500"></i> Novo Pedido
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Adicione produtos ao pedido</p>
                    </div>
                    <div
                        class="px-3 py-1 rounded-full bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300 text-sm font-medium flex items-center gap-2">
                        <i class="mdi mdi-table-furniture"></i>
                        Mesa: {{ $table->number ?? '?' }}
                    </div>
                </div>

                <!-- Search & Filters -->
                <div class="flex flex-col md:flex-row gap-4 items-center">
                    <div class="relative flex-grow w-full">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="mdi mdi-magnify text-gray-400 text-lg"></i>
                        </span>
                        <input type="text" x-model="search"
                            class="w-full pl-10 pr-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-colors"
                            placeholder="Buscar produtos...">
                    </div>
                </div>

                <!-- Categories -->
                <div
                    class="flex gap-2 mt-4 overflow-x-auto pb-2 scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-600">
                    <button @click="category = 'all'"
                        :class="{ 'bg-orange-500 text-white shadow-md': category === 'all', 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600': category !== 'all' }"
                        class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 whitespace-nowrap flex items-center gap-2">
                        <i class="mdi mdi-view-grid"></i> Todos
                    </button>
                    @foreach ($categories as $cat)
                        <button @click="category = {{ $cat->id }}"
                            :class="{ 'bg-orange-500 text-white shadow-md': category === {{ $cat->id }}, 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600': category !== {{ $cat->id }} }"
                            class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 whitespace-nowrap flex items-center gap-2">
                            <i class="mdi mdi-tag"></i> {{ $cat->name }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Products Grid -->
            <div class="flex-grow overflow-y-auto pr-2 custom-scrollbar">
                <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4">
                    @foreach ($products as $product)
                        <div x-show="(category === 'all' || category === {{ $product->category_id }}) && ('{{ strtolower($product->name) }}'.includes(search.toLowerCase()))"
                            class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col h-full overflow-hidden group">

                            <div
                                class="h-32 bg-gray-100 dark:bg-gray-700 flex items-center justify-center relative overflow-hidden">
                                @if($product->image_path)
                                    <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <i class="mdi mdi-food text-4xl text-gray-400 dark:text-gray-500"></i>
                                @endif
                                <div class="absolute top-2 right-2">
                                    <span
                                        class="px-2 py-1 rounded-md text-xs font-bold bg-white/90 dark:bg-gray-800/90 text-gray-800 dark:text-white shadow-sm">
                                        MZN {{ number_format($product->price, 2, ',', '.') }}
                                    </span>
                                </div>
                            </div>

                            <div class="p-4 flex-1 flex flex-col">
                                <h4 class="text-sm font-bold text-gray-800 dark:text-white mb-1 line-clamp-2"
                                    title="{{ $product->name }}">
                                    {{ $product->name }}
                                </h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-3 line-clamp-2 flex-1">
                                    {{ $product->description }}
                                </p>

                                <div
                                    class="flex items-center justify-between mt-auto pt-3 border-t border-gray-100 dark:border-gray-700">
                                    <div class="flex items-center gap-2 w-full" x-data="{ qty: 0 }"
                                        x-effect="qty = (cart[{{ $product->id }}] ? cart[{{ $product->id }}].quantity : 0)">
                                        <button @click="removeFromCart({{ $product->id }})"
                                            class="w-8 h-8 rounded-full flex items-center justify-center border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 hover:bg-red-50 hover:text-red-500 hover:border-red-200 dark:hover:bg-red-900/20 dark:hover:text-red-400 transition-colors"
                                            :disabled="qty <= 0" :class="{ 'opacity-50 cursor-not-allowed': qty <= 0 }">
                                            <i class="mdi mdi-minus"></i>
                                        </button>

                                        <span class="flex-1 text-center font-bold text-gray-800 dark:text-white"
                                            x-text="qty">0</span>

                                        <button
                                            @click="addToCart({ id: {{ $product->id }}, name: '{{ addslashes($product->name) }}', price: {{ $product->price }} })"
                                            class="w-8 h-8 rounded-full flex items-center justify-center bg-orange-100 text-orange-600 hover:bg-orange-200 dark:bg-orange-900/30 dark:text-orange-400 dark:hover:bg-orange-900/50 transition-colors">
                                            <i class="mdi mdi-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Order Summary (Cart) -->
        <div
            class="w-full lg:w-1/3 flex flex-col h-full bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                <h4 class="font-bold text-gray-800 dark:text-white flex items-center gap-2">
                    <i class="mdi mdi-clipboard-text text-orange-500"></i> Resumo do Pedido
                </h4>
            </div>

            <!-- Cart Items -->
            <div class="flex-1 overflow-y-auto p-4 space-y-3 custom-scrollbar">
                <template x-if="Object.keys(cart).length === 0">
                    <div
                        class="h-full flex flex-col items-center justify-center text-gray-400 dark:text-gray-500 opacity-60">
                        <i class="mdi mdi-cart-outline text-6xl mb-2"></i>
                        <p class="text-sm">Seu pedido está vazio</p>
                    </div>
                </template>

                <template x-for="item in cart" :key="item.id">
                    <div
                        class="flex justify-between items-start p-3 rounded-lg bg-gray-50 dark:bg-gray-700/30 border border-gray-100 dark:border-gray-700">
                        <div class="flex-1">
                            <h5 class="text-sm font-medium text-gray-800 dark:text-white" x-text="item.name"></h5>
                            <div class="flex items-center gap-2 mt-1 text-xs text-gray-500 dark:text-gray-400">
                                <span x-text="formatMoney(item.price)"></span>
                                <span>x</span>
                                <span class="font-bold text-gray-700 dark:text-gray-300" x-text="item.quantity"></span>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="block text-sm font-bold text-orange-600 dark:text-orange-400"
                                x-text="formatMoney(item.price * item.quantity)"></span>
                            <button @click="deleteFromCart(item.id)" class="text-xs text-red-500 hover:text-red-700 mt-1">
                                <i class="mdi mdi-trash-can-outline"></i> Remover
                            </button>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Footer -->
            <div class="p-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-gray-600 dark:text-gray-400 font-medium">Total:</span>
                    <span class="text-2xl font-bold text-gray-900 dark:text-white" x-text="formatMoney(total)"></span>
                </div>

                <form method="POST" action="{{ route('orders.store') }}" @submit.prevent="submitOrder">
                    @csrf
                    <input type="hidden" name="table_id" value="{{ $table->id ?? '' }}">

                    <!-- Hidden inputs for cart items -->
                    <template x-for="item in cart" :key="item.id">
                        <div>
                            <input type="hidden" :name="'products[' + item.id + '][id]'" :value="item.id">
                            <input type="hidden" :name="'products[' + item.id + '][quantity]'" :value="item.quantity">
                        </div>
                    </template>

                    <button type="submit"
                        class="w-full py-3 px-4 bg-orange-600 hover:bg-orange-700 text-white font-bold rounded-lg shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
                        :disabled="Object.keys(cart).length === 0">
                        <i class="mdi mdi-check-circle"></i> Confirmar Pedido
                    </button>
                </form>

                <a href="{{ route('tables.index') }}"
                    class="block text-center mt-3 text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    Cancelar e Voltar
                </a>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function orderSystem() {
                return {
                    search: '',
                    category: 'all',
                    cart: {},

                    addToCart(product) {
                        if (!this.cart[product.id]) {
                            this.cart[product.id] = { ...product, quantity: 0 };
                        }
                        this.cart[product.id].quantity++;
                    },

                    removeFromCart(productId) {
                        if (this.cart[productId]) {
                            this.cart[productId].quantity--;
                            if (this.cart[productId].quantity <= 0) {
                                delete this.cart[productId];
                            }
                        }
                    },

                    deleteFromCart(productId) {
                        if (this.cart[productId]) {
                            delete this.cart[productId];
                        }
                    },

                    get total() {
                        return Object.values(this.cart).reduce((sum, item) => sum + (item.price * item.quantity), 0);
                    },

                    formatMoney(value) {
                        return 'MZN ' + Number(value).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    },

                    submitOrder(e) {
                        if (Object.keys(this.cart).length === 0) {
                            alert('Adicione produtos ao pedido!');
                            return;
                        }
                        e.target.submit();
                    }
                }
            }
        </script>
    @endpush
@endsection