@extends('layouts.app')

@section('title', 'Registrar Movimento de Estoque')
@section('page-title', 'Registrar Movimento de Estoque')
@section('title-icon', 'mdi-clipboard-plus')

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('stock-movements.index') }}">Movimentos de Estoque</a>
    </li>
    <li class="breadcrumb-item active">Novo Movimento</li>
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
    <form action="{{ route('stock-movements.store') }}" method="POST" id="movement-form">
        @csrf
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
                                    {{ old('product_id') == $product->id ? 'selected' : '' }}>
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
                            <div class="movement-type-card in" data-type="in">
                                <div class="type-icon"><i class="mdi mdi-arrow-up-bold"></i></div>
                                <h6 class="fw-bold">Entrada</h6>
                                <small class="text-muted">Recebimento de produtos</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="movement-type-card out" data-type="out">
                                <div class="type-icon"><i class="mdi mdi-arrow-down-bold"></i></div>
                                <h6 class="fw-bold">Saída</h6>
                                <small class="text-muted">Venda ou consumo</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="movement-type-card adjustment" data-type="adjustment">
                                <div class="type-icon"><i class="mdi mdi-wrench"></i></div>
                                <h6 class="fw-bold">Ajuste</h6>
                                <small class="text-muted">Correção de estoque</small>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="movement_type" id="movement_type" 
                           value="{{ old('movement_type') }}" required>
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
                                   value="{{ old('quantity') }}" required>
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="movement_date" class="form-label required-field">Data do Movimento</label>
                            <input type="date" class="form-control @error('movement_date') is-invalid @enderror" 
                                   id="movement_date" name="movement_date" 
                                   value="{{ old('movement_date', date('Y-m-d')) }}" required>
                            @error('movement_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <label for="reason" class="form-label">Motivo/Observações</label>
                            <textarea class="form-control @error('reason') is-invalid @enderror" 
                                      id="reason" name="reason" rows="3" 
                                      placeholder="Descreva o motivo deste movimento (opcional)">{{ old('reason') }}</textarea>
                            @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <!-- Preview -->
                <div class="movement-preview" id="movement-preview">
                    <div class="text-center">
                        <div class="movement-icon" id="preview-icon">
                            <i class="mdi mdi-help-circle-outline text-muted fs-2"></i>
                        </div>
                        <h6 id="preview-type">Selecione o tipo de movimento</h6>
                        <p class="text-muted mb-3" id="preview-description">
                            Escolha um produto e tipo de movimento para ver o resumo
                        </p>
                        <div class="row g-2 text-start" id="preview-details" style="display: none;">
                            <div class="col-6"><strong>Produto:</strong></div>
                            <div class="col-6" id="preview-product">-</div>
                            <div class="col-6"><strong>Tipo:</strong></div>
                            <div class="col-6" id="preview-movement-type">-</div>
                            <div class="col-6"><strong>Quantidade:</strong></div>
                            <div class="col-6" id="preview-quantity">-</div>
                            <div class="col-6"><strong>Data:</strong></div>
                            <div class="col-6" id="preview-date">-</div>
                        </div>
                    </div>
                </div>
                <!-- Ações -->
                <div class="d-grid gap-2 mt-3">
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="mdi mdi-content-save me-2"></i>Registrar Movimento
                    </button>
                    <a href="{{ route('stock-movements.index') }}" class="btn btn-outline-secondary">
                        <i class="mdi mdi-arrow-left me-2"></i>Voltar
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
        const previewDetails = document.getElementById('preview-details');
        const previewProduct = document.getElementById('preview-product');
        const previewMovementType = document.getElementById('preview-movement-type');
        const previewQuantity = document.getElementById('preview-quantity');
        const previewDate = document.getElementById('preview-date');
        // Set initial selected movement type
        const initialType = movementTypeInput.value;
        if (initialType) {
            const initialCard = document.querySelector(`[data-type="${initialType}"]`);
            if (initialCard) {
                initialCard.classList.add('selected');
                updatePreview();
            }
        }
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
                previewDetails.style.display = 'block';
            } else {
                previewIcon.innerHTML = '<i class="mdi mdi-help-circle-outline text-muted fs-2"></i>';
                previewType.textContent = 'Selecione o tipo de movimento';
                previewDescription.textContent = 'Escolha um produto e tipo de movimento para ver o resumo';
                previewDetails.style.display = 'none';
            }
            previewProduct.textContent = selectedProduct.text || '-';
            previewQuantity.textContent = quantity ? quantity : '-';
            previewDate.textContent = date ? new Date(date).toLocaleDateString('pt-BR') : '-';
        }
        const form = document.getElementById('movement-form');
        form.addEventListener('submit', function(e) {
            if (!movementTypeInput.value) {
                e.preventDefault();
                alert('Por favor, selecione o tipo de movimento.');
                return false;
            }
        });
    });
</script>
@endpush