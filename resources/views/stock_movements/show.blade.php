@extends('layouts.app')

@section('title', 'Detalhes do Movimento')
@section('title-icon', 'mdi-clipboard-list')

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('stock-movements.index') }}" class="d-flex align-items-center">
            <i class="mdi mdi-clipboard-list me-1"></i> Movimentos de Estoque
        </a>
    </li>
    <li class="breadcrumb-item active d-flex align-items-center">
        <i class="mdi mdi-file-document me-1"></i> Movimento #{{ $stockMovement->id }}
    </li>
@endsection

@section('styles')
    <style>
        .movement-detail-card {
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            background: var(--bs-card-bg);
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        .movement-header {
            padding: 2.5rem;
            text-align: center;
            position: relative;
        }

        .movement-icon {
            width: 80px;
            height: 80px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2.5rem;
        }

        .movement-title {
            font-size: 1.75rem;
            font-weight: 700;
            margin: 0;
            color: var(--bs-body-color);
        }

        .movement-subtitle {
            color: var(--bs-secondary-color);
            margin: 0.5rem 0 1.5rem;
            font-size: 1rem;
        }

        .quantity-display {
            font-size: 2rem;
            font-weight: 700;
            padding: 1.25rem;
            border-radius: 12px;
            margin: 1rem 0;
            display: inline-block;
            min-width: 180px;
        }

        .quantity-in {
            background: rgba(34, 197, 94, 0.1);
            color: var(--success-color);
            border: 2px solid var(--success-color);
        }

        .quantity-out {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger-color);
            border: 2px solid var(--danger-color);
        }

        .quantity-adjustment {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning-color);
            border: 2px solid var(--warning-color);
        }

        .detail-section {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .detail-section:last-child {
            border-bottom: none;
        }

        .detail-section h6 {
            margin-bottom: 1.25rem;
            font-weight: 600;
            color: var(--bs-body-color);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: 500;
            color: var(--bs-secondary-color);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .detail-value {
            color: var(--bs-body-color);
            font-weight: 500;
            text-align: right;
        }

        .product-preview {
            display: flex;
            align-items: center;
            padding: 1rem;
            background: rgba(0, 0, 0, 0.03);
            border-radius: 12px;
            margin: 1rem 0;
        }

        .product-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            font-size: 1.25rem;
        }

        .product-info h6 {
            margin: 0 0 0.25rem;
            color: var(--bs-body-color);
            font-weight: 500;
        }

        .product-info small {
            color: var(--bs-secondary-color);
        }

        .timeline {
            position: relative;
            padding-left: 2rem;
            margin-top: 1rem;
        }

        .timeline-item {
            position: relative;
            padding: 1rem 0;
            margin-bottom: 1rem;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -28px;
            top: 1.25rem;
            width: 24px;
            height: 24px;
            background: var(--primary-color);
            border: 3px solid var(--bs-card-bg);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .timeline-item::after {
            content: '';
            position: absolute;
            left: -16px;
            top: 36px;
            bottom: -12px;
            width: 2px;
            background: rgba(0, 0, 0, 0.1);
        }

        .timeline-item:last-child::after {
            display: none;
        }

        .timeline-content h6 {
            margin: 0 0 0.25rem;
            font-size: 0.95rem;
            color: var(--bs-body-color);
            font-weight: 500;
        }

        .timeline-content p {
            margin: 0;
            font-size: 0.85rem;
            color: var(--bs-secondary-color);
        }

        .action-buttons {
            background: var(--bs-card-bg);
            border-radius: 16px;
            padding: 1.5rem;
            margin-top: 1.5rem;
        }

        @media (max-width: 768px) {
            .movement-header {
                padding: 1.5rem;
            }

            .quantity-display {
                font-size: 1.5rem;
                min-width: 140px;
                padding: 1rem;
            }
        }
    </style>
@endsection

@section('content')
    <div class="row g-4">
        <!-- Movement Header -->
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                <div class="movement-detail-card">
                    <div class="movement-header bg-gradient-{{ $stockMovement->movement_type }}">
                        <div class="movement-icon text-white">
                            @switch($stockMovement->movement_type)
                                @case('in')
                                    <i class="mdi mdi-arrow-up"></i>
                                @break

                                @case('out')
                                    <i class="mdi mdi-arrow-down"></i>
                                @break

                                @case('adjustment')
                                    <i class="mdi mdi-wrench"></i>
                                @break
                            @endswitch
                        </div>

                        <h1 class="movement-title">
                            @switch($stockMovement->movement_type)
                                @case('in')
                                    Entrada de Estoque
                                @break

                                @case('out')
                                    Saída de Estoque
                                @break

                                @case('adjustment')
                                    Ajuste de Estoque
                                @break
                            @endswitch
                        </h1>

                        <p class="movement-subtitle">
                            Movimento registrado em {{ $stockMovement->movement_date->format('d/m/Y') }} às
                            {{ $stockMovement->created_at->format('H:i') }}
                        </p>

                        <div class="quantity-display quantity-{{ $stockMovement->movement_type }}">
                            @if ($stockMovement->movement_type === 'out')
                                -
                            @endif{{ number_format($stockMovement->quantity) }}
                            <div style="font-size: 0.9rem; margin-top: 0.5rem;">
                                @if ($stockMovement->movement_type === 'in')
                                    unidades adicionadas
                                @elseif($stockMovement->movement_type === 'out')
                                    unidades removidas
                                @else
                                    unidades ajustadas
                                @endif
                            </div>
                        </div>
                    </div>
                </div></div>
            </div>
        </div>

        <div class="col-lg-8 card" >
            <div class="card-body">
            <!-- Product Information -->
            <div class="movement-detail-card">
                <div class="detail-section">
                    <h6>
                        <i class="mdi mdi-cube text-primary"></i>
                        Informações do Produto
                    </h6>

                    @if ($stockMovement->product)
                        <div class="product-preview">
                            <div
                                class="product-icon bg-{{ $stockMovement->product->type === 'service' ? 'info' : 'secondary' }} bg-opacity-10 text-{{ $stockMovement->product->type === 'service' ? 'info' : 'secondary' }}">
                                <i
                                    class="mdi {{ $stockMovement->product->type === 'service' ? 'mdi-cog' : 'mdi-cube' }}"></i>
                            </div>
                            <div class="product-info">
                                <h6>{{ $stockMovement->product->name }}</h6>
                                <small>
                                    {{ $stockMovement->product->type === 'service' ? 'Serviço' : 'Produto' }}
                                    @if ($stockMovement->product->description)
                                        • {{ Str::limit($stockMovement->product->description, 60) }}
                                    @endif
                                </small>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="mdi mdi-alert-circle-outline me-2"></i>
                            Este produto foi removido do sistema. ID original: {{ $stockMovement->product_id }}
                        </div>
                    @endif
                </div>

                <!-- Movement Details -->
                <div class="movement-detail-card">
                    <div class="detail-section">
                        <h6>
                            <i class="mdi mdi-information-outline text-primary"></i>
                            Detalhes do Movimento
                        </h6>

                        <div class="detail-row">
                            <span class="detail-label">
                                <i class="mdi mdi-identifier"></i> ID do Movimento
                            </span>
                            <span class="detail-value">#{{ $stockMovement->id }}</span>
                        </div>

                        <div class="detail-row">
                            <span class="detail-label">
                                <i class="mdi mdi-swap-vertical"></i> Tipo
                            </span>
                            <span class="detail-value">
                                @switch($stockMovement->movement_type)
                                    @case('in')
                                        <span class="badge bg-success">Entrada</span>
                                    @break

                                    @case('out')
                                        <span class="badge bg-danger">Saída</span>
                                    @break

                                    @case('adjustment')
                                        <span class="badge bg-warning text-dark">Ajuste</span>
                                    @break
                                @endswitch
                            </span>
                        </div>

                        <div class="detail-row">
                            <span class="detail-label">
                                <i class="mdi mdi-numeric"></i> Quantidade
                            </span>
                            <span
                                class="detail-value fw-bold fs-5 
                            @if ($stockMovement->movement_type === 'in') text-success
                            @elseif($stockMovement->movement_type === 'out') text-danger
                            @else text-warning @endif">
                                @if ($stockMovement->movement_type === 'out')
                                    -
                                @endif{{ number_format($stockMovement->quantity) }}
                            </span>
                        </div>

                        <div class="detail-row">
                            <span class="detail-label">
                                <i class="mdi mdi-calendar"></i> Data do Movimento
                            </span>
                            <span class="detail-value">{{ $stockMovement->movement_date->format('d/m/Y') }}</span>
                        </div>

                        @if ($stockMovement->reason)
                            <div class="detail-row">
                                <span class="detail-label">
                                    <i class="mdi mdi-comment"></i> Motivo
                                </span>
                                <span class="detail-value">{{ $stockMovement->reason }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- User Information -->
                <div class="movement-detail-card">
                    <div class="detail-section">
                        <h6>
                            <i class="mdi mdi-account text-primary"></i>
                            Informações do Usuário
                        </h6>

                        <div class="detail-row">
                            <span class="detail-label">
                                <i class="mdi mdi-account"></i> Registrado por
                            </span>
                            <span class="detail-value">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2"
                                        style="width: 24px; height: 24px;">
                                        <small class="text-white fw-bold">
                                            {{ substr($stockMovement->user->name ?? 'S', 0, 1) }}
                                        </small>
                                    </div>
                                    {{ $stockMovement->user->name ?? 'Sistema' }}
                                </div>
                            </span>
                        </div>

                        <div class="detail-row">
                            <span class="detail-label">
                                <i class="mdi mdi-clock"></i> Data de Criação
                            </span>
                            <span class="detail-value">{{ $stockMovement->created_at->format('d/m/Y H:i') }}</span>
                        </div>

                        @if ($stockMovement->updated_at != $stockMovement->created_at)
                            <div class="detail-row">
                                <span class="detail-label">
                                    <i class="mdi mdi-update"></i> Última Atualização
                                </span>
                                <span class="detail-value">{{ $stockMovement->updated_at->format('d/m/Y H:i') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div></div>

        <div class="col-lg-4 card me-2">
            <div class="card-body">
            <!-- Timeline -->
            <div class="movement-detail-card">
                <div class="detail-section">
                    <h6>
                        <i class="mdi mdi-timeline-clock text-primary"></i>
                        Linha do Tempo
                    </h6>

                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-content">
                                <h6>Movimento Criado</h6>
                                <p>{{ $stockMovement->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>

                        @if ($stockMovement->updated_at != $stockMovement->created_at)
                            <div class="timeline-item">
                                <div class="timeline-content">
                                    <h6>Movimento Atualizado</h6>
                                    <p>{{ $stockMovement->updated_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        @endif

                        <div class="timeline-item">
                            <div class="timeline-content">
                                <h6>Estoque Atualizado</h6>
                                <p>
                                    @switch($stockMovement->movement_type)
                                        @case('in')
                                            Estoque aumentado em {{ $stockMovement->quantity }} unidades
                                        @break

                                        @case('out')
                                            Estoque reduzido em {{ $stockMovement->quantity }} unidades
                                        @break

                                        @case('adjustment')
                                            Estoque ajustado em {{ $stockMovement->quantity }} unidades
                                        @break
                                    @endswitch
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <div class="d-grid gap-2">
                    @if (Auth::user()->role == 'admin')
                        <a href="{{ route('stock-movements.edit', $stockMovement) }}" class="btn btn-primary">
                            <i class="mdi mdi-pencil me-2"></i> Editar Movimento
                        </a>
                    @endif

                    <a href="{{ route('stock-movements.create') }}" class="btn btn-outline-success">
                        <i class="mdi mdi-plus me-2"></i> Novo Movimento
                    </a>

                    @if ($stockMovement->product)
                        <a href="{{ route('products.show', $stockMovement->product) }}" class="btn btn-outline-info">
                            <i class="mdi mdi-cube me-2"></i> Ver Produto
                        </a>
                    @endif

                    <a href="{{ route('stock-movements.index') }}" class="btn btn-outline-secondary">
                        <i class="mdi mdi-arrow-left me-2"></i> Voltar à Lista
                    </a>

                    @if (Auth::user()->role == 'admin')
                        <button type="button" class="btn btn-outline-danger" onclick="confirmDelete()">
                            <i class="mdi mdi-delete me-2"></i> Excluir Movimento
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div></div>
@endsection

@push('scripts')
    <script>
        function confirmDelete() {
            Swal.fire({
                title: 'Tem certeza?',
                html: 'Deseja realmente excluir este movimento?<br><br>Esta ação não poderá ser desfeita e pode afetar os cálculos de estoque.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '<i class="mdi mdi-delete me-1"></i> Sim, excluir!',
                cancelButtonText: '<i class="mdi mdi-close me-1"></i> Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route('stock-movements.destroy', $stockMovement) }}';
                    form.innerHTML = `
                @csrf
                @method('DELETE')
            `;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
@endpush
