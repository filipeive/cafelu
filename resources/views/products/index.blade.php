@extends('layouts.app')
@section('title', 'Produtos')

@push('styles')
<style>
    /* ========================================
       PRODUCT CARDS
    ======================================== */
    .product-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: none;
        border-radius: 12px;
        overflow: hidden;
        background: rgba(40, 40, 50, 0.6);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        animation: fadeInUp 0.5s ease forwards;
        opacity: 0;
    }

    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.3);
        border-color: rgba(255, 165, 0, 0.3);
    }

    .product-card:nth-child(1) { animation-delay: 0.1s; }
    .product-card:nth-child(2) { animation-delay: 0.2s; }
    .product-card:nth-child(3) { animation-delay: 0.3s; }
    .product-card:nth-child(4) { animation-delay: 0.4s; }
    .product-card:nth-child(5) { animation-delay: 0.5s; }
    .product-card:nth-child(6) { animation-delay: 0.6s; }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .product-image {
        height: 180px;
        object-fit: cover;
        width: 100%;
        transition: transform 0.3s ease;
    }

    .product-card:hover .product-image {
        transform: scale(1.05);
    }

    .no-image-placeholder {
        height: 180px;
        background: linear-gradient(135deg, rgba(75, 73, 172, 0.1) 0%, rgba(255, 165, 0, 0.1) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: rgba(255, 255, 255, 0.5);
    }

    .product-info {
        padding: 1.25rem;
    }

    .product-name {
        font-size: 0.95rem;
        font-weight: 600;
        color: #ffffff;
        margin-bottom: 0.5rem;
        line-height: 1.4;
    }

    .product-category {
        font-size: 0.75rem;
        background: rgba(75, 73, 172, 0.15);
        color: #b19cd9;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        display: inline-block;
        margin-bottom: 0.75rem;
    }

    .product-price {
        font-size: 1.25rem;
        font-weight: 700;
        color: #FFA500;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }

    .badge-stock {
        font-size: 0.7rem;
        padding: 0.35rem 0.7rem;
        border-radius: 20px;
        font-weight: 600;
        letter-spacing: 0.5px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    .product-actions {
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        padding-top: 1rem;
        margin-top: 1rem;
    }

    .product-actions .d-flex {
        display: flex;
        gap: 0.5rem;
    }

    /* Fallback para navegadores sem suporte a gap */
    .product-actions .btn-action:not(:last-child) {
        margin-right: 0.5rem;
    }

    .product-actions .d-flex {
        margin-right: -0.5rem;
    }

    .btn-action {
        border-radius: 8px;
        padding: 0.5rem 0.75rem;
        transition: all 0.3s ease;
        border: 1px solid rgba(255, 255, 255, 0.2);
        background: rgba(255, 255, 255, 0.05);
        color: rgba(255, 255, 255, 0.9);
        font-size: 0.875rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .btn-action i {
        font-size: 1rem;
    }

    .btn-edit {
        border-color: rgba(59, 130, 246, 0.3);
        background: rgba(59, 130, 246, 0.1);
        color: #60a5fa;
    }

    .btn-edit:hover {
        background: rgba(59, 130, 246, 0.3);
        border-color: #3b82f6;
        color: #3b82f6;
    }

    .btn-stock {
        border-color: rgba(34, 197, 94, 0.3);
        background: rgba(34, 197, 94, 0.1);
        color: #4ade80;
    }

    .btn-stock:hover {
        background: rgba(34, 197, 94, 0.3);
        border-color: #22c55e;
        color: #22c55e;
    }

    .btn-delete {
        border-color: rgba(239, 68, 68, 0.3);
        background: rgba(239, 68, 68, 0.1);
        color: #f87171;
    }

    .btn-delete:hover {
        background: rgba(239, 68, 68, 0.3);
        border-color: #ef4444;
        color: #ef4444;
    }

    /* Botão adicionar produto */
    .btn-gradient-primary {
        background: linear-gradient(135deg, #4B49AC 0%, #FFA500 100%);
        border: none;
        color: white;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(75, 73, 172, 0.3);
        transition: all 0.3s ease;
    }

    .btn-gradient-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(75, 73, 172, 0.4);
        color: white;
    }

    .btn-gradient-info {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        border: none;
        color: white;
        font-weight: 600;
    }

    .btn-gradient-info:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
        color: white;
    }

    /* ========================================
       MODAL STYLES
    ======================================== */
    .modal-content {
        background: rgba(40, 40, 50, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        color: #ffffff;
    }

    .modal-header {
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        padding: 1.5rem;
    }

    .modal-body {
        padding: 1.5rem;
        max-height: 70vh;
        overflow-y: auto;
    }

    .modal-body::-webkit-scrollbar {
        width: 6px;
    }

    .modal-body::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.05);
    }

    .modal-body::-webkit-scrollbar-thumb {
        background: rgba(255, 165, 0, 0.3);
        border-radius: 3px;
    }

    .modal-footer {
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        padding: 1.5rem;
    }

    /* ========================================
       FORM STYLES
    ======================================== */
    .form-control, .form-select {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #ffffff;
        border-radius: 8px;
        padding: 0.75rem 1rem;
    }

    .form-control:focus, .form-select:focus {
        background: rgba(255, 255, 255, 0.08);
        border-color: #4B49AC;
        box-shadow: 0 0 0 0.2rem rgba(75, 73, 172, 0.25);
        color: #ffffff;
    }

    .form-control::placeholder {
        color: rgba(255, 255, 255, 0.4);
    }

    .form-label {
        color: rgba(255, 255, 255, 0.8);
        font-weight: 500;
        margin-bottom: 0.5rem;
    }

    .input-group-text {
        background: rgba(75, 73, 172, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: rgba(255, 255, 255, 0.7);
    }

    .custom-file-label {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: rgba(255, 255, 255, 0.7);
    }

    .custom-file-label::after {
        background: rgba(75, 73, 172, 0.2);
        color: #ffffff;
    }

    /* ========================================
       PAGINATION
    ======================================== */
    .pagination .page-item .page-link {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: rgba(255, 255, 255, 0.7);
        margin: 0 2px;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #4B49AC 0%, #FFA500 100%);
        border-color: transparent;
        color: white;
    }

    .pagination .page-item:not(.disabled) .page-link:hover {
        background: rgba(75, 73, 172, 0.3);
        border-color: #4B49AC;
        color: #ffffff;
    }

    /* ========================================
       UTILITIES
    ======================================== */
    .text-muted {
        color: rgba(255, 255, 255, 0.5) !important;
    }

    .card {
        background: rgba(40, 40, 50, 0.6);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .card-title {
        color: #ffffff;
        font-weight: 600;
    }

    .bg-dark {
        background-color: rgba(40, 40, 50, 0.8) !important;
    }

    .border-light {
        border-color: rgba(255, 255, 255, 0.1) !important;
    }

    /* Garantir visibilidade dos botões */
    button, .btn {
        cursor: pointer;
    }

    .btn:focus, button:focus {
        outline: none;
        box-shadow: 0 0 0 0.2rem rgba(75, 73, 172, 0.25);
    }

    /* Garantir que botões não sejam transparent */
    .btn-action {
        opacity: 1 !important;
        visibility: visible !important;
    }
</style>
@endpush

@section('content')
<div class="container-wrapper">
    <!-- Page Header -->
    <div class="page-header">
        <h3 class="page-title">
            <i class="mdi mdi-package-variant-closed text-warning mr-2"></i>
            Gerenciamento de Produtos
        </h3>
    </div>

    <!-- Main Card -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <!-- Header -->
                    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
                        <div class="mb-3 mb-md-0">
                            <h4 class="card-title mb-1 text-white">Todos os Produtos</h4>
                            <p class="text-white mb-0">Total: {{ $products->total() }} produtos</p>
                        </div>
                        @if (Auth::user()->role == 'admin')
                            <button type="button" class="btn btn-gradient-primary btn-lg"
                                onclick="openCreateModal()">
                                <i class="mdi mdi-plus mr-2"></i>
                                Novo Produto
                            </button>
                        @endif
                    </div>

                    <!-- Filters -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card bg-dark border-light">
                                <div class="card-body py-3">
                                    <form method="GET" action="{{ route('products.index') }}">
                                        <div class="row g-3 align-items-center">
                                            <div class="col-md-5">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text bg-transparent border-right-0">
                                                            <i class="mdi mdi-magnify"></i>
                                                        </span>
                                                    </div>
                                                    <input type="text" class="form-control form-control-lg border-left-0" 
                                                        name="search" placeholder="Buscar produtos..." 
                                                        value="{{ request('search') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <select class="form-control form-control-lg bg-dark" name="category">
                                                    <option value="">Todas as Categorias</option>
                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}"
                                                            {{ request('category') == $category->id ? 'selected' : '' }}>
                                                            {{ $category->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <button type="submit" class="btn btn-gradient-info btn-lg w-100">
                                                    <i class="mdi mdi-filter mr-1"></i> Filtrar
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Products Grid -->
                    @if($products->count() > 0)
                        <div class="row">
                            @foreach($products as $product)
                                <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                                    <div class="card product-card">
                                        <!-- Image -->
                                        <div class="position-relative overflow-hidden">
                                            @if ($product->image_path)
                                                <img src="{{ asset('storage/' . $product->image_path) }}"
                                                    class="product-image" alt="{{ $product->name }}">
                                            @else
                                                <div class="no-image-placeholder">
                                                    <i class="mdi mdi-image-off" style="font-size: 3rem;"></i>
                                                </div>
                                            @endif

                                            <!-- Stock Badge -->
                                            <div class="position-absolute top-0 end-0 m-3">
                                                <span class="badge-stock {{ $product->stock_quantity > 10 ? 'bg-success' : ($product->stock_quantity > 5 ? 'bg-warning' : 'bg-danger') }}">
                                                    <i class="mdi mdi-package mr-1"></i>{{ $product->stock_quantity }}
                                                </span>
                                            </div>

                                            <!-- Status Badge -->
                                            @if(!$product->is_active)
                                                <div class="position-absolute top-0 start-0 m-3">
                                                    <span class="badge bg-danger">Inativo</span>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <!-- Info -->
                                        <div class="product-info">
                                            <!-- Category -->
                                            <div class="product-category mb-2">
                                                <i class="mdi mdi-tag-outline mr-1"></i>
                                                {{ $product->category->name ?? 'Sem categoria' }}
                                            </div>
                                            
                                            <!-- Name -->
                                            <h5 class="product-name" title="{{ $product->name }}">
                                                {{ $product->name }}
                                            </h5>
                                            
                                            <!-- Price & Type -->
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <span class="product-price">
                                                    MZN {{ number_format($product->price, 2, ',', '.') }}
                                                </span>
                                                <span class="badge badge-light">
                                                    {{ $product->type == 'product' ? 'Produto' : 'Serviço' }}
                                                </span>
                                            </div>

                                            <!-- Description -->
                                            @if($product->description)
                                                <p class="text-muted small mb-3">
                                                    {{ Str::limit($product->description, 80) }}
                                                </p>
                                            @endif

                                            <!-- Actions -->
                                            @if (Auth::user()->role == 'admin')
                                                <div class="product-actions">
                                                    <div class="d-flex gap-2">
                                                        <button type="button" class="btn btn-action btn-edit flex-fill"
                                                            onclick="editProduct({{ $product->id }})"
                                                            title="Editar">
                                                            <i class="mdi mdi-pencil mr-1"></i>
                                                            <span class="d-none d-lg-inline">Editar</span>
                                                        </button>
                                                        <button type="button" class="btn btn-action btn-stock flex-fill"
                                                            onclick="updateStock({{ $product->id }}, {{ $product->stock_quantity }})"
                                                            title="Atualizar Estoque">
                                                            <i class="mdi mdi-package-variant mr-1"></i>
                                                            <span class="d-none d-lg-inline">Estoque</span>
                                                        </button>
                                                        <button type="button" class="btn btn-action btn-delete flex-fill"
                                                            onclick="deleteProduct({{ $product->id }})"
                                                            title="Excluir">
                                                            <i class="mdi mdi-delete mr-1"></i>
                                                            <span class="d-none d-lg-inline">Excluir</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="mdi mdi-package-variant-remove text-muted" style="font-size: 5rem;"></i>
                            </div>
                            <h4 class="text-muted">Nenhum produto encontrado</h4>
                            <p class="text-muted mb-4">Ajuste os filtros ou adicione novos produtos.</p>
                            @if (Auth::user()->role == 'admin')
                                <button type="button" class="btn btn-gradient-primary"
                                    onclick="openCreateModal()">
                                    <i class="mdi mdi-plus mr-2"></i>
                                    Adicionar Primeiro Produto
                                </button>
                            @endif
                        </div>
                    @endif

                    <!-- Pagination -->
                    @if($products->hasPages())
                        <div class="row mt-4">
                            <div class="col-12">
                                <nav aria-label="Navegação de páginas">
                                    {{ $products->links('pagination::bootstrap-5') }}
                                </nav>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Product Modal -->
<div class="modal fade" id="productModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form id="productForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="product_id">
                <input type="hidden" id="form_method" value="POST">

                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="mdi mdi-package-variant mr-2"></i>
                        <span id="modalTitleText">Novo Produto</span>
                    </h5>
                    <button type="button" class="close text-white" data-bs-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="name" class="form-label">Nome do Produto *</label>
                                <input type="text" class="form-control" id="name" name="name" required
                                    placeholder="Digite o nome do produto">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="type" class="form-label">Tipo *</label>
                                <select class="form-control" id="type" name="type" required>
                                    <option value="product">Produto</option>
                                    <option value="service">Serviço</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description" class="form-label">Descrição</label>
                        <textarea class="form-control" id="description" name="description" rows="3"
                            placeholder="Digite uma descrição para o produto"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="purchase_price" class="form-label">Preço de Custo (MZN) *</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">MZN</span>
                                    </div>
                                    <input type="number" class="form-control" id="purchase_price" name="purchase_price"
                                        step="0.01" min="0" required placeholder="0.00">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="selling_price" class="form-label">Preço de Venda (MZN) *</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">MZN</span>
                                    </div>
                                    <input type="number" class="form-control" id="selling_price" name="selling_price"
                                        step="0.01" min="0" required placeholder="0.00">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="stock_quantity" class="form-label">Estoque *</label>
                                <input type="number" class="form-control" id="stock_quantity" name="stock_quantity"
                                    min="0" required placeholder="0">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="unit" class="form-label">Unidade</label>
                                <input type="text" class="form-control" id="unit" name="unit"
                                    placeholder="ex: un, kg, lt">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="min_stock_level" class="form-label">Estoque Mínimo</label>
                                <input type="number" class="form-control" id="min_stock_level" name="min_stock_level"
                                    min="0" value="5">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="category_id" class="form-label">Categoria *</label>
                                <select class="form-control" id="category_id" name="category_id" required>
                                    <option value="">Selecione uma categoria</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label d-block">Status</label>
                                <div class="form-check form-check-flat form-check-primary mt-2">
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" id="is_active"
                                            name="is_active" value="1" checked>
                                        Produto Ativo
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="image" class="form-label">Imagem do Produto</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="image" name="image" accept="image/*">
                            <label class="custom-file-label" for="image" id="imageLabel">Escolher arquivo</label>
                        </div>
                        <small class="form-text text-muted">Formatos: JPG, PNG, GIF. Máximo: 2MB</small>
                        <div class="mt-2" id="imagePreview"></div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="mdi mdi-close mr-2"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-gradient-primary">
                        <i class="mdi mdi-content-save mr-2"></i>Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        /**
         * ========================================
         * PRODUCTS MANAGEMENT JAVASCRIPT
         * ========================================
         */

        const productModal = new bootstrap.Modal(document.getElementById('productModal'));

        /**
         * Open modal to create new product
         */
        function openCreateModal() {
            document.getElementById('productForm').reset();
            document.getElementById('product_id').value = '';
            document.getElementById('form_method').value = 'POST';
            document.getElementById('modalTitleText').textContent = 'Novo Produto';
            document.getElementById('is_active').checked = true;
            document.getElementById('imagePreview').innerHTML = '';
            document.getElementById('imageLabel').textContent = 'Escolher arquivo';
            productModal.show();
        }

        /**
         * Edit existing product
         * @param {number} productId - Product ID
         */
        function editProduct(productId) {
            showLoading();

            fetch(`/products/${productId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erro ao carregar produto');
                    }
                    return response.json();
                })
                .then(data => {
                    // Populate form fields
                    document.getElementById('product_id').value = data.id;
                    document.getElementById('form_method').value = 'PUT';
                    document.getElementById('modalTitleText').textContent = 'Editar Produto';

                    document.getElementById('name').value = data.name;
                    document.getElementById('description').value = data.description || '';
                    document.getElementById('purchase_price').value = data.purchase_price;
                    document.getElementById('selling_price').value = data.selling_price;
                    document.getElementById('stock_quantity').value = data.stock_quantity;
                    document.getElementById('category_id').value = data.category_id;
                    document.getElementById('type').value = data.type || 'product';
                    document.getElementById('unit').value = data.unit || '';
                    document.getElementById('min_stock_level').value = data.min_stock_level || 5;
                    document.getElementById('is_active').checked = data.is_active == 1;

                    // Show current image if exists
                    if (data.image_path) {
                        document.getElementById('imagePreview').innerHTML = `
                    <div class="border rounded p-2 mt-2" style="background: rgba(255, 255, 255, 0.05);">
                        <img src="/storage/${data.image_path}" 
                             class="img-fluid rounded" 
                             style="max-height: 150px; display: block; margin: 0 auto;">
                        <p class="text-muted text-center mt-2 mb-0">Imagem atual</p>
                    </div>`;
                    }

                    hideLoading();
                    productModal.show();
                })
                .catch(error => {
                    hideLoading();
                    console.error('Error:', error);
                    showToast('Não foi possível carregar os dados do produto', 'error');
                });
        }

        /**
         * Update product stock
         * @param {number} productId - Product ID
         * @param {number} currentStock - Current stock quantity
         */
        function updateStock(productId, currentStock) {
            Swal.fire({
                title: 'Atualizar Estoque',
                html: `
            <div class="text-center mb-4">
                <i class="mdi mdi-package-variant text-warning" style="font-size: 4rem;"></i>
            </div>
            <div class="form-group text-left">
                <label for="stockQuantity" class="text-white mb-2">
                    <strong>Estoque atual:</strong> 
                    <span class="badge badge-info">${currentStock} unidades</span>
                </label>
                <input type="number" 
                       class="form-control form-control-lg text-center" 
                       id="stockQuantity" 
                       min="0" 
                       value="${currentStock}"
                       style="background: rgba(255, 255, 255, 0.1); color: white; border: 1px solid rgba(255, 255, 255, 0.2);"
                       autofocus>
                <small class="text-muted mt-2 d-block">Digite a nova quantidade em estoque</small>
            </div>
        `,
                showCancelButton: true,
                confirmButtonText: '<i class="mdi mdi-check mr-2"></i>Atualizar',
                cancelButtonText: '<i class="mdi mdi-close mr-2"></i>Cancelar',
                confirmButtonColor: '#22c55e',
                cancelButtonColor: '#6b7280',
                background: '#1a1a2e',
                color: '#ffffff',
                customClass: {
                    popup: 'animated-popup'
                },
                preConfirm: () => {
                    const value = document.getElementById('stockQuantity').value;
                    if (!value || value < 0) {
                        Swal.showValidationMessage('Por favor, insira um valor válido!');
                        return false;
                    }
                    return value;
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
                            body: JSON.stringify({
                                stock_quantity: result.value
                            })
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Erro ao atualizar estoque');
                            }
                            return response.json();
                        })
                        .then(data => {
                            hideLoading();
                            if (data.success) {
                                showToast(data.message || 'Estoque atualizado com sucesso!', 'success');
                                setTimeout(() => location.reload(), 1500);
                            } else {
                                showToast(data.message || 'Erro ao atualizar estoque', 'error');
                            }
                        })
                        .catch(error => {
                            hideLoading();
                            console.error('Error:', error);
                            showToast('Não foi possível atualizar o estoque', 'error');
                        });
                }
            });
        }

        /**
         * Delete product
         * @param {number} productId - Product ID
         */
        function deleteProduct(productId) {
            Swal.fire({
                title: 'Tem certeza?',
                html: `
            <div class="text-center mb-3">
                <i class="mdi mdi-alert-circle-outline text-danger" style="font-size: 4rem;"></i>
            </div>
            <p class="text-white">Esta ação não pode ser revertida!</p>
            <p class="text-muted">O produto será permanentemente excluído do sistema.</p>
        `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '<i class="mdi mdi-delete mr-2"></i>Sim, excluir!',
                cancelButtonText: '<i class="mdi mdi-close mr-2"></i>Cancelar',
                background: '#1a1a2e',
                color: '#ffffff',
                iconColor: '#ef4444',
                customClass: {
                    popup: 'animated-popup'
                }
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
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Erro ao excluir produto');
                            }
                            return response.json();
                        })
                        .then(data => {
                            hideLoading();
                            if (data.success) {
                                showToast(data.message || 'Produto excluído com sucesso!', 'success');
                                setTimeout(() => location.reload(), 1500);
                            } else {
                                showToast(data.message || 'Erro ao excluir produto', 'error');
                            }
                        })
                        .catch(error => {
                            hideLoading();
                            console.error('Error:', error);
                            showToast('Não foi possível excluir o produto', 'error');
                        });
                }
            });
        }

        /**
         * Handle image preview
         */
        function handleImagePreview() {
            const imageInput = document.getElementById('image');

            if (!imageInput) return;

            imageInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                const preview = document.getElementById('imagePreview');
                const label = document.getElementById('imageLabel');

                if (file) {
                    // Update label with filename
                    label.textContent = file.name;

                    // Validate file type
                    if (!file.type.startsWith('image/')) {
                        showToast('Por favor, selecione um arquivo de imagem válido', 'warning');
                        this.value = '';
                        label.textContent = 'Escolher arquivo';
                        preview.innerHTML = '';
                        return;
                    }

                    // Validate file size (2MB)
                    if (file.size > 2 * 1024 * 1024) {
                        showToast('A imagem deve ter no máximo 2MB', 'warning');
                        this.value = '';
                        label.textContent = 'Escolher arquivo';
                        preview.innerHTML = '';
                        return;
                    }

                    // Show preview
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.innerHTML = `
                    <div class="border rounded p-2 mt-2" style="background: rgba(255, 255, 255, 0.05);">
                        <img src="${e.target.result}" 
                             class="img-fluid rounded" 
                             style="max-height: 150px; display: block; margin: 0 auto;">
                        <p class="text-muted text-center mt-2 mb-0">
                            <i class="mdi mdi-eye mr-1"></i>Pré-visualização
                        </p>
                    </div>`;
                    };
                    reader.readAsDataURL(file);
                } else {
                    label.textContent = 'Escolher arquivo';
                    preview.innerHTML = '';
                }
            });
        }

        /**
         * Handle form submission
         */
        function handleFormSubmit() {
            const form = document.getElementById('productForm');

            if (!form) return;

            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const productId = document.getElementById('product_id').value;
                const method = document.getElementById('form_method').value;
                const formData = new FormData(this);

                // Add method for PUT requests
                if (method === 'PUT') {
                    formData.append('_method', 'PUT');
                }

                const url = productId ? `/products/${productId}` : '/products';

                // Disable submit button and show loading
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="mdi mdi-loading mdi-spin mr-2"></i>Salvando...';
                submitBtn.disabled = true;

                showLoading();

                fetch(url, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Erro ao salvar produto');
                        }
                        return response.json();
                    })
                    .then(data => {
                        hideLoading();
                        if (data.success) {
                            productModal.hide();
                            showToast(data.message || 'Produto salvo com sucesso!', 'success');
                            setTimeout(() => location.reload(), 1500);
                        } else {
                            let errorMessage = data.message || 'Erro ao salvar produto';
                            if (data.errors) {
                                const errors = Object.values(data.errors).flat();
                                errorMessage = errors.join('<br>');
                            }
                            showToast(errorMessage, 'error');
                        }
                    })
                    .catch(error => {
                        hideLoading();
                        console.error('Error:', error);
                        showToast('Ocorreu um erro ao processar sua solicitação', 'error');
                    })
                    .finally(() => {
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    });
            });
        }

        /**
         * Initialize tooltips
         */
        function initializeTooltips() {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"], [title]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }

        /**
         * Initialize on DOM ready
         */
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize image preview handler
            handleImagePreview();

            // Initialize form submit handler
            handleFormSubmit();

            // Initialize tooltips
            initializeTooltips();

            // Animate product cards on load
            const cards = document.querySelectorAll('.product-card');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
            });

            console.log('Products management initialized');
        });
    </script>
@endpush
