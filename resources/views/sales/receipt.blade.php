<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibo da Venda #{{ $sale->id }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 10pt;
            margin: 0;
            padding: 0;
            width: 80mm;
            -webkit-print-color-adjust: exact;
            box-sizing: border-box;
        }
        .receipt {
            padding: 3mm;
            width: calc(80mm - 6mm);
            box-sizing: border-box;
        }
        .header {
            text-align: center;
            margin-bottom: 2mm;
        }
        .logo {
            max-width: 30mm;
            height: auto;
            display: block;
            margin: 0 auto 1mm;
        }
        .header .company-name {
            font-size: 12pt;
            margin: 1mm 0;
        }
        .header .company-subtitle {
            font-size: 9pt;
            margin: 0;
        }
        .header p {
            margin: 0.4mm 0;
            font-size: 8pt;
        }
        .info {
            margin-bottom: 2mm;
            font-size: 9pt;
        }
        .info p {
            margin: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2mm;
            font-size: 9pt;
        }
        th, td {
            padding: 0.8mm 0;
            vertical-align: top;
        }
        th {
            border-bottom: 1px solid #000;
            font-weight: 700;
            font-size: 9pt;
        }
        th:nth-child(2), td:nth-child(2) {
            text-align: center;
            width: 12%;
        }
        th:nth-child(3), td:nth-child(3) {
            text-align: right;
            width: 18%;
        }
        th:nth-child(4), td:nth-child(4) {
            text-align: right;
            width: 20%;
        }
        .total {
            text-align: right;
            font-weight: bold;
            margin-top: 2mm;
            font-size: 10pt;
        }
        .footer {
            text-align: center;
            margin-top: 4mm;
            font-size: 8pt;
        }
        .divider {
            border-top: 1px dashed #000;
            margin: 2mm 0;
        }
        .print-button {
            text-align: center;
            margin: 12px;
        }
        @media print {
            body {
                width: 80mm;
                margin: 0;
                padding: 0;
            }
            .receipt {
                padding: 2.5mm;
                width: calc(80mm - 5mm);
            }
            .print-button {
                display: none;
            }
            @page {
                size: 80mm auto;
                margin: 0;
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
            <!--<p>SUBTOTAL: {{-- number_format($sale->total_amount, 2, ',', '.') --}} MZN</p>-->
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