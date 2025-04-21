@extends('layouts.app')

@section('title', 'Detalhes da Venda')

@section('styles')
    <style>
        .sale-header {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .sale-header:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
        }

        .status-badge {
            font-size: 0.9rem;
            padding: 0.6rem 1.2rem;
            font-weight: 500;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .payment-method-badge {
            background: rgba(25, 135, 84, 0.15);
            padding: 1rem;
            border-radius: 50%;
            margin-right: 1rem;
            box-shadow: 0 2px 8px rgba(25, 135, 84, 0.1);
            transition: all 0.3s ease;
        }

        .payment-method-badge:hover {
            transform: scale(1.1);
        }

        .sale-actions {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
        }

        .table-items {
            border-radius: 12px;
            overflow: hidden;
        }

        .table-items th {
            background: #f8f9fa;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            padding: 1rem;
        }

        .table-items td {
            padding: 1rem;
            vertical-align: middle;
        }

        .table-items tbody tr {
            transition: all 0.2s ease;
        }

        .table-items tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.02);
            transform: scale(1.001);
        }

        .payment-details {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: 15px;
            padding: 1.8rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .print-btn {
            transition: all 0.3s ease;
            padding: 0.8rem 1.5rem;
            font-weight: 500;
            letter-spacing: 0.5px;
        }

        .print-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.15);
        }

        .card {
            border: none;
            border-radius: 20px;
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08) !important;
        }

        .product-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .product-image:hover {
            transform: scale(1.1);
        }

        .btn {
            border-radius: 12px;
            padding: 0.8rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .mdi {
            font-size: 1.2rem;
        }
    </style>
@endsection

@section('content')
    <div class="container-wrapper py-4">
        <div class="card">
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-12">

                        <div class="sale-header">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <h4 class="text-primary mb-3 fw-bold badge badge-primary" style="font-size: 1.5rem;">
                                        Venda #{{ str_pad($sale->id, 5, '0', STR_PAD_LEFT) }}
                                    </h4><br>
                                    <div class="d-flex align-items-center">
                                        <span
                                            class="status-badge {{ get_status_class_staradmins($sale->status) }} rounded-pill">
                                            <i class="mdi mdi-circle-medium"></i>
                                            {{ ucfirst($sale->status) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6 text-md-end">
                                    <p class="text-muted mb-2">Data da Venda</p>
                                    <h5 class="fw-bold">
                                        {{ \Carbon\Carbon::parse($sale->sale_date)->format('d/m/Y H:i') }}</h5>
                                    <div class="mt-3 d-flex align-items-center justify-content-md-end">
                                        <span class="payment-method-badge">
                                            <i
                                                class="mdi {{ get_payment_icon_mdi($sale->payment_method) }} text-success"></i>
                                        </span>
                                        <span class="h6 mb-0 fw-bold">{{ ucfirst($sale->payment_method) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="mb-4 fw-bold">Itens da Venda</h5>
                                <div class="table-responsive">
                                    <table class="table table-hover table-items mb-0">
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
                                                                    class="product-image me-3"
                                                                    alt="{{ $item->product->name }}">
                                                            @endif
                                                            <span class="fw-medium">{{ $item->product->name }}</span>
                                                        </div>
                                                    </td>
                                                    <td class="text-center fw-medium">{{ $item->quantity }}</td>
                                                    <td class="text-end">MZN {{ number_format($item->unit_price, 2) }}</td>
                                                    <td class="text-end fw-bold">MZN
                                                        {{ number_format($item->unit_price * $item->quantity, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot class="table-light">
                                            <tr>
                                                <th colspan="3" class="text-end">Total:</th>
                                                <th class="text-end h5 mb-0 text-primary">MZN
                                                    {{ number_format($sale->total_amount, 2) }}</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if ($sale->payment_method == 'mixed')
                        <div class="col-md-6 offset-md-3">
                            <div class="card shadow-sm">
                                <div class="card-body payment-details">
                                    <h6 class="mb-4 fw-bold">Detalhes do Pagamento</h6>
                                    <table class="table table-sm mb-0">
                                        <tbody>
                                            @if ($sale->cash_amount > 0)
                                                <tr>
                                                    <td><i class="mdi mdi-cash me-2 text-success"></i>Dinheiro</td>
                                                    <td class="text-end fw-medium">MZN
                                                        {{ number_format($sale->cash_amount, 2) }}</td>
                                                </tr>
                                            @endif
                                            @if ($sale->card_amount > 0)
                                                <tr>
                                                    <td><i class="mdi mdi-credit-card me-2 text-primary"></i>Cartão</td>
                                                    <td class="text-end fw-medium">MZN
                                                        {{ number_format($sale->card_amount, 2) }}</td>
                                                </tr>
                                            @endif
                                            @if ($sale->mpesa_amount > 0)
                                                <tr>
                                                    <td><i class="mdi mdi-phone me-2 text-warning"></i>M-Pesa</td>
                                                    <td class="text-end fw-medium">MZN
                                                        {{ number_format($sale->mpesa_amount, 2) }}</td>
                                                </tr>
                                            @endif
                                            @if ($sale->emola_amount > 0)
                                                <tr>
                                                    <td><i class="mdi mdi-wallet me-2 text-info"></i>e-Mola</td>
                                                    <td class="text-end fw-medium">MZN
                                                        {{ number_format($sale->emola_amount, 2) }}</td>
                                                </tr>
                                            @endif
                                            <tr class="border-top">
                                                <th>Total Pago</th>
                                                <th class="text-end text-success">MZN
                                                    {{ number_format($sale->getTotalPayments(), 2) }}</th>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('sales.index') }}" class="btn btn-secondary">
                                <i class="mdi mdi-arrow-left me-2"></i>Voltar para Vendas
                            </a>
                            <button type="button" class="btn btn-primary print-btn" onclick="printReceipt('normal')">
                                <i class="mdi mdi-printer me-2"></i>Imprimir Recibo
                            </button>
                        </div>
                    </div>
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
