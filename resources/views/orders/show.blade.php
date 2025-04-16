@extends('layouts.app')

@section('content')
    <div class="container-wrapper">
        <div class="row mb-3">
            <div class="col-md-6">
                <h2>
                    Pedido #{{ $order->id }}
                    @if ($order->status === 'active')
                        <span class="badge badge-warning">Ativo</span>
                    @elseif($order->status === 'completed')
                        <span class="badge badge-info">Finalizado</span>
                    @elseif($order->status === 'paid')
                        <span class="badge badge-success">Pago</span>
                    @elseif($order->status === 'canceled')
                        <span class="badge badge-danger">Cancelado</span>
                    @endif
                </h2>
                <p>
                    <strong>Mesa:</strong> {{ $order->table->number }}<br>
                    <strong>Cliente:</strong> {{ $order->customer_name ?? 'Não informado' }}<br>
                    <strong>Data:</strong> {{ $order->created_at->format('d/m/Y H:i') }}<br>
                    @if ($order->status === 'paid')
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
                    @endif
                </p>
            </div>
            <div class="col-md-6 text-right">
                <a href="{{ route('orders.index') }}" class="btn btn-secondary">
                    <i class="mdi mdi-arrow-left"></i> Voltar para Pedidos
                </a>

                @if ($order->status === 'active')
                    <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-primary">
                        <i class="mdi mdi-pencil"></i> Editar Pedido
                    </a>
                    <form action="{{ route('orders.complete', $order) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success">
                            <i class="mdi mdi-check-circle"></i> Finalizar Pedido
                        </button>
                    </form>
                @endif

                @if ($order->status === 'completed')
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#paymentModal">
                        <i class="mdi mdi-cash"></i> Registrar Pagamento
                    </button>
                @endif

                @if ($order->status !== 'canceled' && $order->status !== 'paid')
                    <form action="{{ route('orders.cancel', $order) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-danger"
                            onclick="return confirm('Tem certeza que deseja cancelar este pedido?')">
                            <i class="mdi mdi-close-circle"></i> Cancelar Pedido
                        </button>
                    </form>
                @endif

                @if (!in_array($order->status, ['paid','canceled']))
                    <a href="{{ route('orders.print-receipt', $order->id) }}" class="btn btn-info" target="_blank">
                        <i class="mdi mdi-printer"></i> Imprimir Recibo
                    </a>
                @endif
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5>Itens do Pedido</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
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
                                                        <span class="badge badge-warning">Pendente</span>
                                                    @break

                                                    @case('preparing')
                                                        <span class="badge badge-info">Preparando</span>
                                                    @break

                                                    @case('ready')
                                                        <span class="badge badge-primary">Pronto</span>
                                                    @break

                                                    @case('delivered')
                                                        <span class="badge badge-success">Entregue</span>
                                                    @break

                                                    @case('cancelled')
                                                        <span class="badge badge-danger">Cancelado</span>
                                                    @break
                                                @endswitch
                                            </td>
                                        </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">Nenhum item adicionado ao pedido</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3" class="text-right">Total:</th>
                                            <th colspan="2">{{ number_format($order->total_amount, 2, ',', '.') }} MZN</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Observações</h5>
                        </div>
                        <div class="card-body">
                            @if ($order->notes)
                                {{ $order->notes }}
                            @else
                                <em>Nenhuma observação</em>
                            @endif
                        </div>
                    </div>

                    @if ($order->table->group_id)
                        <div class="card mt-3">
                            <div class="card-header">
                                <h5>Informações do Grupo de Mesas</h5>
                            </div>
                            <div class="card-body">
                                <p>
                                    <strong>Mesa Principal:</strong> {{ $order->table->number }}<br>
                                    <strong>Capacidade Total:</strong>
                                    {{ $order->table->merged_capacity ?? $order->table->capacity }} lugares<br>
                                    <strong>Mesas Agrupadas:</strong>
                                    @php
                                        $groupedTables = \App\Models\Table::where(
                                            'group_id',
                                            $order->table->group_id,
                                        )->get();
                                        $tableNumbers = $groupedTables->pluck('number')->implode(', ');
                                    @endphp
                                    {{ $tableNumbers }}
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Modal de Pagamento -->
        <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="{{ route('orders.pay', $order) }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="paymentModalLabel">Registrar Pagamento</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group mb-3">
                                <label for="payment_method">Método de Pagamento</label>
                                <select name="payment_method" id="payment_method" class="form-control" required>
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
                                <label for="notes">Observações</label>
                                <textarea name="notes" id="notes" class="form-control" rows="3"></textarea>
                            </div>

                            <div class="alert alert-info">
                                <div class="d-flex justify-content-between align-items-center">
                                    <strong>Total a Pagar:</strong>
                                    <span class="h5 mb-0">{{ number_format($order->total_amount, 2, ',', '.') }} MZN</span>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-success">
                                <i class="mdi mdi-check-circle"></i> Confirmar Pagamento
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endsection
