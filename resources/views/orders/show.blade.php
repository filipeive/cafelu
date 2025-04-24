@extends('layouts.app')

@section('title', 'Detalhes do Pedido')

@section('content')
    <div class="container-wrapper">
        <div class="card">
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h2 class="d-flex align-items-center gap-2">
                            Pedido #{{ $order->id }}
                            @if ($order->status === 'active')
                                <span class="badge bg-warning text-dark">Ativo</span>
                            @elseif($order->status === 'completed')
                                <span class="badge bg-info">Finalizado</span>
                            @elseif($order->status === 'paid')
                                <span class="badge bg-success">Pago</span>
                            @elseif($order->status === 'canceled')
                                <span class="badge bg-danger">Cancelado</span>
                            @endif
                        </h2>
                        <div class="card shadow-sm p-3 mb-3">
                            <div class="order-details">
                                <div class="detail-item">
                                    <i class="mdi mdi-table-furniture me-2"></i>
                                    <strong>Mesa:</strong> {{ $order->table->number }}
                                </div>
                                <div class="detail-item">
                                    <i class="mdi mdi-account me-2"></i>
                                    <strong>Cliente:</strong> {{ $order->customer_name ?? 'Não informado' }}
                                </div>
                                <div class="detail-item">
                                    <i class="mdi mdi-calendar-clock me-2"></i>
                                    <strong>Data:</strong> {{ $order->created_at->format('d/m/Y H:i') }}
                                </div>
                                <div class="detail-item">
                                    <i class="mdi mdi-account-circle me-2"></i>
                                    <strong>Atendente:</strong> {{ $order->user->name }}
                                </div>
                                @if ($order->status === 'paid')
                                    <div class="detail-item">
                                        <i class="mdi mdi-credit-card me-2"></i>
                                        <strong>Método de Pagamento:</strong>
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
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-end gap-2 flex-wrap">
                            <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
                                <i class="mdi mdi-arrow-left"></i> Voltar
                            </a>

                            @if ($order->status === 'active' || $order->status === 'completed')
                                <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-primary">
                                    <i class="mdi mdi-pencil"></i> Editar
                                </a>
                            @endif
                            @if ($order->status === 'active' && $order->items->count() > 0)
                                <form action="{{ route('orders.complete', $order) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success">
                                        <i class="mdi mdi-check-circle"></i> Finalizar
                                    </button>
                                </form>
                            @endif

                            @if ($order->status === 'completed')
                                <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                    data-bs-target="#paymentModal">
                                    <i class="mdi mdi-cash"></i> Pagamento
                                </button>
                            @endif

                            @if ($order->status !== 'canceled' && $order->status !== 'paid')
                                <form action="{{ route('orders.cancel', $order) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-danger"
                                        onclick="return confirm('Tem certeza que deseja cancelar este pedido?')">
                                        <i class="mdi mdi-close-circle"></i> Cancelar
                                    </button>
                                </form>
                            @endif

                            @if (!in_array($order->status, ['paid', 'canceled', 'active']))
                                <button class="btn btn-info btn-icon btn-sm ms-2" data-bs-toggle="tooltip" title="Imprimir"
                                    onclick="printRecibo({{ $order->id }})">
                                    <i class="mdi mdi-printer"></i> Imprimir Conta
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                @if (session('success'))
                    <div class="toast-notification toast-success bg-success shadow-lg">
                        <div class="toast-icon"><i class="mdi mdi-check-circle"></i></div>
                        <div class="toast-message">{{ session('success') }}</div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="toast-notification toast-error bg-danger shadow-lg">
                        <div class="toast-icon"><i class="mdi mdi-alert-circle"></i></div>
                        <div class="toast-message">{{ session('error') }}</div>
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-8">
                        <div class="card shadow-sm">
                            <div class="card-header bg-light">
                                <h5 class="mb-0"><i class="mdi mdi-format-list-bulleted me-2"></i>Itens do Pedido</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Produto</th>
                                                <th>Qtd</th>
                                                <th>Preço Unit.</th>
                                                <th>Total</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($order->items as $item)
                                                <tr>
                                                    <td>{{ $item->product->name }}</td>
                                                    <td>{{ $item->quantity }}</td>
                                                    <td>{{ number_format($item->unit_price, 2, ',', '.') }} MZN</td>
                                                    <td>{{ number_format($item->total_price, 2, ',', '.') }} MZN</td>
                                                    <td>
                                                        @switch($item->status)
                                                            @case('pending')
                                                                <span class="badge bg-warning text-dark">Pendente</span>
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
                                                        <td colspan="5" class="text-center text-muted">Nenhum item adicionado
                                                            ao
                                                            pedido</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                            <tfoot class="table-light">
                                                <tr>
                                                    <th colspan="3" class="text-end">Total:</th>
                                                    <th colspan="2" class="text-primary">
                                                        {{ number_format($order->total_amount, 2, ',', '.') }} MZN</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card shadow-sm mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="mdi mdi-note-text me-2"></i>Observações</h5>
                                </div>
                                <div class="card-body">
                                    @if ($order->notes)
                                        {{ $order->notes }}
                                    @else
                                        <em class="text-muted">Nenhuma observação</em>
                                    @endif
                                </div>
                            </div>

                            @if ($order->table->group_id)
                                <div class="card shadow-sm">
                                    <div class="card-header bg-light">
                                        <h5 class="mb-0"><i class="mdi mdi-table-multiple me-2"></i>Grupo de Mesas</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="detail-item">
                                            <i class="mdi mdi-table-furniture me-2"></i>
                                            <strong>Mesa Principal:</strong> {{ $order->table->number }}
                                        </div>
                                        <div class="detail-item">
                                            <i class="mdi mdi-account-group me-2"></i>
                                            <strong>Capacidade Total:</strong>
                                            {{ $order->table->merged_capacity ?? $order->table->capacity }} lugares
                                        </div>
                                        <div class="detail-item">
                                            <i class="mdi mdi-table-multiple me-2"></i>
                                            <strong>Mesas Agrupadas:</strong>
                                            @php
                                                $groupedTables = \App\Models\Table::where(
                                                    'group_id',
                                                    $order->table->group_id,
                                                )->get();
                                                $tableNumbers = $groupedTables->pluck('number')->implode(', ');
                                            @endphp
                                            {{ $tableNumbers }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Modal de Pagamento -->
                    <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog"
                        aria-labelledby="paymentModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <form action="{{ route('orders.pay', $order) }}" method="POST">
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="paymentModalLabel">
                                            <i class="mdi mdi-cash me-2"></i>Registrar Pagamento
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group mb-3">
                                            <label for="payment_method" class="form-label">Método de Pagamento</label>
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

                                        <div class="form-group mb-3">
                                            <label for="notes" class="form-label">Observações</label>
                                            <textarea name="notes" id="notes" class="form-control" rows="3"></textarea>
                                        </div>

                                        <div class="alert alert-info">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <strong>Total a Pagar:</strong>
                                                <span class="h4 mb-0">{{ number_format($order->total_amount, 2, ',', '.') }}
                                                    MZN</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary"
                                            data-bs-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-success">
                                            <i class="mdi mdi-check-circle me-1"></i> Confirmar Pagamento
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <style>
                .detail-item {
                    margin-bottom: 0.5rem;
                    display: flex;
                    align-items: center;
                    gap: 0.5rem;
                }

                .toast-notification {
                    border-radius: 8px;
                    padding: 1rem;
                    margin-bottom: 1rem;
                }

                .card {
                    border-radius: 8px;
                    border: none;
                }

                .card-header {
                    border-top-left-radius: 8px;
                    border-top-right-radius: 8px;
                }

                .btn {
                    border-radius: 6px;
                }

                .modal-content {
                    border-radius: 12px;
                    border: none;
                }
            </style>
        @endsection
