<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibo #{{ $order->id }}</title>
    <style>
        @page {
            size: 80mm auto; /* Largura fixa de 80mm, altura automática */
            margin: 0;
        }

        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 10pt;
            margin: 0;
            padding: 0;
            width: 80mm;
        }

        .receipt {
            padding: 3mm;
            width: 74mm; /* 80mm - 6mm de padding */
        }

        .header {
            text-align: center;
            margin-bottom: 3mm;
        }

        .logo {
            max-width: 40mm;
            height: auto;
            margin-bottom: 2mm;
        }

        .info {
            margin-bottom: 3mm;
        }

        .info p {
            margin: 1mm 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 3mm 0;
        }

        th, td {
            padding: 1mm;
            text-align: left;
        }

        td:nth-child(2) {
            text-align: center;
        }

        td:last-child {
            text-align: right;
        }

        .divider {
            border-top: 1px dashed #000;
            margin: 2mm 0;
        }

        .total {
            text-align: right;
            font-weight: bold;
            font-size: 12pt;
            margin: 3mm 0;
        }

        .footer {
            text-align: center;
            margin-top: 3mm;
        }

        @media print {
            @page {
                margin: 0;
            }

            body {
                width: 80mm;
            }

            .receipt {
                page-break-after: always;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="header">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" class="logo">
            <h2 style="margin: 1mm 0; font-size: 12pt;">Lu & Yoshi Catering</h2>
            <h3 style="margin: 1mm 0; font-size: 11pt;">Café Lufamina</h3>
            <p style="font-size: 9pt;">Av. Samora Machel</p>
            <p style="font-size: 9pt;">Cidade de Quelimane</p>
            <p style="font-size: 9pt;">Tel: (+258) 878643715 / 844818014</p>
            <p style="font-size: 9pt;">NUIT: 1110947722</p>
        </div>

        <div class="divider"></div>

        <div class="info">
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
            <div class="info" style="text-align: center;">
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