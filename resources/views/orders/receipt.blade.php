<div class="receipt">
    <div class="header">
        <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" class="logo">
        <div class="company-name">Lu & Yoshi Catering</div>
        <div class="company-name">Café Lufamina</div>
        <div class="company-info">Av. Samora Machel</div>
        <div class="company-info">Cidade de Quelimane</div>
        <div class="company-info">Tel: (+258) 878643715 / 844818014</div>
        <div class="company-info">NUIT: 1110947722</div>
        <div class="company-info">Email: cafelufamina@gmail.com</div>
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
            <small>Este documento não serve como fatura</small>
        </div>
    </div>

    <script>
        window.onload = function() {
            // Abre a gaveta de dinheiro antes de imprimir (se suportado)
            try {
                const port = new SerialPort({ path: 'COM1' }); // Ajuste a porta conforme necessário
                port.write(Buffer.from([0x1B, 0x70, 0x00, 0x19, 0xFA])); // Comando para abrir gaveta
            } catch (e) {
                console.log('Gaveta não disponível:', e);
            }

            // Imprime após um pequeno delay
            setTimeout(() => {
                window.print();
            }, 500);

            // Retorna à página anterior após a impressão
            window.onafterprint = function() {
                setTimeout(() => {
                    window.location.href = '/orders';
                }, 1000);
            };
        };
    </script>
</body>
</html>