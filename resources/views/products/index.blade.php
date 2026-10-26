@extends('layouts.app')

@section('title', __('messages.products'))

@section('content')
    @php
        $canManage = Auth::check() && in_array(Auth::user()->role, ['admin', 'superadmin', 'manager']);
    @endphp

    <div class="w-full" x-data="{ 
        viewMode: localStorage.getItem('products_view_mode') || 'grid',
        setViewMode(mode) {
            this.viewMode = mode;
            localStorage.setItem('products_view_mode', mode);
        },
        showModal: false,
        modalTitle: '{{ __('messages.new_product') }}',
        isEdit: false,
        product: {
            id: '',
            name: '',
            type: 'product',
            description: '',
            purchase_price: '0.00',
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
                purchase_price: '0.00',
                selling_price: '',
                stock_quantity: '',
                unit: '',
                min_stock_level: 5,
                category_id: '',
                is_active: true,
                image_path: null
            };
            const fileInput = document.getElementById('image');
            if(fileInput) fileInput.value = '';
        },

        editProduct(productId) {
            showLoading();
            fetch(`{{ url('/products') }}/${productId}`)
                .then(res => res.json())
                .then(data => {
                    this.product = {
                        id: data.id,
                        name: data.name,
                        type: data.type || 'product',
                        description: data.description || '',
                        purchase_price: (data.purchase_price !== null && data.purchase_price !== undefined && data.purchase_price !== '') ? data.purchase_price : '0.00',
                        selling_price: (data.selling_price !== null && data.selling_price !== undefined && data.selling_price !== '') ? data.selling_price : (data.price ?? '0.00'),
                        stock_quantity: data.stock_quantity ?? 0,
                        unit: data.unit || '',
                        min_stock_level: data.min_stock_level ?? 5,
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

            const url = this.isEdit ? `{{ url('/products') }}/${this.product.id}` : '{{ route('products.store') }}';

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
                    this.showModal = false;
                    setTimeout(() => location.reload(), 800);
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
                <p class="text-gray-500 dark:text-gray-400 mt-1">
                    {{ __('messages.products_desc') }} • <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $products->total() }}</span> {{ __('messages.products') }}
                </p>
            </div>

            <div class="flex items-center gap-3 w-full md:w-auto justify-between md:justify-end">
                <!-- View Mode Toggle -->
                <div class="inline-flex p-1 bg-gray-100 dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700">
                    <button type="button" @click="setViewMode('grid')"
                        :class="viewMode === 'grid' ? 'bg-white dark:bg-gray-700 text-orange-500 shadow-sm font-bold' : 'text-gray-500 hover:text-gray-800 dark:text-gray-400 dark:hover:text-white'"
                        class="flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs transition-all">
                        <i class="mdi mdi-view-grid text-base"></i>
                        <span>{{ __('messages.grid_view') }}</span>
                    </button>
                    <button type="button" @click="setViewMode('list')"
                        :class="viewMode === 'list' ? 'bg-white dark:bg-gray-700 text-orange-500 shadow-sm font-bold' : 'text-gray-500 hover:text-gray-800 dark:text-gray-400 dark:hover:text-white'"
                        class="flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs transition-all">
                        <i class="mdi mdi-view-list text-base"></i>
                        <span>{{ __('messages.list_view') }}</span>
                    </button>
                </div>

                @if ($canManage)
                    <button type="button" @click="openCreateModal()"
                        class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-2.5 px-5 rounded-2xl shadow-lg shadow-orange-500/20 transition-all flex items-center gap-2 text-sm">
                        <i class="mdi mdi-plus text-lg"></i>
                        <span>{{ __('messages.new_product') }}</span>
                    </button>
                @endif
            </div>
        </div>

        <!-- Filters & Search -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 mb-6">
            <form method="GET" action="{{ route('products.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
                    <div class="md:col-span-5">
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <i class="mdi mdi-magnify text-gray-400 text-lg"></i>
                            </span>
                            <input type="text" name="search" value="{{ request('search') }}"
                                class="w-full pl-10 pr-4 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all"
                                placeholder="{{ __('messages.search_products_placeholder') }}">
                        </div>
                    </div>
                    <div class="md:col-span-4">
                        <select name="category"
                            class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">
                            <option value="">{{ __('messages.all_categories') }}</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-3 flex items-center gap-2">
                        <button type="submit"
                            class="flex-1 bg-gray-900 dark:bg-gray-700 hover:bg-black dark:hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-xl text-sm transition-all flex items-center justify-center gap-1.5">
                            <i class="mdi mdi-filter-variant"></i>
                            <span>{{ __('messages.filter') }}</span>
                        </button>
                        @if (request()->hasAny(['search', 'category', 'status']))
                            <a href="{{ route('products.index') }}"
                                class="p-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-600 dark:text-gray-300 rounded-xl transition-all"
                                title="Limpar Filtros">
                                <i class="mdi mdi-close"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        @if($products->count() > 0)
            <!-- VIEW 1: GRID MODE -->
            <div x-show="viewMode === 'grid'" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($products as $product)
                    <div class="group bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-xl hover:border-orange-500/30 transition-all duration-300 overflow-hidden flex flex-col h-full">
                        <!-- Image Container -->
                        <div class="relative h-44 overflow-hidden bg-gray-50 dark:bg-gray-900 flex items-center justify-center">
                            @if ($product->image_path)
                                <img src="{{ asset('storage/' . $product->image_path) }}"
                                    class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500"
                                    alt="{{ $product->name }}">
                            @else
                                <div class="w-16 h-16 rounded-2xl bg-orange-50 dark:bg-orange-950/40 text-orange-500 flex items-center justify-center">
                                    <i class="mdi mdi-silverware-fork-knife text-3xl"></i>
                                </div>
                            @endif

                            <!-- Category Pill -->
                            <div class="absolute top-3 left-3">
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-white/90 dark:bg-gray-800/90 text-gray-800 dark:text-gray-200 shadow-xs backdrop-blur-sm border border-gray-200/50 dark:border-gray-700/50">
                                    {{ $product->category->name ?? __('messages.no_category') }}
                                </span>
                            </div>

                            <!-- Stock Pill -->
                            <div class="absolute top-3 right-3">
                                @if($product->stock_quantity > 10)
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-500 text-white shadow-xs">
                                        <i class="mdi mdi-check-circle-outline mr-0.5"></i>{{ $product->stock_quantity }} {{ $product->unit ?: 'un' }}
                                    </span>
                                @elseif($product->stock_quantity > 0)
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-amber-500 text-white shadow-xs">
                                        <i class="mdi mdi-alert-circle-outline mr-0.5"></i>{{ $product->stock_quantity }} {{ $product->unit ?: 'un' }}
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-rose-500 text-white shadow-xs">
                                        {{ __('messages.out_of_stock') }}
                                    </span>
                                @endif
                            </div>

                            <!-- Inactive overlay badge if disabled -->
                            @if(!$product->is_active)
                                <div class="absolute bottom-3 left-3">
                                    <span class="px-2 py-0.5 rounded-md text-[11px] font-bold bg-rose-600 text-white shadow-xs">
                                        {{ __('messages.inactive') }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Card Content -->
                        <div class="p-5 flex-1 flex flex-col justify-between">
                            <div>
                                <h4 class="text-base font-bold text-gray-900 dark:text-white mb-1.5 line-clamp-1" title="{{ $product->name }}">
                                    {{ $product->name }}
                                </h4>

                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-3 line-clamp-2 min-h-[32px]">
                                    {{ $product->description ?: __('messages.no_description') }}
                                </p>
                            </div>

                            <div>
                                <div class="flex items-baseline justify-between pt-3 border-t border-gray-100 dark:border-gray-700/60 mb-4">
                                    <div>
                                        <span class="text-xs text-gray-400 block font-medium">{{ __('messages.price') }}</span>
                                        <span class="text-lg font-black text-orange-500 dark:text-orange-400">
                                            MZN {{ number_format($product->price, 2, ',', '.') }}
                                        </span>
                                    </div>
                                    <span class="px-2 py-0.5 rounded-lg text-[11px] font-semibold bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                                        {{ $product->type === 'service' ? __('messages.service') : __('messages.product') }}
                                    </span>
                                </div>

                                <!-- Action Buttons -->
                                @if ($canManage)
                                    <div class="grid grid-cols-12 gap-2 pt-2">
                                        <button type="button" @click="editProduct({{ $product->id }})"
                                            class="col-span-5 flex items-center justify-center gap-1.5 py-2 px-3 rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400 dark:hover:bg-blue-900/50 transition-colors font-bold text-xs"
                                            title="{{ __('messages.edit') }}">
                                            <i class="mdi mdi-pencil text-sm"></i>
                                            <span>{{ __('messages.edit') }}</span>
                                        </button>
                                        <button type="button" onclick="updateStock({{ $product->id }}, {{ $product->stock_quantity }})"
                                            class="col-span-5 flex items-center justify-center gap-1.5 py-2 px-3 rounded-xl bg-emerald-50 text-emerald-600 hover:bg-emerald-100 dark:bg-emerald-900/30 dark:text-emerald-400 dark:hover:bg-emerald-900/50 transition-colors font-bold text-xs"
                                            title="{{ __('messages.stock') }}">
                                            <i class="mdi mdi-package-variant text-sm"></i>
                                            <span>{{ __('messages.stock') }}</span>
                                        </button>
                                        <button type="button" onclick="deleteProduct({{ $product->id }})"
                                            class="col-span-2 flex items-center justify-center p-2 rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-100 dark:bg-rose-900/30 dark:text-rose-400 dark:hover:bg-rose-900/50 transition-colors"
                                            title="{{ __('messages.delete') }}">
                                            <i class="mdi mdi-delete text-sm"></i>
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- VIEW 2: LIST / TABLE MODE -->
            <div x-show="viewMode === 'list'" class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/80 dark:bg-gray-900/50 border-b border-gray-100 dark:border-gray-700 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                <th class="px-6 py-4">{{ __('messages.product') }}</th>
                                <th class="px-6 py-4">{{ __('messages.category') }}</th>
                                <th class="px-6 py-4">{{ __('messages.price') }}</th>
                                <th class="px-6 py-4">{{ __('messages.stock') }}</th>
                                <th class="px-6 py-4">{{ __('messages.status') }}</th>
                                @if ($canManage)
                                    <th class="px-6 py-4 text-right">{{ __('messages.actions') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700/60">
                            @foreach($products as $product)
                                <tr class="hover:bg-gray-50/80 dark:hover:bg-gray-700/30 transition-colors">
                                    <!-- Product Info -->
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3.5">
                                            <div class="w-12 h-12 rounded-2xl bg-gray-100 dark:bg-gray-700 overflow-hidden flex-shrink-0 flex items-center justify-center border border-gray-200/60 dark:border-gray-600">
                                                @if ($product->image_path)
                                                    <img src="{{ asset('storage/' . $product->image_path) }}" class="w-full h-full object-cover" alt="{{ $product->name }}">
                                                @else
                                                    <i class="mdi mdi-silverware-fork-knife text-gray-400 text-xl"></i>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="font-bold text-sm text-gray-900 dark:text-white">{{ $product->name }}</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400 line-clamp-1 max-w-xs">{{ $product->description ?: __('messages.no_description') }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Category -->
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-xs font-semibold bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300">
                                            {{ $product->category->name ?? __('messages.no_category') }}
                                        </span>
                                    </td>

                                    <!-- Price -->
                                    <td class="px-6 py-4">
                                        <span class="font-bold text-sm text-orange-500 dark:text-orange-400">
                                            MZN {{ number_format($product->price, 2, ',', '.') }}
                                        </span>
                                    </td>

                                    <!-- Stock -->
                                    <td class="px-6 py-4">
                                        @if($product->stock_quantity > 10)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                {{ $product->stock_quantity }} {{ $product->unit ?: 'un' }}
                                            </span>
                                        @elseif($product->stock_quantity > 0)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                                {{ $product->stock_quantity }} {{ $product->unit ?: 'un' }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-800 dark:bg-rose-900/30 dark:text-rose-300">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                                {{ __('messages.out_of_stock') }}
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Status -->
                                    <td class="px-6 py-4">
                                        @if($product->is_active)
                                            <span class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-600 dark:text-emerald-400">
                                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                                {{ __('messages.active') }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 text-xs font-bold text-rose-500 dark:text-rose-400">
                                                <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                                                {{ __('messages.inactive') }}
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Actions -->
                                    @if ($canManage)
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex items-center justify-end gap-1.5">
                                                <button type="button" @click="editProduct({{ $product->id }})"
                                                    class="p-2 rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400 dark:hover:bg-blue-900/50 transition-colors"
                                                    title="{{ __('messages.edit') }}">
                                                    <i class="mdi mdi-pencil text-base"></i>
                                                </button>
                                                <button type="button" onclick="updateStock({{ $product->id }}, {{ $product->stock_quantity }})"
                                                    class="p-2 rounded-xl bg-emerald-50 text-emerald-600 hover:bg-emerald-100 dark:bg-emerald-900/30 dark:text-emerald-400 dark:hover:bg-emerald-900/50 transition-colors"
                                                    title="{{ __('messages.stock') }}">
                                                    <i class="mdi mdi-package-variant text-base"></i>
                                                </button>
                                                <button type="button" onclick="deleteProduct({{ $product->id }})"
                                                    class="p-2 rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-100 dark:bg-rose-900/30 dark:text-rose-400 dark:hover:bg-rose-900/50 transition-colors"
                                                    title="{{ __('messages.delete') }}">
                                                    <i class="mdi mdi-delete text-base"></i>
                                                </button>
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            @if($products->hasPages())
                <div class="mt-8">
                    {{ $products->links() }}
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm">
                <div class="mb-6 inline-flex items-center justify-center w-20 h-20 rounded-full bg-orange-50 dark:bg-orange-950/40 text-orange-500">
                    <i class="mdi mdi-package-variant-remove text-4xl"></i>
                </div>
                <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ __('messages.no_products_found') }}</h4>
                <p class="text-gray-500 dark:text-gray-400 mb-6 max-w-sm mx-auto text-sm">{{ __('messages.no_products_found_desc') }}</p>
                @if ($canManage)
                    <button type="button" @click="openCreateModal()"
                        class="inline-flex items-center px-6 py-2.5 bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-2xl shadow-lg shadow-orange-500/20 transition-all text-sm gap-2">
                        <i class="mdi mdi-plus text-lg"></i>
                        <span>{{ __('messages.add_first_product') }}</span>
                    </button>
                @endif
            </div>
        @endif

        <!-- Product Modal (Create & Edit) -->
        <div x-show="showModal" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="showModal = false"></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-3xl bg-white dark:bg-gray-800 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-3xl border border-gray-100 dark:border-gray-700">
                    <form @submit.prevent="submitProductForm" enctype="multipart/form-data">
                        <div class="px-8 py-5 border-b border-gray-100 dark:border-gray-700 bg-gray-50/80 dark:bg-gray-900/50 flex justify-between items-center">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                <i class="mdi mdi-package-variant text-orange-500"></i>
                                <span x-text="modalTitle"></span>
                            </h3>
                            <button type="button" @click="showModal = false"
                                class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 transition-colors p-1">
                                <i class="mdi mdi-close text-2xl"></i>
                            </button>
                        </div>

                        <div class="px-8 py-6 max-h-[calc(100vh-200px)] overflow-y-auto">
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-5">
                                <!-- Name -->
                                <div class="md:col-span-8">
                                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 uppercase tracking-wider">
                                        {{ __('messages.product_name') }} *
                                    </label>
                                    <input type="text" name="name" x-model="product.name" required
                                        class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">
                                </div>

                                <!-- Type -->
                                <div class="md:col-span-4">
                                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 uppercase tracking-wider">
                                        {{ __('messages.type') }} *
                                    </label>
                                    <select name="type" x-model="product.type" required
                                        class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">
                                        <option value="product">{{ __('messages.product') }}</option>
                                        <option value="service">{{ __('messages.service') }}</option>
                                    </select>
                                </div>

                                <!-- Category & Status -->
                                <div class="md:col-span-8">
                                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 uppercase tracking-wider">
                                        {{ __('messages.category') }} *
                                    </label>
                                    <select name="category_id" x-model="product.category_id" required
                                        class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">
                                        <option value="">{{ __('messages.select_category') }}</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="md:col-span-4">
                                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 uppercase tracking-wider">
                                        {{ __('messages.status') }}
                                    </label>
                                    <div class="flex items-center mt-2.5">
                                        <label class="inline-flex items-center cursor-pointer">
                                            <input type="checkbox" x-model="product.is_active" class="sr-only peer">
                                            <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-300 dark:peer-focus:ring-orange-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-orange-500">
                                            </div>
                                            <span class="ms-3 text-sm font-bold text-gray-900 dark:text-gray-300" x-text="product.is_active ? '{{ __('messages.active') }}' : '{{ __('messages.inactive') }}'"></span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Prices -->
                                <div class="md:col-span-6">
                                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 uppercase tracking-wider">
                                        {{ __('messages.purchase_price_mzn') }}
                                    </label>
                                    <input type="number" name="purchase_price" x-model="product.purchase_price" step="0.01" min="0" required
                                        class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">
                                </div>

                                <div class="md:col-span-6">
                                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 uppercase tracking-wider">
                                        {{ __('messages.selling_price_mzn') }} *
                                    </label>
                                    <input type="number" name="selling_price" x-model="product.selling_price" step="0.01" min="0" required
                                        class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white text-sm font-bold text-orange-500 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">
                                </div>

                                <!-- Stock & Unit -->
                                <div class="md:col-span-4">
                                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 uppercase tracking-wider">
                                        {{ __('messages.stock') }} *
                                    </label>
                                    <input type="number" name="stock_quantity" x-model="product.stock_quantity" min="0" required
                                        class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">
                                </div>

                                <div class="md:col-span-4">
                                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 uppercase tracking-wider">
                                        {{ __('messages.unit') }}
                                    </label>
                                    <input type="text" name="unit" x-model="product.unit"
                                        class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all"
                                        placeholder="ex: un, kg, lt, dose">
                                </div>

                                <div class="md:col-span-4">
                                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 uppercase tracking-wider">
                                        {{ __('messages.min_stock') }}
                                    </label>
                                    <input type="number" name="min_stock_level" x-model="product.min_stock_level" min="0"
                                        class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">
                                </div>

                                <!-- Description -->
                                <div class="md:col-span-12">
                                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 uppercase tracking-wider">
                                        {{ __('messages.description') }}
                                    </label>
                                    <textarea name="description" x-model="product.description" rows="2"
                                        class="w-full px-4 py-2 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all"
                                        placeholder="Breve descrição dos ingredientes ou características..."></textarea>
                                </div>

                                <!-- Image -->
                                <div class="md:col-span-12">
                                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 uppercase tracking-wider">
                                        {{ __('messages.image') }}
                                    </label>
                                    <input type="file" name="image" id="image" accept="image/*"
                                        class="block w-full text-xs text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100 dark:file:bg-orange-900/30 dark:file:text-orange-300 cursor-pointer">
                                </div>
                            </div>
                        </div>

                        <div class="px-8 py-5 border-t border-gray-100 dark:border-gray-700 bg-gray-50/80 dark:bg-gray-900/50 flex justify-end gap-3">
                            <button type="button" @click="showModal = false"
                                class="px-5 py-2 text-sm font-bold text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors">
                                {{ __('messages.cancel') }}
                            </button>
                            <button type="submit"
                                class="px-7 py-2 bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-xl shadow-lg shadow-orange-500/20 transition-all text-sm flex items-center gap-1.5">
                                <i class="mdi mdi-content-save"></i>
                                <span>{{ __('messages.save') }}</span>
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
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-2 text-left uppercase tracking-wider">{{ __('messages.new_stock') }}</label>
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
                        fetch(`{{ url('/products') }}/${productId}/stock`, {
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
                                setTimeout(() => location.reload(), 800);
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
                        fetch(`{{ url('/products') }}/${productId}`, {
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
                                setTimeout(() => location.reload(), 800);
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
