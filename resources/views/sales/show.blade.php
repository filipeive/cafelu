@extends('layouts.app')

@section('title', 'Detalhes da Venda')

@section('styles')
    <style>
        .sale-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .status-badge {
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
        }

        .payment-method-badge {
            background: rgba(25, 135, 84, 0.1);
            padding: 0.75rem;
            border-radius: 50%;
            margin-right: 0.75rem;
        }

        .sale-actions {
            position: absolute;
            top: 1rem;
            right: 1rem;
        }

        .table-items th {
            background: #f8f9fa;
            font-weight: 600;
        }

        .payment-details {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1.5rem;
        }

        .print-btn {
            transition: all 0.3s ease;
        }

        .print-btn:hover {
            transform: translateY(-2px);
        }
    </style>
@endsection

@section('content')
    <div class="container-wrapper">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="sale-header shadow-sm">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <h4 class="text-primary mb-3">
                                        Venda #{{ str_pad($sale->id, 5, '0', STR_PAD_LEFT) }}
                                    </h4>
                                    <div class="d-flex align-items-center mb-3">
                                        <span
                                            class="status-badge {{ get_status_class_staradmins($sale->status) }} rounded-pill">
                                            <i class="mdi mdi-circle-medium"></i>
                                            {{ ucfirst($sale->status) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6 text-md-end">
                                    <p class="text-muted mb-1">Data da Venda</p>
                                    <h5>{{ \Carbon\Carbon::parse($sale->sale_date)->format('d/m/Y H:i') }}</h5>
                                    <div class="mt-3 d-flex align-items-center justify-content-md-end">
                                        <span class="payment-method-badge">
                                            <i
                                                class="mdi {{ get_payment_icon_mdi($sale->payment_method) }} text-success"></i>
                                        </span>
                                        <span class="h6 mb-0">{{ ucfirst($sale->payment_method) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Tabela de Itens -->
            <div class="col-12 mt-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="mb-4">Itens da Venda</h5>
                        <div class="table-responsive">
                            <table class="table table-hover table-items">
                                <thead>
                                    <tr>
                                        <th>Produto</th>
                                        <th class="text-center">Quantidade</th>
                                        <th class="text-end">Preço Unit.</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sale->saleItems as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if ($item->product->image_path)
                                                        <img src="{{ Storage::url($item->product->image_path) }}"
                                                            class="rounded me-2"
                                                            style="width: 40px; height: 40px; object-fit: cover;">
                                                    @endif
                                                    <span>{{ $item->product->name }}</span>
                                                </div>
                                            </td>
                                            <td class="text-center">{{ $item->quantity }}</td>
                                            <td class="text-end">MZN {{ number_format($item->unit_price, 2) }}</td>
                                            <td class="text-end">MZN
                                                {{ number_format($item->unit_price * $item->quantity, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <th colspan="3" class="text-end">Total:</th>
                                        <th class="text-end h5 mb-0">MZN {{ number_format($sale->total_amount, 2) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detalhes do Pagamento para Método Misto -->
            @if ($sale->payment_method == 'mixed')
                <div class="col-md-6 offset-md-3 mt-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h6 class="mb-3">Detalhes do Pagamento</h6>
                            <table class="table table-sm mb-0">
                                <tbody>
                                    @if ($sale->cash_amount > 0)
                                        <tr>
                                            <td><i class="mdi mdi-cash me-2"></i>Dinheiro</td>
                                            <td class="text-end">MZN {{ number_format($sale->cash_amount, 2) }}</td>
                                        </tr>
                                    @endif
                                    @if ($sale->card_amount > 0)
                                        <tr>
                                            <td><i class="mdi mdi-credit-card me-2"></i>Cartão</td>
                                            <td class="text-end">MZN {{ number_format($sale->card_amount, 2) }}</td>
                                        </tr>
                                    @endif
                                    @if ($sale->mpesa_amount > 0)
                                        <tr>
                                            <td><i class="mdi mdi-phone me-2"></i>M-Pesa</td>
                                            <td class="text-end">MZN {{ number_format($sale->mpesa_amount, 2) }}</td>
                                        </tr>
                                    @endif
                                    @if ($sale->emola_amount > 0)
                                        <tr>
                                            <td><i class="mdi mdi-wallet me-2"></i>e-Mola</td>
                                            <td class="text-end">MZN {{ number_format($sale->emola_amount, 2) }}</td>
                                        </tr>
                                    @endif
                                    <tr class="border-top">
                                        <th>Total Pago</th>
                                        <th class="text-end">MZN {{ number_format($sale->getTotalPayments(), 2) }}</th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Botões de Ação -->
            <div class="col-12 mt-4">
                <div class="d-flex justify-content-between">
                    <a href="{{ route('sales.index') }}" class="btn btn-secondary">
                        <i class="mdi mdi-arrow-left me-1"></i> Voltar para Vendas
                    </a>
                    <button type="button" class="btn btn-primary print-btn" onclick="printReceipt('normal')">
                        <i class="mdi mdi-printer me-1"></i> Imprimir Recibo
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function printReceipt(type) {
                const url = `{{ route('sales.print', $sale->id) }}?type=${type}`;
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = url;
                form.target = '_blank';

                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = '{{ csrf_token() }}';

                form.appendChild(csrfInput);
                document.body.appendChild(form);
                form.submit();
                document.body.removeChild(form);
            }
        </script>
    @endpush
@endsection
