@extends('layouts.app')

@section('title', 'Produtos')

@section('content')
    <div class="w-full" x-data="{ 
                        showModal: false,
                        modalTitle: 'Novo Produto',
                        isEdit: false,
                        product: {
                            id: '',
                            name: '',
                            type: 'product',
                            description: '',
                            purchase_price: '',
                            selling_price: '',
                            stock_quantity: '',
                            unit: '',
                            min_stock_level: 5,
                            category_id: '',
                            is_active: true,
                            image_path: null
                        },

                        openCreateModal() {
                            this.showModal = true;
                            this.modalTitle = 'Novo Produto';
                            this.isEdit = false;
                            this.product = {
                                id: '',
                                name: '',
                                type: 'product',
                                description: '',
                                purchase_price: '',
                                selling_price: '',
                                stock_quantity: '',
                                unit: '',
                                min_stock_level: 5,
                                category_id: '',
                                is_active: true,
                                image_path: null
                            };
                            // Reset file input
                            const fileInput = document.getElementById('image');
                            if(fileInput) fileInput.value = '';
                        },

                        editProduct(productId) {
                            showLoading();
                            fetch(`/products/${productId}`)
                                .then(res => res.json())
                                .then(data => {
                                    this.product = {
                                        id: data.id,
                                        name: data.name,
                                        type: data.type || 'product',
                                        description: data.description || '',
                                        purchase_price: data.purchase_price || '0.00',
                                        selling_price: data.selling_price,
                                        stock_quantity: data.stock_quantity,
                                        unit: data.unit || '',
                                        min_stock_level: data.min_stock_level || 5,
                                        category_id: data.category_id,
                                        is_active: data.is_active == 1,
                                        image_path: data.image_path
                                    };
                                    this.isEdit = true;
                                    this.modalTitle = 'Editar Produto';
                                    this.showModal = true;
                                    hideLoading();
                                })
                                .catch(err => {
                                    hideLoading();
                                    console.error(err);
                                    showToast('Erro ao carregar produto', 'error');
                                });
                        },

                        submitProductForm(e) {
                            const formData = new FormData(e.target);
                            if (!this.product.is_active) formData.set('is_active', '0');
                            else formData.set('is_active', '1');

                            if (this.isEdit) formData.append('_method', 'PUT');

                            const url = this.isEdit ? `/products/${this.product.id}` : '{{ route('products.store') }}';

                            showLoading();
                            fetch(url, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name=\'csrf-token\']').content,
                                    'Accept': 'application/json'
                                },
                                body: formData
                            })
                            .then(res => res.json())
                            .then(data => {
                                hideLoading();
                                if (data.success) {
                                    showToast(data.message, 'success');
                                    this.showModal = false; // Close modal on success
                                    setTimeout(() => location.reload(), 1000);
                                } else {
                                    let errorMessage = data.message;
                                    if (data.errors) {
                                        errorMessage += ':\n' + Object.values(data.errors).flat().join('\n');
                                    }
                                    showToast(errorMessage, 'error');
                                }
                            })
                            .catch(err => {
                                hideLoading();
                                console.error(err);
                                showToast('Erro ao processar requisição', 'error');
                            });
                        }
                    }">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
            <div>
                <h3 class="text-2xl font-bold text-gray-800 dark:text-white flex items-center gap-2">
                    <i class="mdi mdi-package-variant-closed text-orange-500"></i>
                    Gerenciamento de Produtos
                </h3>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Gerencie seu catálogo de produtos e serviços.</p>
            </div>

            @if (Auth::user()->role == 'admin')
                <button type="button" @click="openCreateModal()"
                    class="bg-orange-600 hover:bg-orange-700 text-white font-semibold py-2.5 px-6 rounded-lg shadow-md hover:shadow-lg transition-all flex items-center gap-2">
                    <i class="mdi mdi-plus text-lg"></i>
                    Novo Produto
                </button>
            @endif
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-6">
            <form method="GET" action="{{ route('products.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                    <div class="md:col-span-5">
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="mdi mdi-magnify text-gray-400 text-lg"></i>
                            </span>
                            <input type="text" name="search" value="{{ request('search') }}"
                                class="w-full pl-10 pr-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors"
                                placeholder="Buscar produtos...">
                        </div>
                    </div>
                    <div class="md:col-span-5">
                        <select name="category"
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors">
                            <option value="">Todas as Categorias</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-4 rounded-lg shadow-sm transition-colors flex items-center justify-center gap-2">
                            <i class="mdi mdi-filter"></i> Filtrar
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Products Grid -->
        @if($products->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($products as $product)
                    <div
                        class="group bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden flex flex-col h-full">
                        <!-- Image -->
                        <div class="relative h-48 overflow-hidden bg-gray-100 dark:bg-gray-900 flex items-center justify-center">
                            @if ($product->image_path)
                                <img src="{{ asset('storage/' . $product->image_path) }}"
                                    class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500"
                                    alt="{{ $product->name }}">
                            @else
                                <i class="mdi mdi-image-off text-4xl text-gray-300 dark:text-gray-600"></i>
                            @endif

                            <!-- Stock Badge -->
                            <div class="absolute top-3 right-3">
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold shadow-sm backdrop-blur-sm
                                                                                    {{ $product->stock_quantity > 10 ? 'bg-green-100/90 text-green-700 dark:bg-green-900/90 dark:text-green-300' :
                        ($product->stock_quantity > 5 ? 'bg-yellow-100/90 text-yellow-700 dark:bg-yellow-900/90 dark:text-yellow-300' :
                            'bg-red-100/90 text-red-700 dark:bg-red-900/90 dark:text-red-300') }}">
                                    <i class="mdi mdi-package mr-1"></i>{{ $product->stock_quantity }}
                                </span>
                            </div>

                            <!-- Status Badge -->
                            @if(!$product->is_active)
                                <div class="absolute top-3 left-3">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-red-500 text-white shadow-sm">
                                        Inativo
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Info -->
                        <div class="p-5 flex-1 flex flex-col">
                            <div class="mb-2">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300">
                                    <i class="mdi mdi-tag-outline mr-1"></i>
                                    {{ $product->category->name ?? 'Sem categoria' }}
                                </span>
                            </div>

                            <h5 class="text-lg font-bold text-gray-800 dark:text-white mb-2 line-clamp-2"
                                title="{{ $product->name }}">
                                {{ $product->name }}
                            </h5>

                            <div class="flex justify-between items-center mb-4">
                                <span class="text-xl font-bold text-orange-600 dark:text-orange-400">
                                    MZN {{ number_format($product->price, 2, ',', '.') }}
                                </span>
                                <span
                                    class="px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                    {{ $product->type == 'product' ? 'Produto' : 'Serviço' }}
                                </span>
                            </div>

                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4 line-clamp-2 flex-1">
                                {{ $product->description ?? 'Sem descrição' }}
                            </p>

                            <!-- Actions -->
                            @if (Auth::user()->role == 'admin')
                                <div class="pt-4 border-t border-gray-100 dark:border-gray-700 grid grid-cols-3 gap-2">
                                    <button type="button" @click="editProduct({{ $product->id }})"
                                        class="flex items-center justify-center px-3 py-2 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 dark:bg-blue-900/20 dark:text-blue-400 dark:hover:bg-blue-900/40 transition-colors"
                                        title="Editar">
                                        <i class="mdi mdi-pencil"></i>
                                    </button>
                                    <button type="button" onclick="updateStock({{ $product->id }}, {{ $product->stock_quantity }})"
                                        class="flex items-center justify-center px-3 py-2 rounded-lg bg-green-50 text-green-600 hover:bg-green-100 dark:bg-green-900/20 dark:text-green-400 dark:hover:bg-green-900/40 transition-colors"
                                        title="Estoque">
                                        <i class="mdi mdi-package-variant"></i>
                                    </button>
                                    <button type="button" onclick="deleteProduct({{ $product->id }})"
                                        class="flex items-center justify-center px-3 py-2 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 dark:bg-red-900/20 dark:text-red-400 dark:hover:bg-red-900/40 transition-colors"
                                        title="Excluir">
                                        <i class="mdi mdi-delete"></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($products->hasPages())
                <div class="mt-6">
                    {{ $products->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
                <div class="mb-4 inline-flex items-center justify-center w-20 h-20 rounded-full bg-gray-100 dark:bg-gray-700">
                    <i class="mdi mdi-package-variant-remove text-4xl text-gray-400"></i>
                </div>
                <h4 class="text-xl font-medium text-gray-800 dark:text-white mb-2">Nenhum produto encontrado</h4>
                <p class="text-gray-500 dark:text-gray-400 mb-6">Ajuste os filtros ou adicione novos produtos.</p>
                @if (Auth::user()->role == 'admin')
                    <button type="button" @click="openCreateModal()"
                        class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-orange-600 hover:bg-orange-700 shadow-sm transition-colors">
                        <i class="mdi mdi-plus mr-2"></i>
                        Adicionar Primeiro Produto
                    </button>
                @endif
            </div>
        @endif

        <!-- Product Modal -->
        <div x-show="showModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

            <div class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity" @click="showModal = false"></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div
                    class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-gray-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-3xl border border-gray-200 dark:border-gray-700">
                    <form @submit.prevent="submitProductForm" enctype="multipart/form-data">
                        <div
                            class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 flex justify-between items-center">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                <i class="mdi mdi-package-variant text-orange-500"></i>
                                <span x-text="modalTitle"></span>
                            </h3>
                            <button type="button" @click="showModal = false"
                                class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 transition-colors">
                                <i class="mdi mdi-close text-xl"></i>
                            </button>
                        </div>

                        <div class="px-6 py-6 max-h-[calc(100vh-200px)] overflow-y-auto">
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                                <!-- Name -->
                                <div class="md:col-span-8">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nome do
                                        Produto *</label>
                                    <input type="text" name="name" x-model="product.name" required
                                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm">
                                </div>

                                <!-- Type -->
                                <div class="md:col-span-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipo
                                        *</label>
                                    <select name="type" x-model="product.type" required
                                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm">
                                        <option value="product">Produto</option>
                                        <option value="service">Serviço</option>
                                    </select>
                                </div>

                                <!-- Description -->
                                <div class="md:col-span-12">
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Descrição</label>
                                    <textarea name="description" x-model="product.description" rows="3"
                                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm"></textarea>
                                </div>

                                <!-- Prices -->
                                <div class="md:col-span-6">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Preço de
                                        Custo (MZN) *</label>
                                    <input type="number" name="purchase_price" x-model="product.purchase_price" step="0.01"
                                        min="0" value="0.00" required
                                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm">
                                </div>

                                <div class="md:col-span-6">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Preço de
                                        Venda (MZN) *</label>
                                    <input type="number" name="selling_price" x-model="product.selling_price" step="0.01"
                                        min="0" required
                                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm">
                                </div>

                                <!-- Stock & Unit -->
                                <div class="md:col-span-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Estoque
                                        *</label>
                                    <input type="number" name="stock_quantity" x-model="product.stock_quantity" min="0"
                                        required
                                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm">
                                </div>

                                <div class="md:col-span-4">
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Unidade</label>
                                    <input type="text" name="unit" x-model="product.unit"
                                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm"
                                        placeholder="ex: un, kg, lt">
                                </div>

                                <div class="md:col-span-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Estoque
                                        Mínimo</label>
                                    <input type="number" name="min_stock_level" x-model="product.min_stock_level" min="0"
                                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm">
                                </div>

                                <!-- Category & Status -->
                                <div class="md:col-span-8">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Categoria
                                        *</label>
                                    <select name="category_id" x-model="product.category_id" required
                                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm">
                                        <option value="">Selecione uma categoria</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="md:col-span-4">
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                                    <div class="flex items-center mt-2">
                                        <label class="inline-flex items-center cursor-pointer">
                                            <input type="checkbox" x-model="product.is_active" class="sr-only peer">
                                            <div
                                                class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-300 dark:peer-focus:ring-orange-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-orange-500">
                                            </div>
                                            <span
                                                class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">Ativo</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Image -->
                                <div class="md:col-span-12">
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Imagem</label>
                                    <input type="file" name="image" id="image" accept="image/*"
                                        class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100 dark:file:bg-orange-900/30 dark:file:text-orange-300">
                                </div>
                            </div>
                        </div>

                        <div
                            class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 flex justify-end gap-3">
                            <button type="button" @click="showModal = false"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600">
                                Cancelar
                            </button>
                            <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-orange-600 border border-transparent rounded-lg hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 shadow-sm">
                                <i class="mdi mdi-content-save mr-1"></i>
                                Salvar
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
            function updateStock(productId, currentStock) {
                Swal.fire({
                    title: 'Atualizar Estoque',
                    html: `
                                                    <div class="mb-4">
                                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Novo Estoque</label>
                                                        <input type="number" id="stockQuantity" class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-orange-500 focus:border-orange-500" value="${currentStock}" min="0">
                                                    </div>
                                                `,
                    showCancelButton: true,
                    confirmButtonText: 'Atualizar',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#F97316',
                    cancelButtonColor: '#6B7280',
                    background: document.documentElement.classList.contains('dark') ? '#1F2937' : '#FFFFFF',
                    color: document.documentElement.classList.contains('dark') ? '#FFFFFF' : '#1F2937',
                    preConfirm: () => {
                        const val = document.getElementById('stockQuantity').value;
                        if (!val || val < 0) Swal.showValidationMessage('Quantidade inválida');
                        return val;
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        showLoading();
                        fetch(`/products/${productId}/stock`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({ stock_quantity: result.value })
                        })
                            .then(res => res.json())
                            .then(data => {
                                hideLoading();
                                if (data.success) {
                                    showToast('Estoque atualizado!', 'success');
                                    setTimeout(() => location.reload(), 1000);
                                } else {
                                    showToast('Erro ao atualizar estoque', 'error');
                                }
                            })
                            .catch(err => {
                                hideLoading();
                                console.error(err);
                                showToast('Erro na requisição', 'error');
                            });
                    }
                });
            }

            function deleteProduct(productId) {
                Swal.fire({
                    title: 'Tem certeza?',
                    text: "Esta ação não pode ser revertida!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sim, excluir',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#EF4444',
                    cancelButtonColor: '#6B7280',
                    background: document.documentElement.classList.contains('dark') ? '#1F2937' : '#FFFFFF',
                    color: document.documentElement.classList.contains('dark') ? '#FFFFFF' : '#1F2937'
                }).then((result) => {
                    if (result.isConfirmed) {
                        showLoading();
                        fetch(`/products/${productId}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        })
                            .then(res => res.json())
                            .then(data => {
                                hideLoading();
                                if (data.success) {
                                    showToast('Produto excluído!', 'success');
                                    setTimeout(() => location.reload(), 1000);
                                } else {
                                    showToast('Erro ao excluir produto', 'error');
                                }
                            })
                            .catch(err => {
                                hideLoading();
                                console.error(err);
                                showToast('Erro na requisição', 'error');
                            });
                    }
                });
            }
        </script>
    @endpush
@endsection