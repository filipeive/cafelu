<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibo de Pedido #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', monospace;
            margin: 0;
            padding: 10px;
            font-size: 14px;
            line-height: 1.4;
            background: white;
        }
        .receipt {
            max-width: 250px;
            margin: 0 auto;
            background: white;
            padding: 15px;
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
        }
        .logo {
            max-width: 80px;
            margin: 0 auto 8px;
            display: block;
        }
        .company-name {
            font-weight: bold;
            font-size: 18px;
            margin: 3px 0;
            color: #4f46e5;
        }
        .company-subtitle {
            font-size: 14px;
            color: #6b7280;
            margin: 3px 0;
        }
        .company-info {
            font-size: 12px;
            margin: 3px 0;
            color: #555;
        }
        .beach-wave {
            height: 3px;
            background: linear-gradient(90deg, #3b82f6, #8b5cf6, #ec4899);
            margin: 8px 0;
            border-radius: 2px;
        }
        .divider {
            border-top: 1px dashed #000;
            margin: 12px 0;
        }
        .order-info p {
            margin: 3px 0;
            font-size: 13px;
        }
        .order-info strong {
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }
        th, td {
            padding: 5px 0;
            text-align: left;
        }
        th {
            font-weight: bold;
        }
        .item-name {
            width: 55%;
            word-wrap: break-word;
        }
        .item-qty {
            width: 20%;
            text-align: center;
        }
        .item-total {
            width: 25%;
            text-align: right;
        }
        .total {
            text-align: right;
            font-weight: bold;
            font-size: 16px;
            margin: 8px 0;
            color: #ef4444;
        }
        .payment-info {
            margin: 8px 0;
            font-size: 13px;
        }
        .payment-info p {
            margin: 3px 0;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            margin-top: 15px;
            color: #555;
        }
        .thank-you {
            font-weight: bold;
            color: #ef4444;
            font-size: 16px;
            margin: 15px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .legal-note {
            font-size: 10px;
            margin: 10px 0;
            padding: 5px;
            background: #f8f9fa;
            border-radius: 3px;
            text-align: center;
        }
        @media print {
            @page {
                margin: 0;
                size: 80mm 297mm;
            }
            body {
                margin: 0;
                -webkit-print-color-adjust: exact;
            }
            .no-print {
                display: none;
            }
            .receipt {
                padding: 10px;
            }
            .total {
                font-size: 16px !important;
                font-weight: bold !important;
                color: black !important;
            }

        }

    </style>
</head>
<body>
    <div class="receipt">
        <div class="header">
            <img src="{{ asset('assets/images/logo-zalala.png') }}" alt="ZALALA BEACH BAR Logo" class="logo">
            <div class="company-name">ZALALA BEACH BAR</div>
            <div class="company-subtitle">BEACH BAR & RESTAURANT</div>
            <div class="beach-wave"></div>
            <div class="company-info">Bairro de Zalala, ER470</div>
            <div class="company-info">Quelimane, Zambézia</div>
            <div class="company-info">Tel: (+258) 846 885 214</div>
            <div class="company-info">NUIT: 110735901</div>
            <div class="company-info">Email: info@zalalabeach.com</div>
        </div>

        <div class="divider"></div>

        <div class="order-info">
            <p><strong>Recibo #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</strong></p>
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
                    <th class="item-name">Item</th>
                    <th class="item-qty">Qtd</th>
                    <th class="item-total">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    @if($item->status !== 'cancelled')
                        <tr>
                            <td class="item-name">{{ Str::limit($item->product->name, 25) }}</td>
                            <td class="item-qty">{{ $item->quantity }}</td>
                            <td class="item-total">MZN {{ number_format($item->total_price, 2, ',', '.') }}</td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>

        <div class="divider"></div>

        <table style="width:100%; font-size:16px; font-weight:bold; margin-top:8px;">
    <tr>
        <td style="text-align:left;">TOTAL:</td>
        <td style="text-align:right;">MZN {{ number_format($order->total_amount, 2, ',', '.') }}</td>
    </tr>
</table>
        <div class="divider"></div>

        @if($order->payment_method)
            <div class="payment-info">
                <p><strong>Forma de Pagamento:</strong></p>
                <p>{{ [
                    'cash' => 'DINHEIRO',
                    'card' => 'CARTÃO',
                    'mpesa' => 'M-PESA',
                    'emola' => 'E-MOLA',
                    'mkesh' => 'M-KESH'
                ][$order->payment_method] ?? 'NÃO INFORMADO' }}</p>
            </div>
        @endif

        <div class="divider"></div>

        <div class="footer">
            <div class="thank-you">✨ OBRIGADO PELA PREFERÊNCIA! ✨</div>
            <div class="legal-note">
                Este documento serve como comprovante de consumo.
                Produtos alimentícios não são trocados.
            </div>
            <p>VOLTE SEMPRE AO ZALALA BEACH BAR!</p>
            <p style="font-size: 11px; margin-top: 5px;">Sistema de Gestão ZALALA v1.0</p>
        </div>
    </div>

    <div class="no-print" style="text-align: center; padding: 15px;">
        <button class="btn btn-primary" onclick="printReceipt()" style="margin: 5px;">
            <i class="mdi mdi-printer me-2"></i> Imprimir Novamente
        </button>
        <button class="btn btn-secondary" onclick="closeWindow()" style="margin: 5px;">
            <i class="mdi mdi-close me-2"></i> Fechar
        </button>
    </div>

    <script>
        function printReceipt() {
            // Tenta abrir a gaveta de dinheiro (se estiver conectada)
            try {
                if (window.SerialPort) {
                    const port = new SerialPort({ path: 'COM1' }); // Ajuste conforme necessário
                    port.write(Buffer.from([0x1B, 0x70, 0x00, 0x19, 0xFA])); // Comando EPSON para abrir gaveta
                }
            } catch (e) {
                console.log('Gaveta não disponível ou erro:', e);
            }
            
            // Imprime o recibo
            window.print();
        }
        
        function closeWindow() {
            window.close();
            // Fallback para redirecionamento se não for possível fechar
            setTimeout(function() {
                window.location.href = '/orders';
            }, 500);
        }
        
        window.onload = function() {
            // Imprime automaticamente após carregar
            setTimeout(function() {
                printReceipt();
            }, 500);
        };
        
        // Evento após impressão
        window.onafterprint = function() {
            // Opcional: fechar automaticamente após impressão
            setTimeout(function() {
                closeWindow();
            }, 2000);
        };
    </script>
</body>
</html>