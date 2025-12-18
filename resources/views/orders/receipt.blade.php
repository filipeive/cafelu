<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibo #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</title>
    <style>
        @page {
            margin: 0;
            size: 80mm auto; /* Thermal printer width */
        }
        
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            margin: 0;
            padding: 10px;
            width: 80mm;
            max-width: 80mm;
            background-color: #fff;
            color: #000;
        }

        .receipt {
            width: 100%;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .logo {
            max-width: 60px;
            margin-bottom: 5px;
            filter: grayscale(100%); /* Thermal printers are usually B&W */
        }

        .company-name {
            font-size: 16px;
            font-weight: bold;
            margin: 5px 0;
            text-transform: uppercase;
        }

        .company-subtitle {
            font-size: 12px;
            font-weight: normal;
            margin: 2px 0;
        }

        .company-info {
            font-size: 10px;
            margin: 1px 0;
        }

        .divider {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }

        .order-info {
            margin-bottom: 10px;
        }

        .order-info p {
            margin: 2px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        th {
            text-align: left;
            border-bottom: 1px solid #000;
            padding: 2px 0;
            font-size: 11px;
        }

        td {
            padding: 4px 0;
            vertical-align: top;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .total {
            text-align: right;
            font-size: 14px;
            font-weight: bold;
            margin: 10px 0;
        }

        .payment-info {
            margin-top: 10px;
            font-size: 11px;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 10px;
        }

        /* Hide everything else when printing */
        @media print {
            body {
                width: auto;
                margin: 0;
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="header">
            <!-- <img src="/assets/images/Logo.png" alt="Logo" class="logo"> -->
            <h2 class="company-name">ZALALA BEACH BAR</h2>
            <h3 class="company-subtitle">BEACH BAR & RESTAURANT</h3>
            <p class="company-info">Bairro de Zalala, ER470</p>
            <p class="company-info">Quelimane, Zambézia</p>
            <p class="company-info">Tel: (+258) 846 885 214</p>
            <p class="company-info">NUIT: 110735901</p>
            <p class="company-info">Email: zalalabeachbar@gmail.com</p>
        </div>

        <div class="divider"></div>

        <div class="order-info">
            <p><strong>RECIBO #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</strong></p>
            <p>Data: {{ $order->created_at->format('d/m/Y H:i') }}</p>
            @if($order->table)
                <p>Mesa: {{ $order->table->number }}</p>
            @endif
            <p>Atendente: {{ Str::limit($order->user->name ?? 'Sistema', 20) }}</p>
            @if($order->customer_name)
                <p>Cliente: {{ Str::limit($order->customer_name, 20) }}</p>
            @endif
        </div>

        <div class="divider"></div>

        <table>
            <thead>
                <tr>
                    <th style="width: 50%">Item</th>
                    <th style="width: 15%" class="text-center">Qtd</th>
                    <th style="width: 35%" class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    @if($item->status !== 'cancelled')
                        <tr>
                            <td>{{ Str::limit($item->product->name, 20) }}</td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-right">{{ number_format($item->total_price, 2, ',', '.') }}</td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>

        <div class="divider"></div>

        <div class="total">
            TOTAL: MZN {{ number_format($order->total_amount, 2, ',', '.') }}
        </div>

        @if($order->payment_method)
            <div class="payment-info">
                <p><strong>Forma de Pagamento:</strong></p>
                <p>
                    @switch($order->payment_method)
                        @case('cash') Dinheiro @break
                        @case('card') Cartão @break
                        @case('mpesa') M-Pesa @break
                        @case('emola') E-Mola @break
                        @case('mkesh') M-Kesh @break
                        @default {{ $order->payment_method }}
                    @endswitch
                </p>
                @if($order->payment_method == 'cash' && isset($order->cash_amount))
                    <p>Valor Entregue: {{ number_format($order->cash_amount, 2, ',', '.') }}</p>
                    <p>Troco: {{ number_format($order->cash_amount - $order->total_amount, 2, ',', '.') }}</p>
                @endif
            </div>
        @endif

        <div class="divider"></div>

        <div class="footer">
            <p><strong>Obrigado pela preferência!</strong></p>
            <p>Volte Sempre!</p>
            <p style="margin-top: 5px;">Processado por Cafelu</p>
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
            // Optional: close window after print
            // window.onafterprint = function() { window.close(); };
        }
    </script>
</body>
</html>