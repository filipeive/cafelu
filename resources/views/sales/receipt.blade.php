<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibo da Venda #{{ $sale->id }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12pt;
            margin: 0;
            padding: 0;
            width: 80mm;
        }
        .receipt {
            padding: 5mm;
        }
        .header {
            text-align: center;
            margin-bottom: 5mm;
        }
        .header h1 {
            font-size: 12pt;
            margin: 0;
        }
        .header p {
            margin: 2mm 0;
        }
        .info {
            margin-bottom: 5mm;
        }
        .info p {
            margin: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5mm;
        }
        th, td {
            text-align: left;
            padding: 1mm;
        }
        th {
            border-bottom: 1px solid #000;
        }
        .total {
            text-align: right;
            font-weight: bold;
            margin-top: 3mm;
        }
        .footer {
            text-align: center;
            margin-top: 10mm;
            font-size: 9pt;
        }
        .divider {
            border-top: 1px dashed #000;
            margin: 3mm 0;
        }
        @media print {
            body {
                width: 80mm;
                margin: 0;
                padding: 0;
            }
            .print-button {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="header">
            <h1>Lucafé</h1>
            <p>Av. Principal, 123 - Maputo</p>
            <p>Tel: (21) 123-4567</p>
            <p>NUIT: 12345678</p>
        </div>

        <div class="divider"></div>

        <div class="info">
            <p><strong>Venda #{{ str_pad($sale->id, 5, '0', STR_PAD_LEFT) }}</strong></p>
            <p><strong>Data:</strong> {{ \Carbon\Carbon::parse($sale->sale_date)->format('d/m/Y H:i') }}</p>
            @if($sale->table)
                <p><strong>Mesa:</strong> {{ $sale->table->number }}</p>
            @endif
            <p><strong>Atendente:</strong> {{ $sale->user->name ?? 'Sistema' }}</p>
        </div>

        <div class="divider"></div>

        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Qtd</th>
                    <th>Preço</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sale->saleItems as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->unit_price, 2, ',', '.') }}</td>
                        <td>{{ number_format($item->unit_price * $item->quantity, 2, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center;">Nenhum item encontrado</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="divider"></div>

        <div class="total">
            <p>SUBTOTAL: {{ number_format($sale->total_amount, 2, ',', '.') }} MZN</p>
            <p>TOTAL: {{ number_format($sale->total_amount, 2, ',', '.') }} MZN</p>
        </div>

        @if($sale->payment_method)
            <div class="info">
                <p><strong>Forma de Pagamento:</strong> 
                    @switch($sale->payment_method)
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
                            {{ ucfirst($sale->payment_method) }}
                    @endswitch
                </p>
            </div>
        @endif

        <div class="divider"></div>

        <div class="footer">
            <p>Obrigado pela preferência!</p>
            <p>Volte Sempre!</p>
            <p>{{ now()->format('d/m/Y H:i') }}</p>
        </div>
    </div>

    <div class="print-button" style="text-align: center; margin: 20px;">
        <button onclick="window.print()">Imprimir Recibo</button>
        <button onclick="closeAndReturn()">Fechar e Voltar</button>
    </div>

    <script>
        function closeAndReturn() {
            setTimeout(function() {
                window.close();
            }, 100);
        }

        window.onload = function() {
            window.print();
            setTimeout(closeAndReturn, 1000);
        };
    </script>
</body>
</html>