@extends('layouts.app')

@section('title', 'Gestão de Despesas')
@section('title-icon', 'mdi-cash-remove')
@section('page-title', 'Gestão de Despesas')

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard') }}" class="text-decoration-none">
            <i class="mdi mdi-home"></i> Dashboard
        </a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">
        <i class="mdi mdi-cash-remove"></i> Despesas
    </li>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <div class="d-flex align-items-center">
                                    <div class="bg-danger bg-opacity-10 rounded-circle p-3 me-3">
                                        <i class="mdi mdi-cash-remove text-danger fs-2"></i>
                                    </div>
                                    <div>
                                        <h2 class="mb-1">Gestão de Despesas</h2>
                                        <p class="text-muted mb-0">
                                            Registre e acompanhe todas as despesas do ZALALA BEACH BAR
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 text-md-end">
                                <div class="btn-group" role="group">
                                    <button class="btn btn-success" data-bs-toggle="modal"
                                        data-bs-target="#createExpenseModal">
                                        <i class="mdi mdi-plus me-1"></i> Nova Despesa
                                    </button>
                                    <button class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#createCategoryModal">
                                        <i class="mdi mdi-folder-plus me-1"></i> Nova Categoria
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cards de Estatísticas -->
        <div class="row mb-4">
            <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
                <div class="card stats-card border-danger h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-muted mb-2 fw-semibold">Total de Despesas</h6>
                                <h3 class="mb-0 text-danger fw-bold">{{ number_format($totalExpenses, 2, ',', '.') }} MT
                                </h3>
                                <small class="text-muted">registradas no período</small>
                            </div>
                            <div class="text-danger">
                                <i class="mdi mdi-cash-remove fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
                <div class="card stats-card border-success h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-muted mb-2 fw-semibold">Média de Despesas</h6>
                                <h3 class="mb-0 text-success fw-bold">{{ number_format($averageExpense, 2, ',', '.') }} MT
                                </h3>
                                <small class="text-muted">por ocorrência</small>
                            </div>
                            <div class="text-success">
                                <i class="mdi mdi-chart-line fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
                <div class="card stats-card border-warning h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-muted mb-2 fw-semibold">Maior Despesa</h6>
                                <h3 class="mb-0 text-warning fw-bold">{{ number_format($highestExpense, 2, ',', '.') }} MT
                                </h3>
                                <small class="text-muted">única ocorrência</small>
                            </div>
                            <div class="text-warning">
                                <i class="mdi mdi-arrow-up fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
                <div class="card stats-card border-info h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-muted mb-2 fw-semibold">Menor Despesa</h6>
                                <h3 class="mb-0 text-info fw-bold">{{ number_format($lowestExpense, 2, ',', '.') }} MT</h3>
                                <small class="text-muted">única ocorrência</small>
                            </div>
                            <div class="text-info">
                                <i class="mdi mdi-arrow-down fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0 d-flex align-items-center">
                    <i class="mdi mdi-filter me-2 text-primary"></i>
                    Filtros e Pesquisa
                </h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('expenses.index') }}">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Pesquisar Descrição</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="search" value="{{ request('search') }}"
                                    placeholder="Descrição da despesa...">
                                <button class="btn btn-outline-secondary" type="button" onclick="clearSearch()">
                                    <i class="mdi mdi-close"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Data Inicial</label>
                            <input type="date" class="form-control" name="date_from" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Data Final</label>
                            <input type="date" class="form-control" name="date_to" value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="mdi mdi-magnify me-1"></i> Filtrar
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Lista de Despesas -->
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 d-flex align-items-center">
                        <i class="mdi mdi-format-list-bulleted me-2 text-primary"></i>
                        Despesas Registradas
                    </h5>
                    <span class="badge bg-primary">{{ $expenses->total() }} registros</span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Data</th>
                                <th>Categoria</th>
                                <th>Descrição</th>
                                <th>Valor</th>
                                <th>Comprovativo</th>
                                <th>Usuário</th>
                                <th class="text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($expenses as $expense)
                                <tr>
                                    <td><strong class="text-danger">#{{ $expense->id }}</strong></td>
                                    <td><strong>{{ $expense->expense_date->format('d/m/Y') }}</strong></td>
                                    <td>
                                        <span class="badge bg-light text-dark">
                                            {{ $expense->category?->name ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td>{{ Str::limit($expense->description, 50) }}</td>
                                    <td>
                                        <strong class="text-danger">
                                            {{ number_format($expense->amount, 2, ',', '.') }} MT
                                        </strong>
                                    </td>
                                    <td>
                                        @if ($expense->hasReceipt())
                                            <span class="badge bg-success" data-bs-toggle="tooltip"
                                                title="{{ $expense->receipt_original_name }}">
                                                <i class="mdi {{ $expense->receipt_icon }} me-1"></i>
                                                Comprovativo
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">Sem comprovativo</span>
                                        @endif
                                    </td>
                                    <td><small>{{ $expense->user?->name ?? 'N/A' }}</small></td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-info view-btn" data-bs-toggle="tooltip"
                                                title="Ver Detalhes" data-expense-id="{{ $expense->id }}">
                                                <i class="mdi mdi-eye"></i>
                                            </button>
                                            <a href="{{ route('expenses.edit', $expense) }}"
                                                class="btn btn-outline-warning" data-bs-toggle="tooltip" title="Editar">
                                                <i class="mdi mdi-pencil"></i>
                                            </a>
                                            <button type="button" class="btn btn-outline-danger delete-btn"
                                                data-bs-toggle="tooltip" title="Excluir"
                                                data-expense-id="{{ $expense->id }}"
                                                data-expense-description="{{ $expense->description }}">
                                                <i class="mdi mdi-delete"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="d-flex flex-column align-items-center text-muted">
                                            <i class="mdi mdi-cash-remove fa-3x mb-3 opacity-50"></i>
                                            <h5>Nenhuma despesa encontrada</h5>
                                            <p class="mb-3">Registre sua primeira despesa ou ajuste os filtros.</p>
                                            <button class="btn btn-success" data-bs-toggle="modal"
                                                data-bs-target="#createExpenseModal">
                                                <i class="mdi mdi-plus me-2"></i>Adicionar Despesa
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($expenses->hasPages())
                    <div class="card-footer bg-light d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            Mostrando {{ $expenses->firstItem() ?? 0 }} a {{ $expenses->lastItem() ?? 0 }}
                            de {{ $expenses->total() }}
                        </small>
                        {{ $expenses->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal para Criar Categoria -->
    <div class="modal fade" id="createCategoryModal" tabindex="-1" aria-labelledby="createCategoryModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="category-form" method="POST" action="{{ route('expense-categories.store') }}">
                    @csrf
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="createCategoryModalLabel">
                            <i class="mdi mdi-folder-plus me-2"></i> Nova Categoria
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nome da Categoria *</label>
                            <input type="text" class="form-control" name="name" required>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="mdi mdi-close me-2"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-content-save me-2"></i> Salvar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para Criar Despesa -->
    <div class="modal fade" id="createExpenseModal" tabindex="-1" aria-labelledby="createExpenseModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form id="expense-form" method="POST" action="{{ route('expenses.store') }}">
                    @csrf
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="createExpenseModalLabel">
                            <i class="mdi mdi-plus-circle me-2"></i> Nova Despesa
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Categoria *</label>
                                <select class="form-select" name="expense_category_id" required>
                                    <option value="">Selecione uma categoria</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Data *</label>
                                <input type="date" class="form-control" name="expense_date" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Descrição *</label>
                            <input type="text" class="form-control" name="description" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Valor (MT) *</label>
                                <input type="number" class="form-control" name="amount" step="0.01" min="0"
                                    required>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Número do Recibo</label>
                                <input type="text" class="form-control" name="receipt_number">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Observações</label>
                            <textarea class="form-control" name="notes" rows="3" maxlength="500"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="mdi mdi-paperclip me-1"></i>
                                Comprovativo/Recibo
                            </label>
                            <input type="file" class="form-control" name="receipt_file"
                                accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.xls,.xlsx">
                            <div class="form-text">
                                Formatos aceites: JPG, PNG, PDF, DOC, XLS (Max: 10MB)
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="mdi mdi-close me-2"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="mdi mdi-content-save me-2"></i> Salvar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para Detalhes da Despesa -->
    <div class="modal fade" id="expenseDetailsModal" tabindex="-1" aria-labelledby="expenseDetailsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="expenseDetailsModalLabel">
                        <i class="mdi mdi-eye me-2"></i> Detalhes da Despesa #<span id="detail-expense-id"></span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="expense-details-content">
                    <div class="text-center py-5">
                        <div class="loading-spinner mb-3"></div>
                        <p class="text-muted">Carregando detalhes...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="mdi mdi-close me-2"></i> Fechar
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .stats-card {
            transition: all 0.3s ease;
            border-left: 4px solid;
            padding: 0.8rem 1rem;
            /* Compacta */
            min-height: 100px;
            /* Mantém altura mínima */
        }

        .stats-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.12);
        }

        .stat-label {
            font-size: 0.85rem;
            /* Menor */
            margin-bottom: 0.2rem;
        }

        .stat-value {
            font-size: 1.4rem;
            /* Menor que antes */
            font-weight: bold;
        }

        .stats-card small {
            font-size: 0.75rem;
        }

        .stats-card i {
            font-size: 1.8rem;
            /* Ícones menores */
        }

        .loading-spinner {
            width: 40px;
            height: 40px;
            border: 3px solid #f3f4f6;
            border-top: 3px solid #ef4444;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .btn-group .btn {
            transition: all 0.3s ease;
        }

        .btn-group .btn:hover {
            transform: translateY(-2px);
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Auto-complete data atual no modal de despesa
            const expenseModal = document.getElementById('createExpenseModal');
            if (expenseModal) {
                expenseModal.addEventListener('show.bs.modal', function() {
                    const today = new Date().toISOString().split('T')[0];
                    this.querySelector('input[name="expense_date"]').value = today;
                    clearValidation();
                });
            }

            // Limpar validação
            function clearValidation() {
                document.querySelectorAll('.form-control, .form-select').forEach(el => {
                    el.classList.remove('is-invalid');
                });
                document.querySelectorAll('.invalid-feedback').forEach(el => {
                    el.textContent = '';
                });
            }

            // Mostrar erro de campo
            function showFieldError(selector, message) {
                const el = document.querySelector(selector);
                if (el) {
                    el.classList.add('is-invalid');
                    const feedback = el.nextElementSibling;
                    if (feedback && feedback.classList.contains('invalid-feedback')) {
                        feedback.textContent = message;
                    }
                }
            }

            // Limpar pesquisa
            function clearSearch() {
                document.querySelector('input[name="search"]').value = '';
                document.querySelector('form').submit();
            }

            // Submit do formulário de categoria
            document.getElementById('category-form').addEventListener('submit', function(e) {
                e.preventDefault();
                clearValidation();

                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="mdi mdi-loading mdi-spin me-2"></i>Salvando...';

                const formData = new FormData(this);

                fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const modal = bootstrap.Modal.getInstance(document.getElementById(
                                'createCategoryModal'));
                            modal.hide();
                            showToast('Categoria criada com sucesso!', 'success');
                            setTimeout(() => window.location.reload(), 1500);
                        } else {
                            if (data.errors) {
                                Object.keys(data.errors).forEach(field => {
                                    showFieldError(`input[name="${field}"]`, data.errors[field][
                                        0
                                    ]);
                                });
                            }
                            showToast(data.message || 'Erro ao salvar categoria.', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Erro:', error);
                        showToast('Erro de conexão.', 'error');
                    })
                    .finally(() => {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    });
            });

            // Submit do formulário de despesa
            document.getElementById('expense-form').addEventListener('submit', function(e) {
                e.preventDefault();
                clearValidation();

                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="mdi mdi-loading mdi-spin me-2"></i>Salvando...';

                const formData = new FormData(this);

                fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const modal = bootstrap.Modal.getInstance(document.getElementById(
                                'createExpenseModal'));
                            modal.hide();
                            showToast('Despesa criada com sucesso!', 'success');
                            setTimeout(() => window.location.reload(), 1500);
                        } else {
                            if (data.errors) {
                                Object.keys(data.errors).forEach(field => {
                                    const selector = field === 'expense_category_id' ?
                                        'select[name="expense_category_id"]' :
                                        `input[name="${field}"], textarea[name="${field}"]`;
                                    showFieldError(selector, data.errors[field][0]);
                                });
                            }
                            showToast(data.message || 'Erro ao salvar despesa.', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Erro:', error);
                        showToast('Erro de conexão.', 'error');
                    })
                    .finally(() => {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    });
            });

            // Event listener para ver detalhes
            document.querySelectorAll('.view-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const expenseId = this.getAttribute('data-expense-id');

                    const modal = new bootstrap.Modal(document.getElementById(
                        'expenseDetailsModal'));
                    const content = document.getElementById('expense-details-content');
                    const expenseIdSpan = document.getElementById('detail-expense-id');

                    expenseIdSpan.textContent = expenseId;

                    // Placeholder de carregamento
                    content.innerHTML = `
                        <div class="text-center py-5">
                            <div class="loading-spinner mb-3"></div>
                            <p class="text-muted">Carregando detalhes...</p>
                        </div>
                    `;

                    modal.show();

                    // Carregar dados via AJAX
                    fetch(`/expenses/${expenseId}/details`, {
                            method: 'GET',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => {
                            if (!response.ok) throw new Error(
                                'Erro ao carregar dados');
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                const expense = data.data;

                                // Comprovativo / recibo
                                let receiptHtml = '';
                                if (expense.has_receipt && expense.receipt_url) {
                                    receiptHtml = `
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h6 class="card-title text-primary">
                                        <i class="mdi mdi-paperclip me-2"></i>
                                        Comprovativo/Recibo
                                    </h6>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <i class="mdi ${expense.receipt_icon} text-primary fa-2x me-3"></i>
                                            <div>
                                                <strong class="d-block">${expense.receipt_original_name}</strong>
                                                <small class="text-muted">${expense.receipt_file_size}</small>
                                            </div>
                                        </div>
                                        <div class="btn-group">
                                            <a href="${expense.receipt_url}" 
                                               class="btn btn-sm btn-primary" target="_blank">
                                                <i class="mdi mdi-download me-1"></i>Download
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                                } else {
                                    receiptHtml = `
                            <div class="alert alert-warning">
                                <i class="mdi mdi-alert-circle me-2"></i>
                                Nenhum comprovativo anexado
                            </div>
                        `;
                                }

                                // Conteúdo do modal
                                content.innerHTML = `
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h6 class="card-title text-primary">Informações Básicas</h6>
                                        <table class="table table-borderless">
                                            <tr>
                                                <td><strong>Descrição:</strong></td>
                                                <td>${expense.description}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Categoria:</strong></td>
                                                <td><span class="badge bg-light text-dark">${expense.category}</span></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Valor:</strong></td>
                                                <td><span class="text-danger fw-bold">${expense.formatted_amount}</span></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h6 class="card-title text-primary">Detalhes Adicionais</h6>
                                        <table class="table table-borderless">
                                            <tr>
                                                <td><strong>Data:</strong></td>
                                                <td>${expense.expense_date}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Recibo:</strong></td>
                                                <td>${expense.receipt_number}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Usuário:</strong></td>
                                                <td>${expense.user}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        ${receiptHtml}

                        <div class="card mb-3">
                            <div class="card-body">
                                <h6 class="card-title text-primary">Observações</h6>
                                <p class="text-muted">${expense.notes}</p>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title text-primary">Histórico</h6>
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Criado em:</strong></td>
                                        <td>${expense.created_at}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Atualizado em:</strong></td>
                                        <td>${expense.updated_at}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    `;
                            } else {
                                content.innerHTML = `
                        <div class="alert alert-danger">
                            <i class="mdi mdi-alert-circle me-2"></i>
                            ${data.message || 'Erro ao carregar detalhes'}
                        </div>
                    `;
                            }
                        })
                        .catch(error => {
                            console.error('Erro:', error);
                            content.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="mdi mdi-alert-circle me-2"></i>
                        Erro ao carregar detalhes da despesa: ${error.message}
                    </div>
                `;
                        });
                });
            });

            // Event listener para deletar despesa
            document.querySelectorAll('.delete-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const expenseId = this.getAttribute('data-expense-id');
                    const expenseDescription = this.getAttribute('data-expense-description');

                    if (confirm(
                            `Tem certeza que deseja excluir a despesa "${expenseDescription}"?\n\nEsta ação não pode ser desfeita.`
                        )) {
                        const button = this;
                        const originalText = button.innerHTML;
                        button.disabled = true;
                        button.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i>';

                        fetch(`/expenses/${expenseId}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]').content,
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    showToast('Despesa excluída com sucesso!', 'success');
                                    setTimeout(() => window.location.reload(), 1500);
                                } else {
                                    showToast(data.message || 'Erro ao excluir despesa.',
                                        'error');
                                }
                            })
                            .catch(error => {
                                console.error('Erro:', error);
                                showToast('Erro de conexão.', 'error');
                            })
                            .finally(() => {
                                button.disabled = false;
                                button.innerHTML = originalText;
                            });
                    }
                });
            });

            // Função para exibir toast
            function showToast(message, type = 'info') {
                const bg = type === 'success' ? 'bg-success' :
                    type === 'error' ? 'bg-danger' :
                    type === 'warning' ? 'bg-warning' : 'bg-primary';

                const toastEl = document.createElement('div');
                toastEl.className = `toast align-items-center text-white ${bg} border-0`;
                toastEl.setAttribute('role', 'alert');
                toastEl.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">${message}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            `;

                // Criar container se não existir
                let container = document.getElementById('toastContainer');
                if (!container) {
                    container = document.createElement('div');
                    container.id = 'toastContainer';
                    container.className = 'toast-container position-fixed top-0 end-0 p-3';
                    container.style.zIndex = '9999';
                    document.body.appendChild(container);
                }

                container.appendChild(toastEl);
                const toast = new bootstrap.Toast(toastEl, {
                    delay: 5000
                });
                toast.show();

                toastEl.addEventListener('hidden.bs.toast', () => {
                    toastEl.remove();
                });
            }
        });
    </script>
@endpush
