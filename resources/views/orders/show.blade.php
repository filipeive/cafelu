@extends('layouts.app')

@section('title', 'Detalhes do Pedido')
@section('title-icon', 'mdi-receipt')
@section('page-title', 'Detalhes do Pedido')

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('orders.index') }}" class="text-decoration-none">
            <i class="mdi mdi-receipt"></i> Pedidos
        </a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">
        Pedido #{{ $order->id }}
    </li>
@endsection

@push('styles')
<style>
:root {
    --primary-color: #0891b2;
    --success-color: #10b981;
    --warning-color: #f59e0b;
    --danger-color: #ef4444;
    --info-color: #06b6d4;
}

.order-header {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-left: 4px solid var(--primary-color);
    border-radius: 8px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}

.order-status-badge {
    font-size: 0.85rem;
    font-weight: 600;
    padding: 0.35rem 0.8rem;
    border-radius: 6px;
}

.card {
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}

.card-header {
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    padding: 1rem 1.25rem;
    font-weight: 600;
    color: #374151;
}

.table th {
    background: #f8fafc;
    font-weight: 600;
    color: #4b5563;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 1px solid #e2e8f0;
}

.table td {
    border-color: #f1f5f9;
    padding: 0.75rem;
    vertical-align: middle;
}

.product-icon {
    width: 32px;
    height: 32px;
    background: #f1f5f9;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #64748b;
}

.btn {
    border-radius: 6px;
    font-weight: 500;
    padding: 0.5rem 1rem;
    font-size: 0.9rem;
}

.badge {
    font-size: 0.8rem;
    font-weight: 500;
}

.total-amount {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--success-color);
}

.order-meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #64748b;
    font-size: 0.9rem;
}

.order-meta-item i {
    width: 16px;
}

