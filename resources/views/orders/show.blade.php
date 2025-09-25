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
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                                        <i class="mdi mdi-receipt text-primary fs-2"></i>
                                    </div>
                                    <div>
                                        <h2 class="mb-1">
                                            Pedido #{{ $order->id }}
                                            @switch($order->status)
                                                @case('active')
                                                    <span class="badge bg-warning ms-2">Ativo</span>
                                                @break

                                                @case('completed')
                                                    <span class="badge bg-info ms-2">Finalizado</span>
                                                @break

                                                @case('paid')
                                                    <span class="badge bg-success ms-2">Pago</span>
                                                @break

                                                @case('canceled')
                                                    <span class="badge bg-danger ms-2">Cancelado</span>
                                                @break
                                            @endswitch
                                        </h2>
                                        <div class="text-muted">
                                            <i class="mdi mdi-calendar me-1"></i>
                                            {{ $order->created_at->format('d/m/Y H:i') }}
                                            •
                                            <i class="mdi mdi-table-furniture me-1"></i>
                                            Mesa {{ $order->table->number }}
                                            •
                                            <i class="mdi mdi-account me-1"></i>
                                            {{ $order->customer_name ?? 'Cliente não informado' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 text-md-end">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
                                        <i class="mdi mdi-arrow-left me-1"></i> Voltar
                                    </a>

                                    @if ($order->status === 'active')
                                        <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-primary">
                                            <i class="mdi mdi-pencil me-1"></i> Editar
                                        </a>
                                        <form action="{{ route('orders.complete', $order) }}" method="POST"
                                            class="d-inline">
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
        </div>

        <!-- Main Content -->
        <div class="row">
            <!-- Items Section -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="mdi mdi-cart-outline me-2"></i> Itens do Pedido
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0">Produto</th>
                                        <th class="border-0 text-center" style="width: 100px;">Qtd</th>
                                        <th class="border-0 text-end" style="width: 120px;">Preço Unit.</th>
                                        <th class="border-0 text-end" style="width: 120px;">Total</th>
                                        <th class="border-0 text-center" style="width: 120px;">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($order->items as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-light rounded p-2 me-3">
                                                        <i class="mdi mdi-food-variant text-muted"></i>
                                                    </div>
                                                    <div>
                                                        <strong class="d-block">{{ $item->product->name }}</strong>
                                                        @if ($item->notes)
                                                            <small class="text-muted">{{ $item->notes }}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-primary rounded-pill px-3 py-2">
                                                    {{ $item->quantity }}
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                {{ number_format($item->unit_price, 2, ',', '.') }} MZN
                                            </td>
                                            <td class="text-end fw-bold text-success">
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
                                                <td colspan="5" class="text-center py-4">
                                                    <div class="text-muted">
                                                        <i class="mdi mdi-cart-off display-4 mb-2"></i>
                                                        <p class="mb-0">Nenhum item adicionado ao pedido</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <th colspan="3" class="text-end border-0">Total Geral:</th>
                                            <th colspan="2" class="text-end border-0">
                                                <span class="h5 text-success mb-0">
                                                    {{ number_format($order->total_amount, 2, ',', '.') }} MZN
                                                </span>
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Info (if paid) -->
                    @if ($order->status === 'paid')
                        <div class="card mt-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="mdi mdi-cash-multiple me-2"></i> Informações de Pagamento
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Método de Pagamento:</strong><br>
                                        <span class="text-capitalize">
                                            @switch($order->payment_method)
                                                @case('cash')
                                                    Dinheiro
                                                @break

                                                @case('card')
                                                    Cartão
                                                @break

                                                @case('mpesa')
                                                    M-Pesa
                                                @break

                                                @case('emola')
                                                    E-Mola
                                                @break

                                                @case('mkesh')
                                                    M-Kesh
                                                @break

                                                @default
                                                    Não informado
                                            @endswitch
                                        </span>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Data do Pagamento:</strong><br>
                                        {{ $order->paid_at ? $order->paid_at->format('d/m/Y H:i') : 'Não informado' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="row mb-4">

                        <!-- Quick Actions -->
                        <div class="card mt-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="mdi mdi-lightning-bolt me-2"></i> Ações Rápidas
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    @if ($order->status === 'paid')
                                        <a href="{{ route('orders.print', $order->id) }}" class="btn btn-info"
                                            target="_blank">
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

            <!-- Sidebar Info -->
            <div class="col-lg-4">
                <!-- Order Summary -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="mdi mdi-information-outline me-2"></i> Resumo do Pedido
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong class="text-muted d-block">Mesa</strong>
                            <span class="h6">{{ $order->table->number }}</span>
                        </div>

                        <div class="mb-3">
                            <strong class="text-muted d-block">Cliente</strong>
                            <span class="h6">{{ $order->customer_name ?? 'Não informado' }}</span>
                        </div>

                        <div class="mb-3">
                            <strong class="text-muted d-block">Data/Hora</strong>
                            <span class="h6">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                        </div>

                        <div class="mb-3">
                            <strong class="text-muted d-block">Status</strong>
                            @switch($order->status)
                                @case('active')
                                    <span class="badge bg-warning">Ativo</span>
                                @break

                                @case('completed')
                                    <span class="badge bg-info">Finalizado</span>
                                @break

                                @case('paid')
                                    <span class="badge bg-success">Pago</span>
                                @break

                                @case('canceled')
                                    <span class="badge bg-danger">Cancelado</span>
                                @break
                            @endswitch
                        </div>

                        <div class="mt-4 p-3 bg-light rounded">
                            <strong class="text-muted d-block">Total do Pedido</strong>
                            <span class="h4 text-success">{{ number_format($order->total_amount, 2, ',', '.') }} MZN</span>
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="mdi mdi-note-text-outline me-2"></i> Observações
                        </h5>
                    </div>
                    <div class="card-body">
                        @if ($order->notes)
                            <p class="mb-0">{{ $order->notes }}</p>
                        @else
                            <em class="text-muted">Nenhuma observação</em>
                        @endif
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
                            <h5 class="modal-title" id="paymentModalLabel">
                                <i class="mdi mdi-cash-register me-2"></i> Registrar Pagamento
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
                                <textarea name="notes" id="notes" class="form-control" rows="3"
                                    placeholder="Notas adicionais sobre o pagamento..."></textarea>
                            </div>

                            <div class="alert alert-info">
                                <div class="d-flex justify-content-between align-items-center">
                                    <strong>Total a Pagar:</strong>
                                    <span class="h5 mb-0 text-success">
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

    @push('styles')
        <style>
            .card {
                border: none;
                border-radius: 12px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
                transition: all 0.3s ease;
            }

            .card:hover {
                box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
                transform: translateY(-2px);
            }

            .card-header {
                background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
                border-bottom: 1px solid #e9ecef;
                padding: 1.25rem 1.5rem;
            }

            .card-title {
                color: #2d3748;
                font-weight: 600;
            }

            .table th {
                border-top: none;
                font-weight: 600;
                color: #4a5568;
                font-size: 0.875rem;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            .badge {
                font-weight: 500;
                padding: 0.5rem 0.75rem;
            }

            .btn {
                border-radius: 8px;
                font-weight: 500;
                transition: all 0.3s ease;
            }

            .btn-group .btn {
                margin: 0 2px;
            }

            .modal-content {
                border: none;
                border-radius: 12px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            }

            .modal-header {
                border-bottom: 1px solid #e9ecef;
                background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            }

            @media (max-width: 768px) {
                .btn-group {
                    width: 100%;
                }

                .btn-group .btn {
                    margin: 2px 0;
                    width: 100%;
                }
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Auto-hide alerts after 5 seconds
                setTimeout(() => {
                    const alerts = document.querySelectorAll('.alert');
                    alerts.forEach(alert => {
                        const bsAlert = new bootstrap.Alert(alert);
                        setTimeout(() => bsAlert.close(), 5000);
                    });
                }, 100);

                // Payment modal validation
                const paymentModal = document.getElementById('paymentModal');
                if (paymentModal) {
                    paymentModal.addEventListener('show.bs.modal', function() {
                        const form = this.querySelector('form');
                        form.reset();
                    });
                }

                // Print receipt function
                window.printReceipt = function(orderId) {
                    window.open('/orders/' + orderId + '/print-receipt', '_blank');
                };
            });
        </script>
    @endpush
