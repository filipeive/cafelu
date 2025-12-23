@extends('layouts.app')

@section('title', 'Novo Pedido')

@section('content')
    <div class="w-full pb-12" x-data="orderSystem()">
        <!-- Header Section -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">Novo Pedido 🍽️</h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Escolha seus itens favoritos e faça seu pedido agora.</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="relative">
                    <input type="text" x-model="search" placeholder="Buscar produto..."
                        class="w-full md:w-64 pl-10 pr-4 py-2.5 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl text-sm focus:ring-2 focus:ring-orange-500 transition-all">
                    <i class="mdi mdi-magnify absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                </div>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Products Section -->
            <div class="flex-1">
                <!-- Categories -->
                <div class="mb-8 flex flex-wrap gap-2">
                    <button @click="activeCategory = 'all'"
                        :class="activeCategory === 'all' ? 'bg-orange-500 text-white shadow-md' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 border border-gray-100 dark:border-gray-700 hover:bg-gray-50'"
                        class="px-4 py-2 rounded-lg text-sm font-bold transition-all">
                        Todos
                    </button>
                    @foreach($categories as $category)
                        <button @click="activeCategory = '{{ $category->id }}'"
                            :class="activeCategory === '{{ $category->id }}' ? 'bg-orange-500 text-white shadow-md' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 border border-gray-100 dark:border-gray-700 hover:bg-gray-50'"
                            class="px-4 py-2 rounded-lg text-sm font-bold transition-all">
                            {{ $category->name }}
                        </button>
                    @endforeach
                </div>

                <!-- Products Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                    <template x-for="product in filteredProducts" :key="product.id">
                        <div
                            class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-md transition-all group">
                            <div class="relative h-48 overflow-hidden bg-gray-100 dark:bg-gray-900/50">
                                <template x-if="product.image">
                                    <img :src="'/storage/' + product.image" :alt="product.name"
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                </template>
                                <template x-if="!product.image">
                                    <div
                                        class="w-full h-full flex items-center justify-center text-gray-300 dark:text-gray-600">
                                        <i class="mdi mdi-food text-5xl"></i>
                                    </div>
                                </template>
                                <div class="absolute top-3 right-3">
                                    <span
                                        class="px-2 py-1 bg-white/90 dark:bg-gray-800/90 backdrop-blur rounded-lg text-[10px] font-black text-orange-500 shadow-sm"
                                        x-text="product.price.toFixed(2) + ' MT'"></span>
                                </div>
                            </div>
                            <div class="p-5">
                                <h3 class="font-bold text-gray-900 dark:text-white mb-1 truncate" x-text="product.name">
                                </h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-2 mb-4 h-8"
                                    x-text="product.description || 'Sem descrição'"></p>
                                <button @click="addToCart(product)"
                                    class="w-full py-2.5 bg-orange-50 dark:bg-orange-900/20 text-orange-500 rounded-xl text-xs font-bold hover:bg-orange-500 hover:text-white transition-all flex items-center justify-center gap-2">
                                    <i class="mdi mdi-plus-circle-outline"></i> Adicionar
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Cart Sidebar -->
            <div class="w-full lg:w-96">
                <div
                    class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 sticky top-24 overflow-hidden flex flex-col max-h-[calc(100vh-120px)]">
                    <div class="p-6 border-b border-gray-50 dark:border-gray-700/50 bg-gray-50/50 dark:bg-gray-900/20">
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                            <i class="mdi mdi-cart-outline text-orange-500"></i> Meu Pedido
                        </h2>
                    </div>

                    <div class="flex-1 overflow-y-auto p-6 space-y-4">
                        <template x-if="cart.length === 0">
                            <div class="text-center py-12">
                                <div
                                    class="w-16 h-16 bg-gray-50 dark:bg-gray-900/50 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="mdi mdi-cart-off text-3xl text-gray-300 dark:text-gray-600"></i>
                                </div>
                                <p class="text-sm text-gray-400">Seu carrinho está vazio</p>
                            </div>
                        </template>

                        <template x-for="(item, index) in cart" :key="item.id">
                            <div
                                class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-900/30 rounded-2xl border border-gray-100 dark:border-gray-700/50">
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-xs font-bold text-gray-900 dark:text-white truncate" x-text="item.name">
                                    </h4>
                                    <p class="text-[10px] text-orange-500 font-bold"
                                        x-text="(item.price * item.quantity).toFixed(2) + ' MT'"></p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button @click="updateQuantity(index, -1)"
                                        class="w-6 h-6 rounded-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center text-gray-500 hover:bg-gray-50 transition-colors">
                                        <i class="mdi mdi-minus text-xs"></i>
                                    </button>
                                    <span class="text-xs font-bold text-gray-900 dark:text-white w-4 text-center"
                                        x-text="item.quantity"></span>
                                    <button @click="updateQuantity(index, 1)"
                                        class="w-6 h-6 rounded-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center text-gray-500 hover:bg-gray-50 transition-colors">
                                        <i class="mdi mdi-plus text-xs"></i>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div class="p-6 bg-gray-50/50 dark:bg-gray-900/20 border-t border-gray-50 dark:border-gray-700/50">
                        <div class="flex justify-between items-center mb-6">
                            <span class="text-sm text-gray-500 dark:text-gray-400 font-medium">Total</span>
                            <span class="text-xl font-black text-gray-900 dark:text-white"
                                x-text="cartTotal.toFixed(2) + ' MT'"></span>
                        </div>

                        <button @click="placeOrder()" :disabled="cart.length === 0 || loading"
                            class="w-full py-4 bg-orange-500 text-white font-bold rounded-2xl shadow-lg shadow-orange-500/30 hover:bg-orange-600 transition-all transform hover:-translate-y-1 disabled:opacity-50 disabled:transform-none flex items-center justify-center gap-2">
                            <template x-if="!loading">
                                <i class="mdi mdi-check-circle"></i>
                            </template>
                            <template x-if="loading">
                                <i class="mdi mdi-loading mdi-spin"></i>
                            </template>
                            <span x-text="loading ? 'Processando...' : 'Finalizar Pedido'"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function orderSystem() {
                return {
                    search: '',
                    activeCategory: 'all',
                    cart: [],
                    loading: false,
                    products: @json($categories->flatMap->products),

                    get filteredProducts() {
                        return this.products.filter(p => {
                            const matchesSearch = p.name.toLowerCase().includes(this.search.toLowerCase());
                            const matchesCategory = this.activeCategory === 'all' || p.category_id == this.activeCategory;
                            return matchesSearch && matchesCategory;
                        });
                    },

                    get cartTotal() {
                        return this.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
                    },

                    addToCart(product) {
                        const existing = this.cart.find(i => i.id === product.id);
                        if (existing) {
                            existing.quantity++;
                        } else {
                            this.cart.push({ ...product, quantity: 1 });
                        }
                        this.showToast('Item adicionado!');
                    },

                    updateQuantity(index, delta) {
                        this.cart[index].quantity += delta;
                        if (this.cart[index].quantity <= 0) {
                            this.cart.splice(index, 1);
                        }
                    },

                    placeOrder() {
                        if (this.cart.length === 0) return;
                        this.loading = true;

                        fetch('{{ route('customer.order.store') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ items: this.cart })
                        })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        title: 'Sucesso!',
                                        text: 'Seu pedido foi realizado com sucesso.',
                                        icon: 'success',
                                        confirmButtonColor: '#F97316',
                                        borderRadius: '1.5rem'
                                    }).then(() => {
                                        window.location.href = '{{ route('customer.orders') }}';
                                    });
                                } else {
                                    Swal.fire('Erro', data.message, 'error');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire('Erro', 'Ocorreu um erro ao processar seu pedido.', 'error');
                            })
                            .finally(() => {
                                this.loading = false;
                            });
                    },

                    showToast(message) {
                        // Simple toast implementation or use a library
                        console.log(message);
                    }
                }
            }
        </script>
    @endpush
@endsection