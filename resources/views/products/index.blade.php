@extends('layouts.app')
@section('title', 'Produtos')
@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        .product-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            border: none;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .category-badge {
            position: absolute;
            top: 10px;
            right: 10px;
        }

        .badge-stock {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            color: white;
        }

        .btn-group .btn {
            flex: 1;
            padding: 0.375rem;
        }

        .price-tag {
            display: flex;
            flex-direction: column;
        }

        .stock-info {
            font-size: 0.9rem;
        }

        .pagination {
            margin-bottom: 0;
        }

        .input-group-text {
            border: none;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #4B49AC;
            box-shadow: 0 0 0 0.2rem rgba(75, 73, 172, 0.25);
        }

        .card {
            border-radius: 0.5rem;
            transition: all 0.2s ease;
        }

        .shadow-hover:hover {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
        }

        .alert {
            border-radius: 0.375rem;
        }
        /* Estilos específicos para o campo de preço */
        .form-label {
            font-weight: 600;
            color: #2d2d2d;
            margin-bottom: .35rem;
            font-size: .95rem;
        }

        .input-group-text {
            background: #f5f7fb;
            border: 1px solid #e6e9ef;
            color: #495057;
            font-weight: 600;
            min-width: 56px;
            justify-content: center;
        }

        .price-input {
            border-left: 0;
            border-radius: .375rem;
            box-shadow: inset 0 1px 2px rgba(0,0,0,0.03);
            transition: box-shadow .15s ease, border-color .15s ease;
        }

        .price-input:focus {
            outline: none;
            border-color: #6c63ff;
            box-shadow: 0 0 0 .15rem rgba(108,99,255,0.12);
        }

        .form-text {
            color: #6c757d;
            font-size: .85rem;
        }

        @media (max-width: 576px) {
            .input-group-text {
                min-width: 46px;
                font-size: .9rem;
            }
            .price-input::placeholder { font-size: .95rem; }
        }
    </style>
@endsection

