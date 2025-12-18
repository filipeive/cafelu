@extends('layouts.app')

@section('title', __('messages.products'))

@section('content')
    <div class="w-full" x-data="{ 
                                showModal: false,
                                modalTitle: '{{ __('messages.new_product') }}',
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

                                init() {
                                    const urlParams = new URLSearchParams(window.location.search);
                                    if (urlParams.has('create')) {
                                        this.openCreateModal();
                                        // Limpar a URL sem recarregar a página
                                        window.history.replaceState({}, document.title, window.location.pathname);
                                    }
                                },

                                openCreateModal() {
                                    this.showModal = true;
                                    this.modalTitle = '{{ __('messages.new_product') }}';
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
                                            this.modalTitle = '{{ __('messages.edit_product') }}';
                                            this.showModal = true;
                                            hideLoading();
                                        })
                                        .catch(err => {
                                            hideLoading();
                                            console.error(err);
                                            showToast('{{ __('messages.error_loading_product') }}', 'error');
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
                                        showToast('{{ __('messages.error_processing_request') }}', 'error');
                                    });
                                }
                            }">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
            <div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <i class="mdi mdi-package-variant-closed text-orange-500"></i>
                    {{ __('messages.products_management') }}
                </h3>
                <p class="text-gray-500 dark:text-gray-400 mt-1">{{ __('messages.products_desc') }}</p>
            </div>

            @if (Auth::user()->role == 'admin')
                <button type="button" @click="openCreateModal()"
                    class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-2.5 px-6 rounded-xl shadow-lg shadow-orange-500/20 transition-all flex items-center gap-2">
                    <i class="mdi mdi-plus text-lg"></i>
                    {{ __('messages.new_product') }}
                </button>
            @endif
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 mb-6">
            <form method="GET" action="{{ route('products.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                    <div class="md:col-span-5">
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="mdi mdi-magnify text-gray-400 text-lg"></i>
                            </span>
                            <input type="text" name="search" value="{{ request('search') }}"
                                class="w-full pl-10 pr-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all"
                                placeholder="{{ __('messages.search_products_placeholder') }}">
                        </div>
                    </div>
                    <div class="md:col-span-5">
                        <select name="category"
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">
                            <option value="">{{ __('messages.all_categories') }}</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <button type="submit"
                            class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2.5 px-4 rounded-xl shadow-lg shadow-blue-500/20 transition-all flex items-center justify-center gap-2">
                            <i class="mdi mdi-filter"></i> {{ __('messages.filter') }}
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
                        class="group bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col h-full">
                        <!-- Image -->
                        <div class="relative h-48 overflow-hidden bg-gray-50 dark:bg-gray-900 flex items-center justify-center">
                            @if ($product->image_path)
                                <img src="{{ asset('storage/' . $product->image_path) }}"
                                    class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500"
                                    alt="{{ $product->name }}">
                            @else
                                <i class="mdi mdi-image-off text-4xl text-gray-200 dark:text-gray-700"></i>
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
                                        {{ __('messages.inactive') }}
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
                                    {{ $product->category->name ?? __('messages.no_category') }}
                                </span>
                            </div>

                            <h5 class="text-lg font-bold text-gray-900 dark:text-white mb-2 line-clamp-2"
                                title="{{ $product->name }}">
                                {{ $product->name }}
                            </h5>

                            <div class="flex justify-between items-center mb-4">
                                <span class="text-xl font-bold text-orange-500 dark:text-orange-400">
                                    MZN {{ number_format($product->price, 2, ',', '.') }}
                                </span>
                                <span
                                    class="px-2 py-1 rounded text-xs font-medium bg-gray-50 text-gray-500 dark:bg-gray-700 dark:text-gray-400">
                                    {{ $product->type == 'product' ? __('messages.product') : __('messages.service') }}
                                </span>
                            </div>

                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4 line-clamp-2 flex-1">
                                {{ $product->description ?? __('messages.no_description') }}
                            </p>

                            <!-- Actions -->
                            @if (Auth::user()->role == 'admin')
                                <div class="pt-4 border-t border-gray-50 dark:border-gray-700 grid grid-cols-3 gap-2">
                                    <button type="button" @click="editProduct({{ $product->id }})"
                                        class="flex items-center justify-center px-3 py-2 rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-100 dark:bg-blue-900/20 dark:text-blue-400 dark:hover:bg-blue-900/40 transition-colors"
                                        title="{{ __('messages.edit') }}">
                                        <i class="mdi mdi-pencil"></i>
                                    </button>
                                    <button type="button" onclick="updateStock({{ $product->id }}, {{ $product->stock_quantity }})"
                                        class="flex items-center justify-center px-3 py-2 rounded-xl bg-green-50 text-green-600 hover:bg-green-100 dark:bg-green-900/20 dark:text-green-400 dark:hover:bg-green-900/40 transition-colors"
                                        title="{{ __('messages.stock') }}">
                                        <i class="mdi mdi-package-variant"></i>
                                    </button>
                                    <button type="button" onclick="deleteProduct({{ $product->id }})"
                                        class="flex items-center justify-center px-3 py-2 rounded-xl bg-red-50 text-red-600 hover:bg-red-100 dark:bg-red-900/20 dark:text-red-400 dark:hover:bg-red-900/40 transition-colors"
                                        title="{{ __('messages.delete') }}">
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
                <div class="mt-8">
                    {{ $products->links() }}
                </div>
            @endif
        @else
            <div
                class="text-center py-16 bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm">
                <div class="mb-6 inline-flex items-center justify-center w-24 h-24 rounded-full bg-gray-50 dark:bg-gray-700/50">
                    <i class="mdi mdi-package-variant-remove text-5xl text-gray-300 dark:text-gray-600"></i>
                </div>
                <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ __('messages.no_products_found') }}</h4>
                <p class="text-gray-500 dark:text-gray-400 mb-8">{{ __('messages.no_products_found_desc') }}</p>
                @if (Auth::user()->role == 'admin')
                    <button type="button" @click="openCreateModal()"
                        class="inline-flex items-center px-8 py-3 bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-xl shadow-lg shadow-orange-500/20 transition-all">
                        <i class="mdi mdi-plus mr-2"></i>
                        {{ __('messages.add_first_product') }}
                    </button>
                @endif
            </div>
        @endif

        <!-- Product Modal -->
        <div x-show="showModal" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

            <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" @click="showModal = false"></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div
                    class="relative transform overflow-hidden rounded-3xl bg-white dark:bg-gray-800 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-3xl border border-gray-100 dark:border-gray-700">
                    <form @submit.prevent="submitProductForm" enctype="multipart/form-data">
                        <div
                            class="px-8 py-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 flex justify-between items-center">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                <i class="mdi mdi-package-variant text-orange-500"></i>
                                <span x-text="modalTitle"></span>
                            </h3>
                            <button type="button" @click="showModal = false"
                                class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 transition-colors">
                                <i class="mdi mdi-close text-2xl"></i>
                            </button>
                        </div>

                        <div class="px-8 py-8 max-h-[calc(100vh-200px)] overflow-y-auto">
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                                <!-- Name -->
                                <div class="md:col-span-8">
                                    <label
                                        class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">{{ __('messages.product_name') }}
                                        *</label>
                                    <input type="text" name="name" x-model="product.name" required
                                        class="w-full px-4 py-2 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">
                                </div>

                                <!-- Type -->
                                <div class="md:col-span-4">
                                    <label
                                        class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">{{ __('messages.type') }}
                                        *</label>
                                    <select name="type" x-model="product.type" required
                                        class="w-full px-4 py-2 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">
                                        <option value="product">{{ __('messages.product') }}</option>
                                        <option value="service">{{ __('messages.service') }}</option>
                                    </select>
                                </div>

                                <!-- Description -->
                                <div class="md:col-span-12">
                                    <label
                                        class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">{{ __('messages.description') }}</label>
                                    <textarea name="description" x-model="product.description" rows="3"
                                        class="w-full px-4 py-2 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all"></textarea>
                                </div>

                                <!-- Prices -->
                                <div class="md:col-span-6">
                                    <label
                                        class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">{{ __('messages.purchase_price_mzn') }}
                                        *</label>
                                    <input type="number" name="purchase_price" x-model="product.purchase_price" step="0.01"
                                        min="0" value="0.00" required
                                        class="w-full px-4 py-2 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">
                                </div>

                                <div class="md:col-span-6">
                                    <label
                                        class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">{{ __('messages.selling_price_mzn') }}
                                        *</label>
                                    <input type="number" name="selling_price" x-model="product.selling_price" step="0.01"
                                        min="0" required
                                        class="w-full px-4 py-2 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">
                                </div>

                                <!-- Stock & Unit -->
                                <div class="md:col-span-4">
                                    <label
                                        class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">{{ __('messages.stock') }}
                                        *</label>
                                    <input type="number" name="stock_quantity" x-model="product.stock_quantity" min="0"
                                        required
                                        class="w-full px-4 py-2 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">
                                </div>

                                <div class="md:col-span-4">
                                    <label
                                        class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">{{ __('messages.unit') }}</label>
                                    <input type="text" name="unit" x-model="product.unit"
                                        class="w-full px-4 py-2 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all"
                                        placeholder="ex: un, kg, lt">
                                </div>

                                <div class="md:col-span-4">
                                    <label
                                        class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">{{ __('messages.min_stock') }}</label>
                                    <input type="number" name="min_stock_level" x-model="product.min_stock_level" min="0"
                                        class="w-full px-4 py-2 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">
                                </div>

                                <!-- Category & Status -->
                                <div class="md:col-span-8">
                                    <label
                                        class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">{{ __('messages.category') }}
                                        *</label>
                                    <select name="category_id" x-model="product.category_id" required
                                        class="w-full px-4 py-2 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">
                                        <option value="">{{ __('messages.select_category') }}</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="md:col-span-4">
                                    <label
                                        class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">{{ __('messages.status') }}</label>
                                    <div class="flex items-center mt-2">
                                        <label class="inline-flex items-center cursor-pointer">
                                            <input type="checkbox" x-model="product.is_active" class="sr-only peer">
                                            <div
                                                class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-300 dark:peer-focus:ring-orange-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-orange-500">
                                            </div>
                                            <span
                                                class="ms-3 text-sm font-bold text-gray-900 dark:text-gray-300">{{ __('messages.active') }}</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Image -->
                                <div class="md:col-span-12">
                                    <label
                                        class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">{{ __('messages.image') }}</label>
                                    <input type="file" name="image" id="image" accept="image/*"
                                        class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100 dark:file:bg-orange-900/30 dark:file:text-orange-300">
                                </div>
                            </div>
                        </div>

                        <div
                            class="px-8 py-6 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 flex justify-end gap-3">
                            <button type="button" @click="showModal = false"
                                class="px-6 py-2 text-sm font-bold text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors">
                                {{ __('messages.cancel') }}
                            </button>
                            <button type="submit"
                                class="px-8 py-2 bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-xl shadow-lg shadow-orange-500/20 transition-all">
                                <i class="mdi mdi-content-save mr-1"></i>
                                {{ __('messages.save') }}
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
                    title: '{{ __('messages.update_stock') }}',
                    html: `
                                                                    <div class="mb-4">
                                                                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 text-left">{{ __('messages.new_stock') }}</label>
                                                                        <input type="number" id="stockQuantity" class="w-full px-4 py-2 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all" value="${currentStock}" min="0">
                                                                    </div>
                                                                `,
                    showCancelButton: true,
                    confirmButtonText: '{{ __('messages.update') }}',
                    cancelButtonText: '{{ __('messages.cancel') }}',
                    confirmButtonColor: '#F97316',
                    cancelButtonColor: '#6B7280',
                    background: document.documentElement.classList.contains('dark') ? '#1F2937' : '#FFFFFF',
                    color: document.documentElement.classList.contains('dark') ? '#FFFFFF' : '#1F2937',
                    preConfirm: () => {
                        const val = document.getElementById('stockQuantity').value;
                        if (!val || val < 0) Swal.showValidationMessage('{{ __('messages.invalid_quantity') }}');
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
                                    showToast('{{ __('messages.stock_updated') }}', 'success');
                                    setTimeout(() => location.reload(), 1000);
                                } else {
                                    showToast('{{ __('messages.error_updating_stock') }}', 'error');
                                }
                            })
                            .catch(err => {
                                hideLoading();
                                console.error(err);
                                showToast('{{ __('messages.error_processing_request') }}', 'error');
                            });
                    }
                });
            }

            function deleteProduct(productId) {
                Swal.fire({
                    title: '{{ __('messages.are_you_sure') }}',
                    text: "{{ __('messages.action_irreversible') }}",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: '{{ __('messages.yes_delete') }}',
                    cancelButtonText: '{{ __('messages.cancel') }}',
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
                                    showToast('{{ __('messages.product_deleted') }}', 'success');
                                    setTimeout(() => location.reload(), 1000);
                                } else {
                                    showToast('{{ __('messages.error_deleting_product') }}', 'error');
                                }
                            })
                            .catch(err => {
                                hideLoading();
                                console.error(err);
                                showToast('{{ __('messages.error_processing_request') }}', 'error');
                            });
                    }
                });
            }
        </script>
    @endpush
@endsection