<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibo de Venda #{{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', monospace;
            margin: 0;
            padding: 10px;
            font-size: 14px;
            line-height: 1.4;
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
        .items, .totals, .payment-methods, .footer {
            margin-top: 15px;
        }
        .item-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .item-name {
            width: 120px;
            word-wrap: break-word;
        }
        .item-qty, .item-price, .item-total {
            text-align: right;
            min-width: 40px;
        }
        .totals strong {
            font-weight: bold;
            display: block;
            text-align: right;
            margin-top: 8px;
            font-size: 16px;
        }
        .payment-method {
            margin: 3px 0;
            padding: 2px 0;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            margin-top: 20px;
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
        .return-policy {
            font-size: 11px;
            margin: 10px 0;
            padding: 5px;
            background: #f8f9fa;
            border-radius: 3px;
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
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="header">
            <img src="{{ asset('assets/images/logo-zalala.png') }}" alt="ZALALA BEACH BAR Logo" class="logo">
            <h2 style="margin: 5px 0; font-size: 18px; font-weight: bold;">ZALALA BEACH BAR</h2>
            <h3 style="margin: 3px 0; font-size: 14px; color: #6b7280;">BEACH BAR & RESTAURANT</h3>
            <div class="beach-wave"></div>
            <div class="company-info" style="font-size: 12px; margin: 8px 0;">
                <p style="margin: 3px 0;">
                    Bairro de Zalala, ER470<br>
                    Quelimane, Zambézia<br>
                    Tel: (+258) 846 885 214<br>
                    NUIT: 110735901
                </p>
            </div>
            <p style="margin: 8px 0; font-weight: bold;">Recibo: #{{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}</p>
            <p style="margin: 3px 0;">Data: {{ date('d/m/Y H:i', strtotime($sale->sale_date)) }}</p>
            <p style="margin: 3px 0;">Atendente: {{ $sale->user->name ?? 'Não identificado' }}</p>
            {{-- @if($sale->table)
                <p style="margin: 3px 0;">Mesa: {{ $sale->table->number }}</p>
            @endif --}}
        </div>
        
        <div class="divider"></div>
        
        <div class="items">
            @foreach($items as $item)
                <div class="item-row">
                    <div class="item-name">{{ $item->name }}</div>
                    <div class="item-qty">{{ $item->quantity }}x</div>
                    <div class="item-price">MZN {{ number_format($item->unit_price, 2, ',', '.') }}</div>
                    <div class="item-total">MZN {{ number_format($item->unit_price * $item->quantity, 2, ',', '.') }}</div>
                </div>
            @endforeach
        </div>
        
        <div class="divider"></div>
        
        <div class="totals">
           {{--  <div class="item-row">
                <strong>Subtotal:</strong>
                <span>MZN {{ number_format($sale->total_amount - $sale->tax_amount, 2, ',', '.') }}</span>
            </div>
            @if($sale->tax_amount > 0)
            <div class="item-row">
                <strong>IVA ({{ $sale->tax_rate }}%):</strong>
                <span>MZN {{ number_format($sale->tax_amount, 2, ',', '.') }}</span>
            </div>
            @endif --}}
            <strong>TOTAL: MZN {{ number_format($sale->total_amount, 2, ',', '.') }}</strong>
        </div>
        
        <div class="divider"></div>
        
        <div class="payment-methods">
            <h6 style="margin: 8px 0 5px; font-weight: bold; font-size: 14px;">MÉTODO DE PAGAMENTO:</h6>
            @if($sale->payment_method == 'multiple')
                <div class="payment-method">MÚLTIPLOS MÉTODOS</div>
            @else
                <div class="payment-method">{{ strtoupper($sale->payment_method) }}</div>
            @endif
            
            @php
                $totalPaid = 0;
            @endphp
            
            @if($sale->cash_amount > 0)
                <div class="payment-method">DINHEIRO: MZN {{ number_format($sale->cash_amount, 2, ',', '.') }}</div>
                @php $totalPaid += $sale->cash_amount; @endphp
            @endif
            @if($sale->card_amount > 0)
                <div class="payment-method">CARTÃO: MZN {{ number_format($sale->card_amount, 2, ',', '.') }}</div>
                @php $totalPaid += $sale->card_amount; @endphp
            @endif
            @if($sale->mpesa_amount > 0)
                <div class="payment-method">M-PESA: MZN {{ number_format($sale->mpesa_amount, 2, ',', '.') }}</div>
                @php $totalPaid += $sale->mpesa_amount; @endphp
            @endif
            @if($sale->emola_amount > 0)
                <div class="payment-method">E-MOLA: MZN {{ number_format($sale->emola_amount, 2, ',', '.') }}</div>
                @php $totalPaid += $sale->emola_amount; @endphp
            @endif
            {{-- @if($sale->mkesh_amount > 0)
                <div class="payment-method">M-KESH: MZN {{ number_format($sale->mkesh_amount, 2, ',', '.') }}</div>
                @php $totalPaid += $sale->mkesh_amount; @endphp
            @endif --}}
            
            @if($sale->cash_amount > 0 && $sale->cash_amount > $sale->total_amount)
                <div class="payment-method" style="color: #ef4444; font-weight: bold;">
                    TROCO: MZN {{ number_format($sale->cash_amount - $sale->total_amount, 2, ',', '.') }}
                </div>
            @endif
        </div>
        
        <div class="divider"></div>
        
        <div class="footer">
            <div class="thank-you">✨ OBRIGADO PELA PREFERÊNCIA! ✨</div>
            <div class="return-policy">
                Este recibo serve como comprovante de compra.
                Produtos alimentícios não são trocados.
            </div>
            <p style="margin: 10px 0; font-weight: bold;">VOLTE SEMPRE AO ZALALA BEACH BAR!</p>
            <p style="font-size: 11px; margin: 5px 0;">Sistema de Gestão ZALALA v1.0</p>
        </div>
    </div>

    <div class="no-print" style="text-align: center; padding: 15px;">
        <button class="btn btn-primary" onclick="closeAndReturn()" style="margin: 5px;">
            <i class="mdi mdi-printer me-2"></i> Imprimir Novamente
        </button>
        <button class="btn btn-secondary" onclick="closeWindow()" style="margin: 5px;">
            <i class="mdi mdi-close me-2"></i> Fechar
        </button>
    </div>

    <script>
        function closeAndReturn() {
            window.print();
        }
        
        function closeWindow() {
            window.close();
            // Fallback para redirecionamento se não for possível fechar
            setTimeout(function() {
                window.location.href = '/pos';
            }, 500);
            closeAndReturn();
        }
        
        window.onload = function() {
            // Adiciona um pequeno delay antes de imprimir para garantir que tudo foi carregado
            setTimeout(function() {
                window.print();
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