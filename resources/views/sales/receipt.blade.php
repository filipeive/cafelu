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
            width: 72mm; /* Reduzido para economizar papel */
        }
        .receipt {
            padding: 3mm; /* Reduzido */
        }
        .header {
            text-align: center;
            margin-bottom: 3mm; /* Reduzido */
        }
        .logo {
            max-width: 35mm; /* Reduzido */
            height: auto;
            margin-bottom: 2mm; /* Reduzido */
        }
        .header h1, .header h2 {
            font-size: 12pt; /* Reduzido */
            margin: 1mm 0; /* Reduzido */
        }
        .header p {
            margin: 0.5mm 0; /* Reduzido */
            font-size: 10pt; /* Reduzido */
        }
        .info {
            margin-bottom: 3mm; /* Reduzido */
        }
        .info p {
            margin: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 3mm; /* Reduzido */
        }
        th, td {
            text-align: left;
            padding: 0.5mm; /* Reduzido */
        }
        th {
            border-bottom: 1px solid #000;
        }
        .total {
            text-align: right;
            font-weight: bold;
            margin-top: 2mm; /* Reduzido */
        }
        .footer {
            text-align: center;
            margin-top: 5mm; /* Reduzido */
            font-size: 8pt; /* Reduzido */
        }
        .divider {
            border-top: 1px dashed #000;
            margin: 2mm 0; /* Reduzido */
        }
        @media print {
            body {
                width: 72mm; /* Reduzido */
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

        <div class="info">
            <p><strong>Recibo #{{ str_pad($sale->id, 5, '0', STR_PAD_LEFT) }}</strong></p>
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
            <p style="font-weight: bold; color: #FF0000;">Obrigado Pela Preferência!!!</p>
            <p>Volte Sempre!</p>
            <small style="font-size: 8pt;">Este documento não serve como fatura</small>
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