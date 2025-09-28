@extends('layouts.app')

@section('page-title', 'Categorias')
@section('title-icon', 'mdi-format-list-bulleted')

@section('breadcrumbs')
    <li class="breadcrumb-item active d-flex align-items-center">
        <i class="mdi mdi-format-list-bulleted me-1"></i> Categorias
    </li>
@endsection

@push('styles')
<style>
.category-card {
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: none;
    padding: 20px;
    background: #fff;
    height: 100%;
}
.category-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
}
.category-header {
    padding: 1.5rem;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}
.category-title {
    margin: 0;
    font-size: 1.5rem;
    font-weight: 600;
}
.category-actions {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}
.category-table {
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    background: #fff;
}
.category-table .table {
    margin-bottom: 0;
}
.category-table .table thead th {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.8rem;
    letter-spacing: 0.5px;
    opacity: 0.8;
    border-bottom: 2px solid rgba(0, 0, 0, 0.05);
    padding: 1rem 1.5rem;
}
.category-table .table td {
    vertical-align: middle;
    padding: 1.25rem 1.5rem;
}
.product-badge {
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-weight: 500;
    font-size: 0.85rem;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    min-width: 100px;
    justify-content: center;
}
.action-btn {
    width: 36px;
    height: 36px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    transition: all 0.2s ease;
    font-size: 0.9rem;
}
.action-btn:hover {
    transform: translateY(-2px);
}
.category-icon {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    margin-right: 1rem;
    background: #f8fafc;
}
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    color: #6b7280;
}
.empty-state i {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}
.empty-state h4 {
    font-size: 1.25rem;
    margin-bottom: 0.5rem;
    color: #374151;
}
.empty-state p {
    margin-bottom: 2rem;
    color: #6b7280;
}
.modal-content {
    border-radius: 16px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
}
.modal-header {
    border-bottom: none;
    padding: 1.5rem 1.5rem 1rem;
}
.modal-title {
    font-weight: 600;
    font-size: 1.25rem;
}
.modal-body {
    padding: 1.5rem;
}
.modal-footer {
    border-top: none;
    padding: 1rem 1.5rem 1.5rem;
}
@media (max-width: 768px) {
    .category-header { padding: 1rem; }
    .category-table .table td,
    .category-table .table th { padding: 1rem; }
    .action-btn { width: 32px; height: 32px; font-size: 0.8rem; }
}
</style>
@endpush

@section('content')
<div class="row g-4">
    <!-- Header Section -->
    <div class="col-12">
        <div class="card category-card mb-4">
            <div class="category-header">
                <div>
                    <h2 class="category-title">
                        <i class="mdi mdi-format-list-bulleted me-2"></i>
                        Categorias
                    </h2>
                    <p class="text-muted mb-0">Gerencie as categorias do seu cardápio</p>
                </div>
                <button type="button" 
                        class="btn btn-primary d-flex align-items-center"
                        data-bs-toggle="modal" 
                        data-bs-target="#createCategoryModal">
                    <i class="mdi mdi-plus me-2"></i>
                    <span>Nova Categoria</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Categories Table -->
    <div class="col-12">
        <div class="category-table animate-in">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Categoria</th>
                                <th>Produtos</th>
                                <th>Criada em</th>
                                <th class="text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $category)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="category-icon text-primary">
                                            <i class="mdi mdi-folder-outline"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $category->name }}</div>
                                            <small class="text-muted">ID: {{ $category->id }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="product-badge bg-info bg-opacity-10 text-info">
                                        <i class="mdi mdi-food-variant me-1"></i>
                                        {{ $category->products->count() }} produtos
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-medium">{{ \Carbon\Carbon::parse($category->created_at)->format('d/m/Y') }}</div>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($category->created_at)->format('H:i') }}</small>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('categories.show', $category) }}" class="action-btn btn btn-outline-primary" title="Ver detalhes">
                                            <i class="mdi mdi-eye"></i>
                                        </a>
                                        <button type="button" 
                                                class="action-btn btn btn-outline-warning"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editCategoryModal{{ $category->id }}"
                                                title="Editar">
                                            <i class="mdi mdi-pencil"></i>
                                        </button>
                                        <button type="button" 
                                                class="action-btn btn btn-outline-danger"
                                                onclick="confirmDelete({{ $category->id }})"
                                                title="Excluir">
                                            <i class="mdi mdi-delete"></i>
                                        </button>
                                    </div>
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
                                                <h5 class="modal-title">
                                                    <i class="mdi mdi-pencil me-2 text-warning"></i>
                                                    Editar Categoria
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Nome da Categoria</label>
                                                    <input type="text" 
                                                           name="name" 
                                                           class="form-control" 
                                                           value="{{ $category->name }}" 
                                                           required
                                                           placeholder="Ex: Bebidas, Entradas, Pratos Principais">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                                    <i class="mdi mdi-close me-2"></i> Cancelar
                                                </button>
                                                <button type="submit" class="btn btn-warning">
                                                    <i class="mdi mdi-content-save me-2"></i> Salvar Alterações
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <tr>
                                <td colspan="4">
                                    <div class="empty-state">
                                        <i class="mdi mdi-format-list-bulleted text-muted"></i>
                                        <h4>Nenhuma categoria encontrada</h4>
                                        <p>Comece criando sua primeira categoria de produtos.</p>
                                        <button type="button" 
                                                class="btn btn-primary"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#createCategoryModal">
                                            <i class="mdi mdi-plus me-2"></i> Criar Primeira Categoria
                                        </button>
                                    </div>
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

<!-- Modal de Criação -->
<div class="modal fade" id="createCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="mdi mdi-plus-circle me-2 text-primary"></i>
                        Nova Categoria
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nome da Categoria</label>
                        <input type="text" 
                               name="name" 
                               class="form-control" 
                               required
                               placeholder="Ex: Bebidas, Entradas, Pratos Principais"
                               autofocus>
                        <div class="form-text">Dê um nome descritivo para facilitar a organização do cardápio.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="mdi mdi-close me-2"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="mdi mdi-plus me-2"></i> Criar Categoria
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Formulário de Exclusão (Hidden) -->
@forelse($categories as $category)
<form id="deleteForm{{ $category->id }}" 
      action="{{ route('categories.destroy', $category) }}" 
      method="POST" 
      class="d-none">
    @csrf
    @method('DELETE')
</form>
@empty
@endforelse
@endsection

@push('scripts')
<script>
function confirmDelete(categoryId) {
    Swal.fire({
        title: 'Tem certeza que deseja excluir esta categoria?',
        text: "Todos os produtos nesta categoria serão desassociados! Esta ação não pode ser desfeita.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '<i class="mdi mdi-delete me-1"></i> Sim, excluir!',
        cancelButtonText: '<i class="mdi mdi-close me-1"></i> Cancelar',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('deleteForm' + categoryId).submit();
        }
    });
}
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endpush