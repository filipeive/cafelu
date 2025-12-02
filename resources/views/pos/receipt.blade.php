<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibo de Venda #{{ $sale->id }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            margin: 0;
            padding: 6mm;
            font-size: 12px;
            color: #000;
            -webkit-print-color-adjust: exact;
            box-sizing: border-box;
        }
        .receipt {
            width: 80mm;
            max-width: 80mm;
            padding: 20px;
            margin: 0 auto;
            background: #fff;
        }
        .header {
            text-align: center;
            margin-bottom: 4px;
        }
        .logo {
            max-width: 60px;
            height: auto;
            display: block;
            margin: 0 auto 4px;
        }
        .company-name {
            font-size: 14px;
            margin: 0;
            font-weight: bold;
            letter-spacing: 1px;
        }
        .company-subtitle {
            font-size: 11px;
            margin: 0;
        }
        .company-info {
            font-size: 10px;
            margin: 0;
        }
        .divider {
            border-top: 1px dashed #000;
            margin: 6px 0;
        }
        .items, .totals, .payment-methods, .footer {
            margin-top: 6px;
        }
        .items table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }
        .items th, .items td {
            padding: 2px 0;
            vertical-align: top;
        }
        .items th {
            font-weight: 600;
            text-align: left;
        }
        .items td:nth-child(2),
        .items td:nth-child(3),
        .items td:nth-child(4) {
            text-align: right;
        }
        .items td {
            word-break: break-word;
        }
        .item {
            display: flex;
            justify-content: space-between;
        }
        .totals .item {
            padding-top: 4px;
            border-top: 1px solid transparent;
            font-size: 12px;
        }
        .footer {
            text-align: center;
            font-size: 11px;
            margin-top: 8px;
        }
        .no-print {
            text-align: center;
            margin-top: 8px;
        }
        @media print {
            @page {
            size: 80mm auto;
            margin: 3mm;
            }
            body {
            margin: 0;
            padding: 0;
            }
            .receipt {
            width: 80mm;
            margin: 0;
            padding: 0;
            }
            .no-print {
            display: none !important;
            }
            img {
            -webkit-print-color-adjust: exact;
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
        
        <div class="items">
            <table style="width: 100%;">
                <thead>
                    <tr>
                        <th style="text-align: left;">Item</th>
                        <th style="text-align: right;">Qtd</th>
                        <th style="text-align: right;">Preço</th>
                        <th style="text-align: right;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td style="text-align: right;">{{ $item->quantity }}</td>
                            <td style="text-align: right;">MZN {{ number_format($item->unit_price, 2) }}</td>
                            <td style="text-align: right;">MZN {{ number_format($item->unit_price * $item->quantity, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="totals">
            <div class="item">
                <strong>Total:</strong>
                <span>MZN {{ number_format($sale->total_amount, 2) }}</span>
            </div>
        </div>
        
        <div class="payment-methods">
            <h6>Método de Pagamento:</h6>
            @if($sale->payment_method == 'multiple')
                <div class="item">Múltiplos métodos</div>
            @else
                <div class="item">{{ ucfirst($sale->payment_method) }}</div>
            @endif
            @if($sale->cash_amount > 0)
                <div class="item">Dinheiro: <span>MZN {{ number_format($sale->cash_amount, 2) }}</span></div>
            @endif
            @if($sale->card_amount > 0)
                <div class="item">Cartão: <span>MZN {{ number_format($sale->card_amount, 2) }}</span></div>
            @endif
            @if($sale->mpesa_amount > 0)
                <div class="item">M-Pesa: <span>MZN {{ number_format($sale->mpesa_amount, 2) }}</span></div>
            @endif
            @if($sale->emola_amount > 0)
                <div class="item">E-mola: <span>MZN {{ number_format($sale->emola_amount, 2) }}</span></div>
            @endif
            {{-- @if($sale->cash_amount > 0 && $sale->cash_amount > $sale->total_amount)
                <div class="item">Troco: <span>MZN {{ number_format($sale->cash_amount - $sale->total_amount, 2) }}</span></div>
            @endif --}}
        </div>
        
        <div class="divider"></div>
        
        <div class="footer">
            <p style="color: red; font-weight: bold;">Obrigado Pela Preferencia!!!</p>
            <p>Volte Sempre!</p>
        </div>
        <div class="no-print">
            <button class="btn btn-primary" onclick="closeAndReturn()">Fechar e Voltar</button>
    </div>

    <script>
       
        function closeAndReturn() {
            setTimeout(function() {
                    window.close(); // Fecha a aba
                }, 80); // 1 segundo
        }
        window.onload = function() {
            window.print(); // Abre a caixa de diálogo de impressão
            setTimeout(closeAndReturn, 1000);
         };
    </script>
</body>
</html>
