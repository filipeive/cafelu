@extends('layouts.app')

@section('title', 'Editar Movimento de Estoque')
@section('page-title', 'Editar Movimento de Estoque')
@section('title-icon', 'mdi-clipboard-edit')

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('stock-movements.index') }}">Movimentos de Estoque</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('stock-movements.show', $stockMovement) }}">Movimento #{{ $stockMovement->id }}</a>
    </li>
    <li class="breadcrumb-item active">Editar</li>
@endsection

@push('styles')
<style>
    .form-section {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        padding: 2rem;
        margin-bottom: 1.5rem;
    }
    .form-section h5 {
        color: #0891b2;
        margin-bottom: 1.2rem;
        font-weight: 600;
        border-bottom: 2px solid #fbbf24;
        padding-bottom: 0.5rem;
    }
    .required-field::after {
        content: " *";
        color: #ef4444;
        font-weight: bold;
    }
    .movement-type-card {
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        padding: 1.2rem;
        cursor: pointer;
        transition: box-shadow 0.2s, transform 0.2s;
        text-align: center;
        background: #f8fafc;
    }
    .movement-type-card.selected {
        border-color: #0891b2;
        background: rgba(8,145,178,0.08);
        box-shadow: 0 2px 8px #0891b233;
        transform: translateY(-2px);
    }
    .movement-type-card .type-icon {
        font-size: 2rem;
        margin-bottom: 0.75rem;
    }
    .movement-type-card.in .type-icon { color: #10b981; }
    .movement-type-card.out .type-icon { color: #ef4444; }
    .movement-type-card.adjustment .type-icon { color: #f59e0b; }
    .warning-alert {
        background: rgba(255,193,7,0.08);
        border: 1px solid #fbbf24;
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1.5rem;
    }
    .movement-preview {
        background: #f8fafc;
        border-radius: 16px;
        padding: 1.5rem;
        margin-top: 1rem;
        box-shadow: 0 2px 8px rgba(8,145,178,0.07);
    }
    .movement-icon {
        width: 64px;
        height: 64px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-size: 2rem;
        background: linear-gradient(90deg,#0891b2,#fbbf24);
        color: #fff;
    }
    @media (max-width: 768px) {
        .form-section { padding: 1rem; }
        .movement-preview { padding: 1rem; }
    }
</style>
@endpush

@section('content')
    <div class="warning-alert">
        <div class="d-flex align-items-center">
            <i class="mdi mdi-alert text-warning me-3 fs-4"></i>
            <div>
                <h6 class="mb-1 text-warning">Atenção ao Editar Movimento</h6>
                <p class="mb-0 text-muted">
                    A alteração deste movimento pode afetar os cálculos de estoque. 
                    Certifique-se de que as informações estão corretas antes de salvar.
                </p>
            </div>
        </div>
    </div>

    <form action="{{ route('stock-movements.update', $stockMovement) }}" method="POST" id="movement-form">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-lg-8">
                <!-- Produto -->
                <div class="form-section">
                    <h5><i class="mdi mdi-cube-outline me-2"></i> Produto</h5>
                    <label for="product_id" class="form-label required-field">Produto/Serviço</label>
                    <select class="form-select @error('product_id') is-invalid @enderror" 
                            id="product_id" name="product_id" required>
                        <option value="">Selecione...</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" 
                                    data-type="{{ $product->type }}"
                                    {{ (old('product_id', $stockMovement->product_id) == $product->id) ? 'selected' : '' }}>
                                {{ $product->name }} {{ $product->type === 'service' ? '(Serviço)' : '(Produto)' }}
                            </option>
                        @endforeach
                    </select>
                    @error('product_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Tipo de Movimento -->
                <div class="form-section">
                    <h5><i class="mdi mdi-swap-horizontal me-2"></i> Tipo de Movimento</h5>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="movement-type-card in {{ old('movement_type', $stockMovement->movement_type) == 'in' ? 'selected' : '' }}" data-type="in">
                                <div class="type-icon"><i class="mdi mdi-arrow-up-bold"></i></div>
                                <h6 class="fw-bold">Entrada</h6>
                                <small class="text-muted">Recebimento de produtos</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="movement-type-card out {{ old('movement_type', $stockMovement->movement_type) == 'out' ? 'selected' : '' }}" data-type="out">
                                <div class="type-icon"><i class="mdi mdi-arrow-down-bold"></i></div>
                                <h6 class="fw-bold">Saída</h6>
                                <small class="text-muted">Venda ou consumo</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="movement-type-card adjustment {{ old('movement_type', $stockMovement->movement_type) == 'adjustment' ? 'selected' : '' }}" data-type="adjustment">
                                <div class="type-icon"><i class="mdi mdi-wrench"></i></div>
                                <h6 class="fw-bold">Ajuste</h6>
                                <small class="text-muted">Correção de estoque</small>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="movement_type" id="movement_type" 
                           value="{{ old('movement_type', $stockMovement->movement_type) }}" required>
                    @error('movement_type')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Detalhes -->
                <div class="form-section">
                    <h5><i class="mdi mdi-information-outline me-2"></i> Detalhes do Movimento</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="quantity" class="form-label required-field">Quantidade</label>
                            <input type="number" class="form-control @error('quantity') is-invalid @enderror" 
                                   id="quantity" name="quantity" min="1" 
                                   value="{{ old('quantity', $stockMovement->quantity) }}" required>
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="movement_date" class="form-label required-field">Data do Movimento</label>
                            <input type="date" class="form-control @error('movement_date') is-invalid @enderror" 
                                   id="movement_date" name="movement_date" 
                                   value="{{ old('movement_date', $stockMovement->movement_date->format('Y-m-d')) }}" required>
                            @error('movement_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <label for="reason" class="form-label">Motivo/Observações</label>
                            <textarea class="form-control @error('reason') is-invalid @enderror" 
                                      id="reason" name="reason" rows="3" 
                                      placeholder="Descreva o motivo deste movimento (opcional)">{{ old('reason', $stockMovement->reason) }}</textarea>
                            @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Auditoria -->
                <div class="form-section">
                    <h5><i class="mdi mdi-history me-2"></i> Histórico</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Criado por</label>
                            <div class="form-control-plaintext">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2" 
                                         style="width: 24px; height: 24px;">
                                        <small class="text-white fw-bold">
                                            {{ substr($stockMovement->user->name ?? 'S', 0, 1) }}
                                        </small>
                                    </div>
                                    <span>{{ $stockMovement->user->name ?? 'Sistema' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Data de Criação</label>
                            <div class="form-control-plaintext">
                                {{ $stockMovement->created_at->format('d/m/Y H:i') }}
                            </div>
                        </div>
                        @if($stockMovement->updated_at != $stockMovement->created_at)
                            <div class="col-md-6">
                                <label class="form-label">Última Atualização</label>
                                <div class="form-control-plaintext">
                                    {{ $stockMovement->updated_at->format('d/m/Y H:i') }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <!-- Preview -->
                <div class="movement-preview" id="movement-preview">
                    <div class="text-center">
                        <div class="movement-icon" id="preview-icon">
                            @switch($stockMovement->movement_type)
                                @case('in') <i class="mdi mdi-arrow-up-bold"></i> @break
                                @case('out') <i class="mdi mdi-arrow-down-bold"></i> @break
                                @case('adjustment') <i class="mdi mdi-wrench"></i> @break
                                @default <i class="mdi mdi-help-circle-outline"></i>
                            @endswitch
                        </div>
                        <h6 id="preview-type">
                            @switch($stockMovement->movement_type)
                                @case('in') Entrada de Estoque @break
                                @case('out') Saída de Estoque @break
                                @case('adjustment') Ajuste de Estoque @break
                                @default Selecione o tipo de movimento
                            @endswitch
                        </h6>
                        <p class="text-muted mb-3" id="preview-description">
                            @switch($stockMovement->movement_type)
                                @case('in') Aumentará o estoque disponível @break
                                @case('out') Diminuirá o estoque disponível @break
                                @case('adjustment') Correção manual do estoque @break
                                @default Escolha um produto e tipo de movimento para ver o resumo
                            @endswitch
                        </p>
                        <div class="row g-2 text-start" id="preview-details">
                            <div class="col-6"><strong>Produto:</strong></div>
                            <div class="col-6" id="preview-product">{{ $stockMovement->product->name ?? 'Produto Removido' }}</div>
                            <div class="col-6"><strong>Tipo:</strong></div>
                            <div class="col-6" id="preview-movement-type">
                                @switch($stockMovement->movement_type)
                                    @case('in') <span class="badge bg-success">Entrada</span> @break
                                    @case('out') <span class="badge bg-danger">Saída</span> @break
                                    @case('adjustment') <span class="badge bg-warning text-dark">Ajuste</span> @break
                                @endswitch
                            </div>
                            <div class="col-6"><strong>Quantidade:</strong></div>
                            <div class="col-6" id="preview-quantity">{{ $stockMovement->quantity }}</div>
                            <div class="col-6"><strong>Data:</strong></div>
                            <div class="col-6" id="preview-date">{{ $stockMovement->movement_date->format('d/m/Y') }}</div>
                        </div>
                    </div>
                </div>
                <!-- Ações -->
                <div class="d-grid gap-2 mt-3">
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="mdi mdi-content-save me-2"></i>Salvar Alterações
                    </button>
                    <a href="{{ route('stock-movements.show', $stockMovement) }}" class="btn btn-outline-primary">
                        <i class="mdi mdi-eye me-2"></i>Ver Detalhes
                    </a>
                    <a href="{{ route('stock-movements.index') }}" class="btn btn-outline-secondary">
                        <i class="mdi mdi-arrow-left me-2"></i>Voltar à Lista
                    </a>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const movementTypeCards = document.querySelectorAll('.movement-type-card');
        const movementTypeInput = document.getElementById('movement_type');
        const productSelect = document.getElementById('product_id');
        const quantityInput = document.getElementById('quantity');
        const dateInput = document.getElementById('movement_date');
        // Preview elements
        const previewIcon = document.getElementById('preview-icon');
        const previewType = document.getElementById('preview-type');
        const previewDescription = document.getElementById('preview-description');
        const previewProduct = document.getElementById('preview-product');
        const previewMovementType = document.getElementById('preview-movement-type');
        const previewQuantity = document.getElementById('preview-quantity');
        const previewDate = document.getElementById('preview-date');
        updatePreview();
        movementTypeCards.forEach(card => {
            card.addEventListener('click', function() {
                movementTypeCards.forEach(c => c.classList.remove('selected'));
                this.classList.add('selected');
                movementTypeInput.value = this.dataset.type;
                updatePreview();
            });
        });
        [productSelect, quantityInput, dateInput].forEach(input => {
            input.addEventListener('change', updatePreview);
            input.addEventListener('input', updatePreview);
        });
        function updatePreview() {
            const selectedType = movementTypeInput.value;
            const selectedProduct = productSelect.options[productSelect.selectedIndex];
            const quantity = quantityInput.value;
            const date = dateInput.value;
            if (selectedType) {
                switch (selectedType) {
                    case 'in':
                        previewIcon.innerHTML = '<i class="mdi mdi-arrow-up-bold"></i>';
                        previewType.textContent = 'Entrada de Estoque';
                        previewDescription.textContent = 'Aumentará o estoque disponível';
                        previewMovementType.innerHTML = '<span class="badge bg-success">Entrada</span>';
                        break;
                    case 'out':
                        previewIcon.innerHTML = '<i class="mdi mdi-arrow-down-bold"></i>';
                        previewType.textContent = 'Saída de Estoque';
                        previewDescription.textContent = 'Diminuirá o estoque disponível';
                        previewMovementType.innerHTML = '<span class="badge bg-danger">Saída</span>';
                        break;
                    case 'adjustment':
                        previewIcon.innerHTML = '<i class="mdi mdi-wrench"></i>';
                        previewType.textContent = 'Ajuste de Estoque';
                        previewDescription.textContent = 'Correção manual do estoque';
                        previewMovementType.innerHTML = '<span class="badge bg-warning text-dark">Ajuste</span>';
                        break;
                }
            }
            previewProduct.textContent = selectedProduct.text || previewProduct.textContent;
            previewQuantity.textContent = quantity || previewQuantity.textContent;
            previewDate.textContent = date ? new Date(date).toLocaleDateString('pt-BR') : previewDate.textContent;
        }
        const form = document.getElementById('movement-form');
        form.addEventListener('submit', function(e) {
            if (!movementTypeInput.value) {
                e.preventDefault();
                alert('Por favor, selecione o tipo de movimento.');
                return false;
            }
            if (confirm('Tem certeza que deseja salvar as alterações? Esta ação pode afetar os cálculos de estoque.')) {
                return true;
            } else {
                e.preventDefault();
                return false;
            }
        });
    });
</script>
@endpush