@media (max-width: 768px) {
    .order-header {
        padding: 1rem;
    }
    
    .table-responsive {
        font-size: 0.85rem;
    }
    
    .btn-group-vertical {
        width: 100%;
    }
    
    .btn-group-vertical .btn {
        margin: 2px 0;
    }
}
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row">
        <div class="col-12">
            <div class="order-header">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-2">
                            <h4 class="mb-0 me-3">Pedido #{{ $order->id }}</h4>
                            @switch($order->status)
                                @case('active')
                                    <span class="order-status-badge bg-warning text-dark">Ativo</span>
                                @break
                                @case('completed')
                                    <span class="order-status-badge bg-info text-dark">Finalizado</span>
                                @break
                                @case('paid')
                                    <span class="order-status-badge bg-success text-white">Pago</span>
                                @break
                                @case('canceled')
                                    <span class="order-status-badge bg-danger text-white">Cancelado</span>
                                @break
                            @endswitch
                        </div>
                        
                        <div class="row g-3">
                            <div class="col-auto">
                                <div class="order-meta-item">
                                    <i class="mdi mdi-calendar"></i>
                                    {{ $order->created_at->format('d/m/Y H:i') }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="order-meta-item">
                                    <i class="mdi mdi-table-furniture"></i>
                                    Mesa {{ $order->table->number }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="order-meta-item">
                                    <i class="mdi mdi-account"></i>
                                    {{ $order->customer_name ?? 'Cliente não informado' }}
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="d-flex flex-wrap gap-2 justify-content-md-end">
                            <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
                                <i class="mdi mdi-arrow-left me-1"></i> Voltar
                            </a>
                            
                            @if ($order->status === 'active')
                                <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-primary">
                                    <i class="mdi mdi-pencil me-1"></i> Editar
                                </a>
                                <form action="{{ route('orders.complete', $order) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success">
                                        <i class="mdi mdi-check-circle me-1"></i> Finalizar
                                    </button>
                                </form>
                            @endif
                            
                            @if ($order->status === 'completed')
                                <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                    data-bs-target="#paymentModal">
                                    <i class="mdi mdi-cash me-1"></i> Pagar
                                </button>
                            @endif
                            
                            @if ($order->status !== 'canceled' && $order->status !== 'paid')
                                <form action="{{ route('orders.cancel', $order) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-danger"
                                        onclick="return confirm('Tem certeza que deseja cancelar este pedido?')">
                                        <i class="mdi mdi-close-circle me-1"></i> Cancelar
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="row">
        <!-- Items -->
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <i class="mdi mdi-cart-outline text-primary me-2"></i>
                    Itens do Pedido
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Produto</th>
                                    <th class="text-center" style="width: 80px;">Qtd</th>
                                    <th class="text-end" style="width: 100px;">Preço</th>
                                    <th class="text-end" style="width: 100px;">Total</th>
                                    <th class="text-center" style="width: 100px;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($order->items as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="product-icon me-3">
                                                    <i class="mdi mdi-food-variant"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-medium">{{ $item->product->name }}</div>
                                                    @if ($item->notes)
                                                        <small class="text-muted">{{ $item->notes }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-primary rounded-pill px-2">
                                                {{ $item->quantity }}
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            {{ number_format($item->unit_price, 2, ',', '.') }} MZN
                                        </td>
                                        <td class="text-end fw-medium">
                                            {{ number_format($item->total_price, 2, ',', '.') }} MZN
                                        </td>
                                        <td class="text-center">
                                            @switch($item->status)
                                                @case('pending')
                                                    <span class="badge bg-warning">Pendente</span>
                                                @break
                                                @case('preparing')
                                                    <span class="badge bg-info">Preparando</span>
                                                @break
                                                @case('ready')
                                                    <span class="badge bg-primary">Pronto</span>
                                                @break
                                                @case('delivered')
                                                    <span class="badge bg-success">Entregue</span>
                                                @break
                                                @case('cancelled')
                                                    <span class="badge bg-danger">Cancelado</span>
                                                @break
                                            @endswitch
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            <i class="mdi mdi-cart-off display-5 mb-2"></i>
                                            <p class="mb-0">Nenhum item adicionado ao pedido</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="3" class="text-end fw-bold border-0">Total:</td>
                                    <td colspan="2" class="text-end border-0">
                                        <span class="total-amount">
                                            {{ number_format($order->total_amount, 2, ',', '.') }} MZN
                                        </span>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Payment Info -->
            @if ($order->status === 'paid')
                <div class="card mt-3">
                    <div class="card-header d-flex align-items-center">
                        <i class="mdi mdi-cash-multiple text-success me-2"></i>
                        Informações de Pagamento
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="text-muted small">Método de Pagamento</label>
                                <p class="mb-0 fw-medium text-capitalize">
                                    @switch($order->payment_method)
                                        @case('cash') Dinheiro @break
                                        @case('card') Cartão @break
                                        @case('mpesa') M-Pesa @break
                                        @case('emola') E-Mola @break
                                        @case('mkesh') M-Kesh @break
                                        @default Não informado
                                    @endswitch
                                </p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Data do Pagamento</label>
                                <p class="mb-0 fw-medium">
                                    {{ $order->paid_at ? $order->paid_at->format('d/m/Y H:i') : 'Não informado' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="row g-3">
                <!-- Order Summary -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <i class="mdi mdi-information-outline text-info me-2"></i>
                            Resumo do Pedido
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="text-muted small d-block">Mesa</label>
                                <span class="fw-medium">{{ $order->table->number }}</span>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small d-block">Cliente</label>
                                <span class="fw-medium">{{ $order->customer_name ?? 'Não informado' }}</span>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small d-block">Data/Hora</label>
                                <span class="fw-medium">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small d-block">Status</label>
                                @switch($order->status)
                                    @case('active')<span class="badge bg-warning">Ativo</span>@break
                                    @case('completed')<span class="badge bg-info">Finalizado</span>@break
                                    @case('paid')<span class="badge bg-success">Pago</span>@break
                                    @case('canceled')<span class="badge bg-danger">Cancelado</span>@break
                                @endswitch
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center">
                                <label class="text-muted small">Total</label>
                                <span class="total-amount">{{ number_format($order->total_amount, 2, ',', '.') }} MZN</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <i class="mdi mdi-note-text-outline text-secondary me-2"></i>
                            Observações
                        </div>
                        <div class="card-body">
                            @if ($order->notes)
                                <p class="mb-0 text-break">{{ $order->notes }}</p>
                            @else
                                <em class="text-muted">Nenhuma observação</em>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <i class="mdi mdi-lightning-bolt text-warning me-2"></i>
                            Ações Rápidas
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                @if ($order->status === 'paid')
                                    <a href="{{ route('orders.print', $order->id) }}" class="btn btn-info" target="_blank">
                                        <i class="mdi mdi-printer me-1"></i> Imprimir Recibo
                                    </a>
                                @endif
                                
                                @if (!in_array($order->status, ['paid', 'canceled']))
                                    <a href="{{ route('orders.print-receipt', $order->id) }}" 
                                       class="btn btn-outline-primary" target="_blank">
                                        <i class="mdi mdi-receipt me-1"></i> Pré-visualizar
                                    </a>
                                @endif
                                
                                <a href="{{ route('orders.edit', $order->id) }}" 
                                   class="btn btn-outline-secondary {{ $order->status !== 'active' ? 'disabled' : '' }}">
                                    <i class="mdi mdi-pencil me-1"></i> Editar Itens
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('orders.pay', $order) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title d-flex align-items-center">
                        <i class="mdi mdi-cash-register text-success me-2"></i>
                        Registrar Pagamento
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="payment_method" class="form-label">Método de Pagamento *</label>
                        <select name="payment_method" id="payment_method" class="form-select" required>
                            <option value="">Selecione um método</option>
                            <option value="cash">Dinheiro</option>
                            <option value="card">Cartão</option>
                            <option value="mpesa">M-Pesa</option>
                            <option value="emola">E-Mola</option>
                            <option value="mkesh">M-Kesh</option>
                        </select>
                    </div>
                    
                    <input type="hidden" name="amount_paid" value="{{ $order->total_amount }}">
                    
                    <div class="mb-3">
                        <label for="notes" class="form-label">Observações</label>
                        <textarea name="notes" id="notes" class="form-control" rows="2"
                            placeholder="Notas adicionais sobre o pagamento..."></textarea>
                    </div>
                    
                    <div class="alert alert-light border">
                        <div class="d-flex justify-content-between align-items-center">
                            <strong>Total a Pagar:</strong>
                            <span class="fw-bold text-success fs-5">
                                {{ number_format($order->total_amount, 2, ',', '.') }} MZN
                            </span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">
                        <i class="mdi mdi-check-circle me-1"></i> Confirmar Pagamento
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-close alerts
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            setTimeout(() => bsAlert.close(), 5000);
        });
    }, 100);

    // Payment modal reset
    const paymentModal = document.getElementById('paymentModal');
    if (paymentModal) {
        paymentModal.addEventListener('show.bs.modal', function() {
            this.querySelector('form').reset();
        });
    }
});
</script>
@endpush