@extends('layouts.app')

@section('content')
<style>
    /* Estilos aprimorados */
    .product-card {
        transition: all 0.3s ease;
        border-radius: 10px;
        overflow: hidden;
    }
    
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .badge-stock {
        font-size: 0.7rem;
        padding: 3px 8px;
        border-radius: 10px;
        font-weight: 600;
    }
    
    .card-img-placeholder {
        height: 120px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f8f9fa;
    }
    
    .card-img-placeholder i {
        font-size: 2rem;
        color: #adb5bd;
    }
    
    .filter-card {
        background-color: #f8f9fa;
        border: none;
        border-radius: 10px;
    }
    
    .action-buttons .btn {
        border-radius: 5px;
        padding: 0.25rem 0.5rem;
    }
    
    .pagination .page-item.active .page-link {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    
    .page-title-icon {
        font-size: 1.5rem;
        margin-right: 10px;
        color: #0d6efd;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <!-- Cabeçalho -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h4 class="card-title mb-1 d-flex align-items-center">
                                <i class="ti-package page-title-icon"></i>
                                Gerenciamento de Produtos
                            </h4>
                            <p class="text-muted small mb-0">
                                <span class="badge bg-primary bg-opacity-10 text-primary">
                                    Total: {{ $products->total() }} produtos
                                </span>
                                @if(request()->has('search') || request()->has('category'))
                                    <span class="badge bg-info bg-opacity-10 text-info ms-2">
                                        Filtros ativos
                                    </span>
                                @endif
                            </p>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-primary d-flex align-items-center gap-2"
                                data-bs-toggle="modal" data-bs-target="#addProductModal">
                                <i class="ti-plus"></i>
                                <span>Adicionar Produto</span>
                            </button>
                            <button type="button" class="btn btn-outline-primary d-flex align-items-center gap-2"
                                data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                                <i class="ti-tag"></i>
                                <span>Nova Categoria</span>
                            </button>
                        </div>
                    </div>

                    <!-- Filtros -->
                    <div class="card filter-card mb-4">
                        <div class="card-body p-3">
                            <form method="GET" action="{{ route('products.index') }}">
                                <div class="row g-3">
                                    <div class="col-md-5">
                                        <div class="input-group">
                                            <span class="input-group-text bg-transparent border-end-0">
                                                <i class="ti-search text-muted"></i>
                                            </span>
                                            <input type="text" class="form-control border-start-0" name="search"
                                                placeholder="Buscar por nome, descrição..." value="{{ request('search') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="input-group">
                                            <span class="input-group-text bg-transparent border-end-0">
                                                <i class="ti-layers text-muted"></i>
                                            </span>
                                            <select class="form-select border-start-0" name="category">
                                                <option value="">Todas as Categorias</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}" {{ $category->id == request('category') ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2">
                                            <i class="ti-filter"></i>
                                            <span>Filtrar</span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Lista de Produtos -->
                    @if ($products->count() > 0)
                        <div class="row g-3">
                            @foreach ($products as $product)
                                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                                    <div class="card product-card h-100">
                                        <div class="position-relative">
                                            @if ($product->image_path)
                                                <img src="{{ asset('storage/' . $product->image_path) }}" 
                                                    class="card-img-top img-fluid" 
                                                    alt="{{ $product->name }}"
                                                    style="height: 160px; object-fit: cover;">
                                            @else
                                                <div class="card-img-placeholder">
                                                    <i class="ti-image"></i>
                                                </div>
                                            @endif

                                            <!-- Indicador de Estoque -->
                                            <div class="position-absolute top-0 end-0 m-2">
                                                <span class="badge-stock {{ $product->stock_quantity > 10 ? 'bg-success' : ($product->stock_quantity > 5 ? 'bg-warning' : 'bg-danger') }}">
                                                    {{ $product->stock_quantity }} em estoque
                                                </span>
                                            </div>
                                        </div>

                                        <div class="card-body">
                                            <!-- Categoria -->
                                            <div class="mb-2">
                                                <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill">
                                                    {{ $product->category->name ?? 'Sem categoria' }}
                                                </span>
                                            </div>

                                            <!-- Nome e Descrição -->
                                            <h6 class="card-title mb-2">{{ $product->name }}</h6>
                                            <p class="card-text text-muted small mb-2 text-truncate" style="max-height: 20px;">
                                                {{ $product->description ?? 'Sem descrição' }}
                                            </p>

                                            <!-- Preço -->
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <span class="fw-bold text-success">
                                                    MZN {{ number_format($product->price, 2, ',', '.') }}
                                                </span>
                                                <span class="small text-muted">
                                                    @if($product->is_active)
                                                        <i class="ti-check-circle text-success"></i> Ativo
                                                    @else
                                                        <i class="ti-close-circle text-danger"></i> Inativo
                                                    @endif
                                                </span>
                                            </div>

                                            <!-- Ações -->
                                            <div class="d-flex gap-2 action-buttons">
                                                <button class="btn btn-outline-primary btn-sm flex-grow-1" 
                                                    onclick="editProduct({{ $product->id }})">
                                                    <i class="ti-pencil me-1"></i> Editar
                                                </button>
                                                <button class="btn btn-outline-secondary btn-sm" 
                                                    onclick="updateStock({{ $product->id }})"
                                                    title="Atualizar Estoque">
                                                    <i class="ti-package"></i>
                                                </button>
                                                <button class="btn btn-outline-danger btn-sm" 
                                                    onclick="confirmDelete({{ $product->id }})"
                                                    title="Excluir">
                                                    <i class="ti-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-info d-flex align-items-center" role="alert">
                            <i class="ti-info-circle me-2"></i>
                            Nenhum produto encontrado. Ajuste os filtros ou adicione novos produtos.
                        </div>
                    @endif

                    <!-- Paginação -->
                    @if ($products->lastPage() > 1)
                        <div class="mt-4">
                            <nav aria-label="Navegação de páginas">
                                <ul class="pagination justify-content-center">
                                    <li class="page-item {{ $products->currentPage() == 1 ? 'disabled' : '' }}">
                                        <a class="page-link" href="{{ $products->previousPageUrl() }}" aria-label="Anterior">
                                            <i class="ti-angle-left"></i>
                                        </a>
                                    </li>

                                    @for ($i = 1; $i <= $products->lastPage(); $i++)
                                        <li class="page-item {{ $i == $products->currentPage() ? 'active' : '' }}">
                                            <a class="page-link" href="{{ $products->url($i) }}">{{ $i }}</a>
                                        </li>
                                    @endfor

                                    <li class="page-item {{ $products->currentPage() == $products->lastPage() ? 'disabled' : '' }}">
                                        <a class="page-link" href="{{ $products->nextPageUrl() }}" aria-label="Próxima">
                                            <i class="ti-angle-right"></i>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Adicionar Produto -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProductModalLabel">Adicionar Novo Produto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="productForm" action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Nome do Produto</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="category_id" class="form-label">Categoria</label>
                            <select class="form-select" id="category_id" name="category_id">
                                <option value="">Selecione uma categoria</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="price" class="form-label">Preço (MZN)</label>
                            <input type="number" step="0.01" class="form-control" id="price" name="price" required>
                        </div>
                        <div class="col-md-6">
                            <label for="stock_quantity" class="form-label">Quantidade em Estoque</label>
                            <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" required>
                        </div>
                        <div class="col-12">
                            <label for="description" class="form-label">Descrição</label>
                            <textarea class="form-control" id="description" name="description" rows="2"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="image" class="form-label">Imagem do Produto</label>
                            <input class="form-control" type="file" id="image" name="image" accept="image/*">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" checked>
                                <label class="form-check-label" for="is_active">Produto Ativo</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar Produto</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Adicionar Categoria -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCategoryModalLabel">Adicionar Nova Categoria</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="categoryName" class="form-label">Nome da Categoria</label>
                        <input type="text" class="form-control" id="categoryName" name="name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar Categoria</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Função para editar produto
    function editProduct(id) {
        fetch(`/products/${id}/edit`)
            .then(response => response.json())
            .then(product => {
                // Preencher o modal de edição com os dados do produto
                $('#editProductModal #name').val(product.name);
                $('#editProductModal #description').val(product.description);
                $('#editProductModal #price').val(product.price);
                $('#editProductModal #stock_quantity').val(product.stock_quantity);
                $('#editProductModal #category_id').val(product.category_id);
                $('#editProductModal #is_active').prop('checked', product.is_active);
                
                // Atualizar o formulário para apontar para a rota de atualização
                $('#editProductModal form').attr('action', `/products/${id}`);
                $('#editProductModal').modal('show');
            })
            .catch(error => console.error('Error:', error));
    }

    // Função para confirmar exclusão
    function confirmDelete(id) {
        Swal.fire({
            title: 'Tem certeza?',
            text: "Você não poderá reverter isso!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sim, excluir!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Enviar requisição de exclusão
                fetch(`/products/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => {
                    if (response.ok) {
                        location.reload();
                    }
                });
            }
        });
    }

    // Função para atualizar estoque
    function updateStock(id) {
        Swal.fire({
            title: 'Atualizar Estoque',
            input: 'number',
            inputLabel: 'Nova quantidade em estoque',
            inputPlaceholder: 'Digite a quantidade',
            showCancelButton: true,
            confirmButtonText: 'Atualizar',
            cancelButtonText: 'Cancelar',
            inputValidator: (value) => {
                if (!value || value < 0) {
                    return 'Por favor, digite uma quantidade válida!';
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/products/${id}/stock`, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ stock_quantity: result.value })
                })
                .then(response => {
                    if (response.ok) {
                        location.reload();
                    }
                });
            }
        });
    }

    // Inicializar tooltips
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@endsection