<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibo de Venda #{{ $sale->id }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', monospace;
            margin: 0;
            padding: 10px;
            font-size: 15px;
        }
        .receipt {
            max-width: 250px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 5px;
        }
        .logo {
            max-width: 100px;
            margin-bottom: 5px;
        }
        .divider {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }
        .items, .totals, .payment-methods, .footer {
            margin-top: 15px;
        }
        .item {
            display: flex;
            justify-content: space-between;
        }
        .totals strong {
            font-weight: bold;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            margin-top: 20px;
        }
        @media print {
            @page {
                margin: 0;
                size: 80mm 297mm;
            }
            body {
                margin: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <<div class="header">
            <img src="/assets/images/Logo.png" alt="Lu & Yosh Catering Logo" class="logo">
            <h2>Lu & Yoshi Catering</h2>
            <h3>Café Lufamina</h3>
            <div class="company-info">
                <p>
                    Av. Samora Machel<br>
                    Cidade de Quelimane<br>
                    Tel: (+258) 878643715 / 844818014<br>
                    Email: cafelufamina@gmail.com<br>
                    NUIT: 1110947722
                </p>
            </div>
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
