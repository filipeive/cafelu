@extends('layouts.app')

@section('title', 'Editar Despesa')
@section('title-icon', 'mdi-pencil')
@section('page-title', 'Editar Despesa')

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('expenses.index') }}" class="text-decoration-none">
            <i class="mdi mdi-cash-remove"></i> Despesas
        </a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">
        <i class="mdi mdi-pencil"></i> Editar #{{ $expense->id }}
    </li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-header bg-warning text-white">
                    <h4 class="mb-0">
                        <i class="mdi mdi-pencil me-2"></i>
                        Editar Despesa #{{ $expense->id }}
                    </h4>
                    <p class="mb-0 mt-2 opacity-75">Modifique os dados da despesa conforme necessário</p>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('expenses.update', $expense) }}" id="edit-expense-form" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="mdi mdi-tag text-primary me-1"></i>
                                    Categoria *
                                </label>
                                <select class="form-select @error('expense_category_id') is-invalid @enderror"
                                    name="expense_category_id" required>
                                    <option value="">Selecione uma categoria</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('expense_category_id', $expense->expense_category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('expense_category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="mdi mdi-calendar text-success me-1"></i>
                                    Data da Despesa *
                                </label>
                                <input type="date" class="form-control @error('expense_date') is-invalid @enderror"
                                    name="expense_date"
                                    value="{{ old('expense_date', $expense->expense_date->format('Y-m-d')) }}" required>
                                @error('expense_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="mdi mdi-text-box text-info me-1"></i>
                                Descrição *
                            </label>
                            <input type="text" class="form-control @error('description') is-invalid @enderror"
                                name="description" value="{{ old('description', $expense->description) }}"
                                placeholder="Descrição detalhada da despesa..." required>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="mdi mdi-cash text-danger me-1"></i>
                                    Valor (MT) *
                                </label>
                                <input type="number" class="form-control @error('amount') is-invalid @enderror"
                                    name="amount" value="{{ old('amount', $expense->amount) }}" step="0.01"
                                    min="0" placeholder="0.00" required>
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="mdi mdi-receipt text-secondary me-1"></i>
                                    Número do Recibo
                                </label>
                                <input type="text" class="form-control @error('receipt_number') is-invalid @enderror"
                                    name="receipt_number" value="{{ old('receipt_number', $expense->receipt_number) }}"
                                    placeholder="Ex: REC001">
                                @error('receipt_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <i class="mdi mdi-paperclip me-1"></i>
                                Comprovativo/Recibo
                            </label>

                            @if ($expense->hasReceipt() && !old('remove_receipt'))
                                <div class="card mb-3" id="current-receipt">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center">
                                                <i class="mdi {{ $expense->receipt_icon }} text-primary fa-2x me-3"></i>
                                                <div>
                                                    <strong class="d-block">{{ $expense->receipt_original_name }}</strong>
                                                    <small
                                                        class="text-muted">{{ $expense->receipt_file_size_formatted }}</small>
                                                </div>
                                            </div>
                                            <div class="btn-group">
                                                <a href="{{ route('expenses.download-receipt', $expense) }}"
                                                    class="btn btn-sm btn-outline-primary" target="_blank">
                                                    <i class="mdi mdi-download me-1"></i>Download
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-danger"
                                                    onclick="removeCurrentReceipt()">
                                                    <i class="mdi mdi-delete me-1"></i>Remover
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="remove_receipt" id="remove_receipt" value="0">
                            @elseif(old('remove_receipt'))
                                <div class="alert alert-info">
                                    <i class="mdi mdi-information me-2"></i>
                                    Comprovativo será removido ao salvar.
                                </div>
                                <input type="hidden" name="remove_receipt" id="remove_receipt" value="1">
                            @endif

                            <div class="{{ $expense->hasReceipt() && !old('remove_receipt') ? 'mt-3' : '' }}">
                                <input type="file" class="form-control" name="receipt_file" id="receipt_file"
                                    accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.xls,.xlsx">
                                <div class="form-text">
                                    @if ($expense->hasReceipt() && !old('remove_receipt'))
                                        Selecione um novo arquivo para substituir o atual.
                                    @else
                                        Adicione um comprovativo (opcional).
                                    @endif
                                    Formatos: JPG, PNG, PDF, DOC, XLS (Max: 10MB)
                                </div>
                            </div>

                            @error('receipt_file')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <i class="mdi mdi-note-text text-warning me-1"></i>
                                Observações
                            </label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" name="notes" rows="4" maxlength="500"
                                placeholder="Observações adicionais sobre a despesa...">{{ old('notes', $expense->notes) }}</textarea>
                            <div class="form-text">Máximo de 500 caracteres</div>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-3 justify-content-end">
                            <a href="{{ route('expenses.index') }}" class="btn btn-secondary">
                                <i class="mdi mdi-close me-2"></i>
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="mdi mdi-content-save me-2"></i>
                                Atualizar Despesa
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            border-bottom: none;
        }

        .form-control,
        .form-select {
            border-radius: 10px;
            border: 2px solid #e5e7eb;
            transition: all 0.3s ease;
            padding: 0.75rem 1rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #f59e0b;
            box-shadow: 0 0 0 0.25rem rgba(245, 158, 11, 0.15);
        }

        .btn {
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Confirmação antes de enviar o formulário
            document.getElementById('edit-expense-form').addEventListener('submit', function(e) {
                const btn = this.querySelector('button[type="submit"]');
                btn.innerHTML = '<i class="mdi mdi-loading mdi-spin me-2"></i>Salvando...';
                btn.disabled = true;
            });

            // Auto-focus no primeiro campo
            document.querySelector('select[name="expense_category_id"]').focus();
        });

        function removeCurrentReceipt() {
            document.getElementById('remove_receipt').value = '1';
            document.getElementById('current-receipt').style.display = 'none';
            document.getElementById('receipt_file').closest('.mt-3').classList.remove('mt-3');

            // Mostrar mensagem informativa
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-info';
            alertDiv.innerHTML = '<i class="mdi mdi-information me-2"></i>Comprovativo será removido ao salvar.';
            document.querySelector('[for="receipt_file"]').parentNode.insertBefore(alertDiv, document.querySelector(
                '[for="receipt_file"]'));
        }

        // Mostrar preview do arquivo selecionado
        document.getElementById('receipt_file').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Se um arquivo for selecionado, cancelar a remoção se estiver marcada
                if (document.getElementById('remove_receipt').value === '1') {
                    document.getElementById('remove_receipt').value = '0';
                    // Remover mensagem de remoção se existir
                    const alertDiv = document.querySelector('.alert.alert-info');
                    if (alertDiv) alertDiv.remove();
                }
            }
        });
    </script>
@endpush