@section('content')
    <div class="container-wrapper">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card shadow-sm">
                <div class="card-body">
                    <!-- Header Section -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h4 class="card-title d-flex align-items-center gap-2 mb-1 text-primary">
                                <i class="mdi mdi-package"></i> Gerenciamento de Produtos
                            </h4>
                            <p class="text-muted small mb-0">Total: {{ $products->total() }} produtos</p>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-primary btn-sm d-flex align-items-center gap-1"
                                data-bs-toggle="modal" data-bs-target="#productModal">
                                <i class="mdi mdi-plus"></i>
                                <span>Novo Produto</span>
                            </button>
                        </div>
                    </div>

                    <!-- Filtros -->
                    <form method="GET" action="{{ route('products.index') }}" class="mb-4">
                        <div class="row g-2">
                            <div class="col-md-5">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-transparent border-0">
                                        <i class="mdi mdi-magnify text-muted"></i>
                                    </span>
                                    <input type="text" class="form-control form-control-sm border-0" name="search"
                                        placeholder="Buscar produtos..." value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-transparent border-0">
                                        <i class="mdi mdi-layers text-muted"></i>
                                    </span>
                                    <select class="form-select form-select-sm border-0" name="category">
                                        <option value="">Todas as Categorias</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ request('category') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary btn-sm w-100">
                                    <i class="mdi mdi-filter me-1"></i>Filtrar
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Grid de Produtos -->
                    <div class="row g-2">
                        @forelse ($products as $product)
                            <div class="col-6 col-md-4 col-lg-4">
                                <div class="card product-card h-100 border shadow-hover">
                                    <div class="position-relative">
                                        @if ($product->image_path)
                                            <img src="{{ asset('storage/' . $product->image_path) }}" class="card-img-top"
                                                alt="{{ $product->name }}" style="height: 120px; object-fit: cover;">
                                        @else
                                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                                style="height: 120px;">
                                                <i class="mdi mdi-image-off text-muted" style="font-size: 2rem;"></i>
                                            </div>
                                        @endif
                                        <!-- Stock Badge -->
                                        <div class="position-absolute top-0 end-0 m-1">
                                            <span
                                                class="badge-stock {{ $product->stock_quantity > 10 ? 'bg-success' : ($product->stock_quantity > 5 ? 'bg-warning' : 'bg-danger') }}">
                                                {{ $product->stock_quantity }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="card-body p-2">
                                        <!-- Category Badge -->
                                        <span class="badge bg-primary bg-opacity-10 text-primary mb-1 rounded-pill">
                                            <i class="mdi mdi-tag me-1"></i>
                                            {{ $product->category->name ?? '-' }}
                                        </span>
                                        <h6 class="card-title mb-2 text-truncate" title="{{ $product->name }}">
                                            {{ $product->name }}
                                        </h6>
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="fw-bold text-success">
                                                MZN {{ number_format($product->price, 2, ',', '.') }}
                                            </span>
                                        </div>
                                        @if (Auth::user()->role == 'admin')
                                            <div class="btn-group btn-group-sm w-100">
                                                <button class="btn btn-outline-secondary"
                                                    onclick="editProduct({{ $product->id }})" title="Editar">
                                                    <i class="mdi mdi-pencil"></i>
                                                </button>
                                                <button class="btn btn-outline-secondary"
                                                    onclick="updateStock({{ $product->id }})" title="Atualizar Estoque">
                                                    <i class="mdi mdi-package"></i>
                                                </button>
                                                <button class="btn btn-outline-danger"
                                                    onclick="deleteProduct({{ $product->id }})" title="Excluir">
                                                    <i class="mdi mdi-delete"></i>
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-info d-flex align-items-center py-2" role="alert">
                                    <i class="mdi mdi-information-outline me-2"></i>
                                    <small>Nenhum produto encontrado. Ajuste os filtros ou adicione novos produtos.</small>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <!-- Paginação -->
                    <nav aria-label="Navegação" class="mt-3">
                        <ul class="pagination pagination-sm justify-content-center mb-0">
                            <li class="page-item {{ $products->onFirstPage() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $products->previousPageUrl() }}">
                                    <i class="mdi mdi-chevron-left"></i>
                                </a>
                            </li>
                            @for ($i = 1; $i <= $products->lastPage(); $i++)
                                <li class="page-item {{ $products->currentPage() == $i ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $products->url($i) }}">{{ $i }}</a>
                                </li>
                            @endfor
                            <li class="page-item {{ $products->hasMorePages() ? '' : 'disabled' }}">
                                <a class="page-link" href="{{ $products->nextPageUrl() }}">
                                    <i class="mdi mdi-chevron-right"></i>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Adicionar/Editar Produto -->
    <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="productForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="method" name="_method" value="POST">
                    <input type="hidden" id="product_id" name="product_id">
                    <div class="modal-header">
                        <h5 class="modal-title text-primary" id="productModalLabel">Novo Produto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label for="name">Nome do Produto</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="description">Descrição</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                        <div class="form-group mb-3">
                            <label for="price">Preço</label>
                            <input type="number" class="form-control" id="price" name="price" step="0.01"
                                min="0" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="stock_quantity">Quantidade em Estoque</label>
                            <input type="number" class="form-control" id="stock_quantity" name="stock_quantity"
                                min="0" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="category_id">Categoria</label>
                            <select class="form-control" id="category_id" name="category_id" required>
                                <option value="">Selecione uma categoria</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="image">Imagem do Produto</label>
                            <input type="file" class="form-control-file" id="image" name="image">
                            <small class="form-text text-muted">Formatos aceitos: jpg, png, jpeg. Tamanho máximo:
                                2MB.</small>
                        </div>
                        <div class="form-group mb-3">
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input type="hidden" name="is_active" value="0">
                                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active"
                                        value="1">
                                    Produto Ativo
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        window.editProduct = function(productId) {
            console.log('Fetching product data for ID:', productId);
            $.get("{{ route('products.index') }}/" + productId, function(data) {
                console.log('Product data fetched successfully:', data);
                $('#productForm').attr('action', "{{ route('products.index') }}/" + productId);
                $('#method').val('PUT');
                $('#productModalLabel').text('Editar Produto');
                $('#product_id').val(data.id);
                $('#name').val(data.name);
                $('#description').val(data.description);
                $('#price').val(data.price);
                $('#stock_quantity').val(data.stock_quantity);
                $('#category_id').val(data.category_id);
                $('#is_active').prop('checked', data.is_active);
                $('#productModal').modal('show');
            }).fail(function(xhr, status, error) {
                console.error('Error fetching product data:', error);
                console.error('Response:', xhr.responseText);
                Swal.fire(
                    'Erro!',
                    'Não foi possível buscar os dados do produto. Por favor, tente novamente.',
                    'error'
                );
            });
        };
        window.updateProduct = function(productId) {
            $.ajax({
                url: "{{ route('products.index') }}/" + productId,
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        Swal.fire(
                            'Sucesso!',
                            'Produto atualizado com sucesso.',
                            'success'
                        );
                        location.reload();
                    } else {
                        Swal.fire(
                            'Erro!',
                            response.message,
                            'error'
                        );
                    }
                }
            });
        };
        window.updateStock = function(productId) {
            Swal.fire({
                title: 'Atualizar Estoque',
                input: 'number',
                inputLabel: 'Digite a nova quantidade em estoque:',
                inputValue: '',
                showCancelButton: true,
                confirmButtonText: 'Atualizar',
                cancelButtonText: 'Cancelar',
                inputValidator: (value) => {
                    if (!value || value < 0) {
                        return 'Por favor, insira um valor válido!';
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('products.index') }}/" + productId + "/stock",
                        type: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            stock_quantity: result.value
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire(
                                    'Atualizado!',
                                    'Estoque atualizado com sucesso.',
                                    'success'
                                );
                                location.reload();
                            } else {
                                Swal.fire(
                                    'Erro!',
                                    response.message,
                                    'error'
                                );
                            }
                        }
                    });
                }
            });
        };

        window.deleteProduct = function(productId) {
            Swal.fire({
                title: 'Tem certeza?',
                text: "Você não poderá reverter isso!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, excluir!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('products.index') }}/" + productId,
                        type: 'DELETE',
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire(
                                    'Excluído!',
                                    'O produto foi excluído com sucesso.',
                                    'success'
                                ).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire(
                                    'Erro!',
                                    response.message,
                                    'error'
                                );
                            }
                        }
                    });
                }
            });
        };
        $(document).ready(function() {
            // Abrir modal para novo produto
            $('button[data-bs-target="#productModal"]').click(function() {
                $('#productForm').attr('action', "{{ route('products.store') }}");
                $('#method').val('POST');
                $('#productModalLabel').text('Novo Produto');
                $('#productForm')[0].reset();
            });
            // Validar formulário
            $('#productForm').on('submit', function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.success) {
                            Swal.fire(
                                'Sucesso!',
                                'Produto salvo com sucesso.',
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Erro!',
                                response.message,
                                'error'
                            );
                        }
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON.errors;
                        let errorMessage = '';
                        for (let key in errors) {
                            errorMessage += errors[key].join(', ') + '\n';
                        }
                        Swal.fire(
                            'Erro!',
                            errorMessage,
                            'error'
                        );
                    }
                });
            });
        });
    </script>
@endpush
