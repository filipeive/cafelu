@extends('layouts.app')

@section('title', 'Produtos')

@section('content')
    <div class="content-wrapper">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title">Lista de Produtos</h4>
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#productModal">
                            <i class="mdi mdi-plus"></i> Novo Produto
                        </button>
                    </div>

                    <!-- Filtros -->
                    <form method="GET" action="{{ route('products.index') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="search" placeholder="Pesquisar..."
                                        value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <select class="form-control" name="category">
                                        <option value="">Todas Categorias</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ request('category') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <select class="form-control" name="status">
                                        <option value="">Todos Status</option>
                                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Ativo
                                        </option>
                                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inativo
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary btn-block">Filtrar</button>
                            </div>
                        </div>
                    </form>
                    {{-- alerts --}}
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    {{-- alert errors --}}
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    <!-- Tabela de Produtos -->
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Imagem</th>
                                    <th>Nome</th>
                                    <th>Categoria</th>
                                    <th>Preço</th>
                                    <th>Estoque</th>
                                    <th>Status</th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($products as $product)
                                    <tr>
                                        <td>
                                            @if ($product->image_path)
                                                <img src="{{ asset('storage/' . $product->image_path) }}"
                                                    alt="{{ $product->name }}" class="img-xs rounded-circle border">
                                            @else
                                                <div
                                                    class="img-xs rounded-circle bg-light d-flex align-items-center justify-content-center border">
                                                    <i class="mdi mdi-image-off text-muted"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->category->name ?? '-' }}</td>
                                        <td>MZN {{ number_format($product->price, 2, ',', '.') }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <input type="number" class="form-control form-control-sm stock-update" 
                                                       data-id="{{ $product->id }}" 
                                                       value="{{ $product->stock_quantity }}" 
                                                       min="0" style="width: 70px;">
                                                <span
                                                    id="stock-badge-{{ $product->id }}"
                                                    class="badge badge-pill ml-2 badge-{{ $product->stock_quantity > 10 ? 'success' : ($product->stock_quantity > 5 ? 'warning' : 'danger') }}">
                                                    {{ $product->stock_quantity }}
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-pill badge-{{ $product->is_active ? 'success' : 'danger' }}">
                                                {{ $product->is_active ? 'Ativo' : 'Inativo' }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-outline-primary btn-sm btn-edit"
                                                data-id="{{ $product->id }}" data-toggle="tooltip" title="Editar">
                                                <i class="mdi mdi-pencil"></i>
                                            </button>
                                            <button class="btn btn-outline-danger btn-sm btn-delete"
                                                data-id="{{ $product->id }}" data-toggle="tooltip" title="Excluir">
                                                <i class="mdi mdi-delete"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">Nenhum produto encontrado.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginação -->
                    <div class="mt-4">
                        {{ $products->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Adicionar/Editar Produto -->
    <div class="modal fade" id="productModal" tabindex="-1" role="dialog" aria-labelledby="productModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="productForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="method" name="_method" value="POST">
                    <input type="hidden" id="product_id" name="product_id">

                    <div class="modal-header">
                        <h5 class="modal-title" id="productModalLabel">Novo Produto</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Nome do Produto</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Descrição</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="price">Preço</label>
                            <input type="number" class="form-control" id="price" name="price" step="0.01"
                                min="0" required>
                        </div>
                        <div class="form-group">
                            <label for="stock_quantity">Quantidade em Estoque</label>
                            <input type="number" class="form-control" id="stock_quantity" name="stock_quantity"
                                min="0" required>
                        </div>
                        <div class="form-group">
                            <label for="category_id">Categoria</label>
                            <select class="form-control" id="category_id" name="category_id" required>
                                <option value="">Selecione uma categoria</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="image">Imagem do Produto</label>
                            <input type="file" class="form-control-file" id="image" name="image">
                            <small class="form-text text-muted">Formatos aceitos: jpg, png, jpeg. Tamanho máximo:
                                2MB.</small>
                        </div>
                        <div class="form-group">
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" 
                                           {{ isset($product) && $product->is_active ? 'checked' : (old('is_active') ? 'checked' : '') }}>
                                    Produto Ativo
                                </label>
                            </div>
                        </div>
                        <div id="imagePreview" class="text-center mb-3 d-none">
                            <img id="previewImage" src="#" alt="Preview" class="img-fluid rounded"
                                style="max-height: 150px;">
                            <div class="mt-2">
                                <button type="button" class="btn btn-danger btn-sm" id="removeImage">
                                    <i class="mdi mdi-delete"></i> Remover Imagem
                                </button>
                            </div>
                            <input type="hidden" id="remove_image" name="remove_image" value="0">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Tooltips
            $('[data-toggle="tooltip"]').tooltip();

            // Preview da imagem antes do upload
            $('#image').change(function() {
                if (this.files && this.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#previewImage').attr('src', e.target.result);
                        $('#imagePreview').removeClass('d-none');
                    }
                    reader.readAsDataURL(this.files[0]);
                }
            });

            // Remover imagem
            $('#removeImage').click(function() {
                $('#previewImage').attr('src', '#');
                $('#imagePreview').addClass('d-none');
                $('#remove_image').val('1');
                $('#image').val('');
            });

            // Abrir modal para novo produto
            $('button[data-target="#productModal"]').click(function() {
                $('#productForm').attr('action', "{{ route('products.store') }}");
                $('#method').val('POST');
                $('#productModalLabel').text('Novo Produto');
                $('#productForm')[0].reset();
                $('#imagePreview').addClass('d-none');
                $('#remove_image').val('0');
            });

            // Abrir modal para editar produto
            $('.btn-edit').click(function() {
                var productId = $(this).data('id');

                $.get("{{ route('products.index') }}/" + productId, function(data) {
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

                    if (data.image_path) {
                        $('#previewImage').attr('src', "{{ asset('storage') }}/" + data
                        .image_path);
                        $('#imagePreview').removeClass('d-none');
                    } else {
                        $('#imagePreview').addClass('d-none');
                    }

                    $('#productModal').modal('show');
                });
            });

            // Excluir produto
            $('.btn-delete').click(function() {
                var productId = $(this).data('id');

                if (confirm('Tem certeza que deseja excluir este produto?')) {
                    $.ajax({
                        url: "{{ route('products.index') }}/" + productId,
                        type: 'DELETE',
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            if (response.success) {
                                location.reload();
                            } else {
                                alert(response.message);
                            }
                        }
                    });
                }
            });

            // Atualizar estoque via AJAX
            $('.stock-update').change(function() {
                var productId = $(this).data('id');
                var newStock = $(this).val();

                $.ajax({
                    url: "{{ route('products.index') }}/" + productId + "/stock",
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        stock_quantity: newStock
                    },
                    success: function(response) {
                        if (response.success) {
                            // Atualizar badge de status
                            var badgeClass = newStock > 10 ? 'badge-success' : (newStock > 5 ?
                                'badge-warning' : 'badge-danger');
                            $('#stock-badge-' + productId).removeClass(
                                'badge-success badge-warning badge-danger').addClass(
                                badgeClass);
                        } else {
                            alert(response.message);
                        }
                    }
                });
            });
        });
    </script>
@endpush
