@extends('layouts.app')

@section('content')
    @php
        $pageTitle = 'Pedidos';

        function get_status_class_staradmin($status)
        {
            $classes = [
                'completed' => 'bg-success text-white',
                'active' => 'bg-warning text-white',
                'canceled' => 'bg-danger text-white',
                'paid' => 'bg-info text-white',
            ];
            return $classes[$status] ?? 'bg-secondary text-white';
        }
    @endphp

    <style>
        /* Custom CSS */
        .stat-card {
            transition: transform 0.2s, box-shadow 0.2s;
            border: none;
            border-radius: 15px;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
        }

        .orders-table {
            border-radius: 15px;
            overflow: hidden;
        }

        .table> :not(caption)>*>* {
            padding: 1rem 1.5rem;
        }

        .status-badge {
            padding: 8px 12px;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.85rem;
        }

        .action-btn {
            width: 35px;
            height: 35px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .action-btn:hover {
            transform: translateY(-2px);
        }

        .search-container {
            position: relative;
            max-width: 350px;
        }

        .search-container .search-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }

        .search-input {
            padding-left: 45px;
            border-radius: 10px;
            border: 1px solid #dee2e6;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .action-btn {
            width: auto;
            height: 35px;
            padding: 0 12px;
            display: flex;
            align-items: center;
            gap: 8px;
            justify-content: center;
            border-radius: 8px;
            transition: all 0.2s;
            font-size: 0.875rem;
        }

        .action-btn i {
            font-size: 1.1rem;
        }

        .action-btn:hover {
            transform: translateY(-2px);
        }

        .no-results {
            text-align: center;
            padding: 2rem;
            color: #6c757d;
        }
    </style>

    <div class="container-wrapper">
        <div class="row mb-4">
            <!-- Stats Cards Row -->
            <div class="col-sm-12 col-lg-3 grid-margin stretch-card">
                <div class="card card-rounded border-start border-primary border-4">
                    <div class="card-body pb-0">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="card-title card-title-dash text-muted mb-1">Total Pedidos</h6>
                                <h2 class="rate-percentage text-primary mb-2">{{ $total_orders }}</h2>
                                <p class="text-primary d-flex align-items-center mb-3">
                                    <i class="mdi mdi-receipt me-1"></i>
                                    <span>Pedidos acumulados</span>
                                </p>
                            </div>
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                <i class="mdi mdi-receipt text-primary icon-md m-0"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-lg-3 grid-margin stretch-card">
                <div class="card card-rounded border-start border-success border-4">
                    <div class="card-body pb-0">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="card-title card-title-dash text-muted mb-1">Pedidos Hoje</h6>
                                <h2 class="rate-percentage text-success mb-2">{{ $totalToday }}</h2>
                                <p class="text-success d-flex align-items-center mb-3">
                                    <i class="mdi mdi-calendar-today me-1"></i>
                                    <span>Hoje</span>
                                </p>
                            </div>
                            <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                <i class="mdi mdi-calendar-today text-success icon-md m-0"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-lg-3 grid-margin stretch-card">
                <div class="card card-rounded border-start border-info border-4">
                    <div class="card-body pb-0">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="card-title card-title-dash text-muted mb-1">Valor Total</h6>
                                <h2 class="rate-percentage text-info mb-2">MZN {{ number_format($totalToday, 2) }}</h2>
                                <p class="text-info d-flex align-items-center mb-3">
                                    <i class="mdi mdi-cash-multiple me-1"></i>
                                    <span>Total do dia</span>
                                </p>
                            </div>
                            <div class="bg-info bg-opacity-10 rounded-circle p-3">
                                <i class="mdi mdi-cash-multiple text-info icon-md m-0"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-lg-3 grid-margin stretch-card">
                <div class="card card-rounded border-start border-warning border-4">
                    <div class="card-body pb-0">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="card-title card-title-dash text-muted mb-1">Pedidos Ativos</h6>
                                <h2 class="rate-percentage text-warning mb-2">{{ $totalOpen }}</h2>
                                <p class="text-warning d-flex align-items-center mb-3">
                                    <i class="mdi mdi-clock-alert me-1"></i>
                                    <span>Em andamento</span>
                                </p>
                            </div>
                            <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                                <i class="mdi mdi-clock-alert text-warning icon-md m-0"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12 grid-margin">
                <div class="card card-rounded shadow-sm">
                    <div class="card-body">
                        <!-- Cabeçalho -->
                        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                            <div class="d-flex align-items-center">
                                <h4 class="card-title mb-0 me-2">Lista de Pedidos</h4>
                                <span class="badge bg-primary rounded-pill">{{ $total_orders }} pedidos</span>
                            </div>
                            <div class="d-flex align-items-center gap-3 flex-wrap">
                                <!-- Barra de Pesquisa -->
                                <div class="search-field d-none d-md-flex">
                                    <div class="input-group">
                                        <span class="input-group-text bg-transparent border-end-0">
                                            <i class="mdi mdi-magnify text-primary"></i>
                                        </span>
                                        <input type="text" class="form-control bg-transparent border-start-0 ps-0"
                                            placeholder="Pesquisar pedidos..." id="ordersSearch"
                                            value="{{ old('search', $search) }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tabela -->
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="py-3">ID</th>
                                        <th class="py-3">Data</th>
                                        <th class="py-3">Mesa</th>
                                        <th class="py-3">Total</th>
                                        <th class="py-3">Status</th>
                                        <th class="py-3 text-center" width="200">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($orders as $order)
                                        <tr>
                                            <td class="py-3">
                                                <span
                                                    class="fw-medium text-primary">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span>
                                            </td>
                                            <td class="py-3">
                                                <div class="d-flex align-items-center">
                                                    <span class="bg-primary bg-opacity-10 p-2 rounded me-2">
                                                        <i class="mdi mdi-calendar text-primary"></i>
                                                    </span>
                                                    <div>
                                                        <div class="fw-medium">{{ $order->created_at->format('d/m/Y') }}
                                                        </div>
                                                        <small
                                                            class="text-muted">{{ $order->created_at->format('H:i') }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-3">
                                                <span class="badge bg-light text-dark">Mesa {{ $order->table_id }}</span>
                                            </td>
                                            <td class="py-3">
                                                <div class="fw-medium">MZN {{ number_format($order->total_amount, 2) }}
                                                </div>
                                            </td>
                                            <td class="py-3">
                                                <div
                                                    class="badge {{ get_status_class_staradmin2($order->status) }} bg-secondary rounded-pill px-3">
                                                    <i class="mdi mdi-circle-medium me-1"></i>
                                                    {{ ucfirst($order->status) }}
                                                </div>
                                            </td>
                                            <td class="py-3">
                                                <div class="d-flex justify-content-center gap-2">
                                                    <a href="{{ route('orders.show', $order->id) }}"
                                                        class="btn btn-primary btn-icon btn-sm" data-bs-toggle="tooltip"
                                                        title="Ver Detalhes">
                                                        <i class="mdi mdi-eye"></i>
                                                    </a>
                                                    <!-- Substitua o botão existente por este -->
                                                    @if ($order->status == 'completed')
                                                        <!-- Modal de Pagamento -->
                                                        <div class="modal fade" id="paymentModal{{ $order->id }}"
                                                            tabindex="-1"
                                                            aria-labelledby="paymentModalLabel{{ $order->id }}"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <form action="{{ route('orders.pay', $order) }}"
                                                                        method="POST">
                                                                        @csrf
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title"
                                                                                id="paymentModalLabel{{ $order->id }}">
                                                                                Registrar Pagamento #{{ $order->id }}
                                                                            </h5>
                                                                            <button type="button" class="btn-close"
                                                                                data-bs-dismiss="modal"
                                                                                aria-label="Close"></button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <div class="form-group mb-3">
                                                                                <label
                                                                                    for="payment_method{{ $order->id }}">Método
                                                                                    de Pagamento</label>
                                                                                <select name="payment_method"
                                                                                    id="payment_method{{ $order->id }}"
                                                                                    class="form-select" required>
                                                                                    <option value="">Selecione um
                                                                                        método
                                                                                    </option>
                                                                                    <option value="cash">Dinheiro
                                                                                    </option>
                                                                                    <option value="card">Cartão</option>
                                                                                    <option value="mpesa">M-Pesa</option>
                                                                                    <option value="emola">E-Mola</option>
                                                                                    <option value="mkesh">M-Kesh</option>
                                                                                </select>
                                                                            </div>
                                                                            <input type="hidden" name="amount_paid"
                                                                                value="{{ $order->total_amount }}">


                                                                            <div class="form-group mb-3">
                                                                                <label
                                                                                    for="notes{{ $order->id }}">Observações</label>
                                                                                <textarea name="notes" id="notes{{ $order->id }}" class="form-control" rows="3"></textarea>
                                                                            </div>

                                                                            <div class="alert alert-info">
                                                                                <div
                                                                                    class="d-flex justify-content-between align-items-center">
                                                                                    <strong>Total a Pagar:</strong>
                                                                                    <span
                                                                                        class="h5 mb-0">{{ number_format($order->total_amount, 2, ',', '.') }}
                                                                                        MZN</span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button"
                                                                                class="btn btn-secondary"
                                                                                data-bs-dismiss="modal">Cancelar</button>
                                                                            <button type="submit"
                                                                                class="btn btn-success">
                                                                                <i class="mdi mdi-check-circle me-1"></i>
                                                                                Confirmar Pagamento
                                                                            </button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <button type="button" class="btn btn-success btn-icon btn-sm"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#paymentModal{{ $order->id }}"
                                                            title="Registrar Pagamento">
                                                            <i class="mdi mdi-cash"></i>
                                                        </button>
                                                    @endif
                                                    @if ($order->status == 'paid')
                                                        <button class="btn btn-info btn-icon btn-sm"
                                                            data-bs-toggle="tooltip" title="Imprimir"
                                                            onclick="printRecibo({{ $order->id }})">
                                                            <i class="mdi mdi-printer"></i>
                                                        </button>
                                                    @endif
                                                    @if ($order->status != 'completed' && $order->status != 'canceled' && $order->status != 'paid')
                                                        <a href="{{ route('orders.edit', $order->id) }}"
                                                            class="btn btn-warning btn-icon btn-sm"
                                                            data-bs-toggle="tooltip" title="Editar">
                                                            <i class="mdi mdi-pencil"></i>
                                                        </a>
                                                        <!-- Substitua os botões existentes por estes -->
                                                        @if ($order->status == 'active' && $order->items->count() > 0)
                                                            <form action="{{ route('orders.complete', $order->id) }}"
                                                                method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit"
                                                                    class="btn btn-success btn-icon btn-sm"
                                                                    data-bs-toggle="tooltip" title="Finalizar"
                                                                    onclick="return confirm('Tem certeza que deseja finalizar este pedido?')">
                                                                    <i class="mdi mdi-check"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                        <form action="{{ route('orders.cancel', $order->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-danger btn-icon btn-sm"
                                                                data-bs-toggle="tooltip" title="Cancelar"
                                                                onclick="return confirm('Tem certeza que deseja cancelar este pedido?')">
                                                                <i class="mdi mdi-close"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">
                                                <i class="mdi mdi-alert-circle-outline mb-2" style="font-size: 2rem;"></i>
                                                <p class="mb-0">Nenhum pedido encontrado</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginação -->
                        <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-3">
                            {{ $orders->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializa todos os tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Adiciona listener para o evento de sucesso do modal
            const paymentModals = document.querySelectorAll('.modal');
            paymentModals.forEach(modal => {
                modal.addEventListener('shown.bs.modal', function() {
                    const form = this.querySelector('form');
                    const submitBtn = form.querySelector('button[type="submit"]');

                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        submitBtn.disabled = true;
                        submitBtn.innerHTML =
                            '<i class="mdi mdi-loading mdi-spin me-1"></i> Processando...';
                        this.submit();
                    });
                });
            });
        });
        document.getElementById('ordersSearch').addEventListener('input', function(e) {
            const searchTerm = e.target.value;
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => {
                window.location.href = '{{ route('orders.index') }}?search=' + encodeURIComponent(
                    searchTerm);
            }, 500);
        });

        function completeOrder(orderId) {
            Swal.fire({
                title: 'Finalizar Pedido',
                text: 'Tem certeza que deseja finalizar este pedido e gerar uma venda?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sim, finalizar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/orders/complete/${orderId}`, {
                            method: 'POST', // Mudando para POST
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    title: 'Sucesso!',
                                    text: data.message,
                                    icon: 'success'
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire('Erro!', data.message, 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Erro:', error);
                            Swal.fire('Erro!', 'Ocorreu um erro ao processar a requisição', 'error');
                        });
                }
            });
        }

        function cancelOrder(orderId) {
            Swal.fire({
                title: 'Cancelar Pedido',
                text: 'Tem certeza que deseja cancelar este pedido?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sim, cancelar',
                cancelButtonText: 'Não',
                confirmButtonColor: '#d33'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/orders/cancel/${orderId}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    title: 'Cancelado!',
                                    text: data.message || 'O pedido foi cancelado com sucesso.',
                                    icon: 'success'
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire('Erro!', data.message || 'Erro ao cancelar o pedido.', 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Erro:', error);
                            Swal.fire('Erro!', 'Ocorreu um erro ao processar a requisição', 'error');
                        });
                }
            });
        }
    </script>
@endsection
