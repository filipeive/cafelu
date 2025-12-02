<div class="receipt">
    <div class="header">
        <img src="/assets/images/Logo.png" alt="Lu & Yosh Catering Logo" class="logo">
        <h2 class="company-name">ZALALA BEACH BAR</h2>
        <h3 class="company-subtitle">BEACH BAR & RESTAURANT</h3>
        <p class="company-info">Bairro de Zalala, ER470</p>
        <p class="company-info">Quelimane, Zambézia</p>
        <p class="company-info">Tel: (+258) 846 885 214</p>
        <p class="company-info">NUIT: 110735901</p>
        <p class="company-info">Email: zalalabeachbar@gmail.com</p>
        <p>Data: {{ date('d/m/Y H:i', strtotime($sale->sale_date)) }}</p>
    </div>

    <div class="divider"></div>

    <div class="order-info">
        <p><strong>Recibo #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</strong></p>
        <p><strong>Data:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
        @if($order->table)
            <p><strong>Mesa:</strong> {{ $order->table->number }}</p>
        @endif
        <p><strong>Atend:</strong> {{ Str::limit($order->user->name ?? 'Sistema', 15) }}</p>
    </div>

    <div class="divider"></div>

    <table>
        <thead>
            <tr>
                <th style="width: 50%">Item</th>
                <th style="width: 20%">Qtd</th>
                <th style="width: 30%">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                @if($item->status !== 'cancelled')
                    <tr>
                        <td>{{ Str::limit($item->product->name, 20) }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->total_price, 2, ',', '.') }}</td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>

    <div class="divider"></div>

    <div class="total">
        <p>TOTAL: MZN {{ number_format($order->total_amount, 2, ',', '.') }}</p>
    </div>

    @if($order->payment_method)
        <div class="payment-info">
            <p><strong>Forma de Pagamento:</strong></p>
            <p>{{ [
                'cash' => 'Dinheiro',
                'card' => 'Cartão',
                'mpesa' => 'M-Pesa',
                'emola' => 'E-Mola',
                'mkesh' => 'M-Kesh'
            ][$order->payment_method] ?? 'Não informado' }}</p>
        </div>
    @endif

    <div class="divider"></div>

    <div class="footer">
        <p style="font-weight: bold;">Obrigado pela preferência!</p>
        <p>Volte Sempre!</p>
        <small>Este documento não serve como fatura</small>
    </div>
</div>