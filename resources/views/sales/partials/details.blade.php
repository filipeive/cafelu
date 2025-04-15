<div class="sale-details">
    <div class="row mb-4">
        <div class="col-md-6">
            <h6 class="text-muted mb-1">ID da Venda</h6>
            <p class="fw-medium">#{{ str_pad($sale->id, 5, '0', STR_PAD_LEFT) }}</p>

            <h6 class="text-muted mb-1 mt-3">Status</h6>
            <p>
                <span class="badge {{ get_status_class_staradmins($sale->status) }} rounded-pill px-3">
                    <i class="mdi mdi-circle-medium me-1"></i>
                    {{ ucfirst($sale->status) }}
                </span>
            </p>
        </div>
        <div class="col-md-6">
            <h6 class="text-muted mb-1">Data da Venda</h6>
            <p class="fw-medium">{{ \Carbon\Carbon::parse($sale->sale_date)->format('d/m/Y H:i') }}</p>

            <h6 class="text-muted mb-1 mt-3">Método de Pagamento</h6>
            <p class="d-flex align-items-center">
                <span class="bg-success bg-opacity-10 p-2 rounded me-2">
                    <i class="mdi {{ get_payment_icon_mdi($sale->payment_method) }} text-success"></i>
                </span>
                <span class="fw-medium">{{ ucfirst($sale->payment_method) }}</span>
            </p>
        </div>
    </div>

    <div class="table-responsive mb-4">
        <table class="table table-bordered table-striped">
            <thead class="table-light">
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
                        <td>{{ $item->product->name }}</td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-end">MZN {{ number_format($item->unit_price, 2) }}</td>
                        <td class="text-end">MZN {{ number_format($item->unit_price * $item->quantity, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" class="text-end">Total:</th>
                    <th class="text-end">MZN {{ number_format($sale->total_amount, 2) }}</th>
                </tr>
            </tfoot>
        </table>
    </div>

    @if ($sale->payment_method == 'mixed')
        <div class="row">
            <div class="col-md-6 offset-md-6">
                <div class="card card-body bg-light mb-0">
                    <h6 class="card-title">Detalhes do Pagamento</h6>
                    <table class="table table-sm mb-0">
                        <tbody>
                            @if ($sale->cash_amount > 0)
                                <tr>
                                    <td>Dinheiro</td>
                                    <td class="text-end">MZN {{ number_format($sale->cash_amount, 2) }}</td>
                                </tr>
                            @endif

                            @if ($sale->card_amount > 0)
                                <tr>
                                    <td>Cartão</td>
                                    <td class="text-end">MZN {{ number_format($sale->card_amount, 2) }}</td>
                                </tr>
                            @endif

                            @if ($sale->mpesa_amount > 0)
                                <tr>
                                    <td>M-Pesa</td>
                                    <td class="text-end">MZN {{ number_format($sale->mpesa_amount, 2) }}</td>
                                </tr>
                            @endif

                            @if ($sale->emola_amount > 0)
                                <tr>
                                    <td>e-Mola</td>
                                    <td class="text-end">MZN {{ number_format($sale->emola_amount, 2) }}</td>
                                </tr>
                            @endif

                            <tr class="table-primary">
                                <th>Total Pago</th>
                                <th class="text-end">MZN
                                    {{ number_format($sale->cash_amount + $sale->card_amount + $sale->mpesa_amount + $sale->emola_amount, 2) }}
                                </th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>
</body>
</html>
