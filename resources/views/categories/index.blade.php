@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title mb-0">Categorias</h4>
                        <button type="button" 
                                class="btn btn-primary btn-icon-text" 
                                data-bs-toggle="modal" 
                                data-bs-target="#createCategoryModal">
                            <i class="mdi mdi-plus-circle-outline"></i>
                            Nova Categoria
                        </button>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Produtos</th>
                                    <th>Criada em</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $category)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="mdi mdi-folder-outline text-primary me-2"></i>
                                            {{ $category->name }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ $category->products->count() }} produtos
                                        </span>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($category->created_at)->format('d/m/Y') }}</td>
                                    <td>
                                        <button type="button" 
                                                class="btn btn-warning btn-icon btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editCategoryModal{{ $category->id }}"
                                                title="Editar">
                                            <i class="mdi mdi-pencil"></i>
                                        </button>
                                        
                                        <button type="button" 
                                                class="btn btn-danger btn-icon btn-sm"
                                                onclick="confirmDelete({{ $category->id }})"
                                                title="Excluir">
                                            <i class="mdi mdi-delete"></i>
                                        </button>

                                        <form id="deleteForm{{ $category->id }}" 
                                              action="{{ route('categories.destroy', $category) }}" 
                                              method="POST" 
                                              class="d-none">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>

                                <!-- Modal de Edição -->
                                <div class="modal fade" id="editCategoryModal{{ $category->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('categories.update', $category) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Editar Categoria</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label class="form-label">Nome da Categoria</label>
                                                        <input type="text" 
                                                               name="name" 
                                                               class="form-control" 
                                                               value="{{ $category->name }}" 
                                                               required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        <i class="mdi mdi-information-outline mb-2" style="font-size: 2rem;"></i>
                                        <p class="mb-0">Nenhuma categoria cadastrada</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Criação -->
<div class="modal fade" id="createCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Nova Categoria</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Nome da Categoria</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Criar Categoria</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmDelete(categoryId) {
    Swal.fire({
        title: 'Tem certeza?',
        text: "Esta ação não pode ser desfeita!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('deleteForm' + categoryId).submit();
        }
    });
}
</script>
@endpush

@endsection