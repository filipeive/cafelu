<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibo da Venda #{{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}</title>
    <style>
        body {
            font-family: 'Arial', monospace;
            font-size: 12pt;
            margin: 0;
            padding: 0;
            width: 72mm;
            background: white;
        }
        .receipt {
            padding: 3mm;
            background: white;
        }
        .header {
            text-align: center;
            margin-bottom: 3mm;
        }
        .logo {
            max-width: 35mm;
            height: auto;
            margin: 0 auto 2mm;
            display: block;
        }
        .company-name {
            font-weight: bold;
            font-size: 14pt;
            margin: 1mm 0;
            color: #4f46e5;
        }
        .company-subtitle {
            font-size: 11pt;
            margin: 1mm 0;
            color: #6b7280;
        }
        .company-info {
            margin: 0.5mm 0;
            font-size: 9pt;
            color: #555;
        }
        .beach-wave {
            height: 2px;
            background: linear-gradient(90deg, #3b82f6, #8b5cf6, #ec4899);
            margin: 2mm 0;
            border-radius: 1px;
        }
        .info {
            margin-bottom: 3mm;
            font-size: 10pt;
        }
        .info p {
            margin: 0.5mm 0;
        }
        .info strong {
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 3mm;
            font-size: 10pt;
        }
        th, td {
            text-align: left;
            padding: 0.5mm;
        }
        th {
            border-bottom: 1px solid #000;
            font-weight: bold;
        }
        .item-name {
            width: 45%;
            word-wrap: break-word;
        }
        .item-qty {
            width: 15%;
            text-align: center;
        }
        .item-price, .item-total {
            width: 20%;
            text-align: right;
        }
        .total {
            text-align: right;
            font-weight: bold;
            margin-top: 2mm;
            font-size: 12pt;
            color: #ef4444;
        }
        .payment-info {
            margin: 2mm 0;
            font-size: 10pt;
        }
        .payment-info p {
            margin: 0.5mm 0;
        }
        .footer {
            text-align: center;
            margin-top: 4mm;
            font-size: 9pt;
            color: #555;
        }
        .thank-you {
            font-weight: bold;
            color: #ef4444;
            font-size: 11pt;
            margin: 3mm 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .divider {
            border-top: 1px dashed #000;
            margin: 2mm 0;
        }
        .total {
    text-align: right;
    font-weight: bold;
    margin-top: 2mm;
    font-size: 12pt;
    color: #000 !important;
}

@media print {
    .total {
        color: #000 !important;
        -webkit-print-color-adjust: exact;
    }
}

        @media print {
            body {
                width: 72mm;
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
            <img src="{{ asset('assets/images/logo-zalala.png') }}" alt="ZALALA BEACH BAR Logo" class="logo">
            <div class="company-name">ZALALA BEACH BAR</div>
            <div class="company-subtitle">BEACH BAR & RESTAURANT</div>
            <div class="beach-wave"></div>
            <p class="company-info">Bairro de Zalala, ER470</p>
            <p class="company-info">Quelimane, Zambézia</p>
            <p class="company-info">Tel: (+258) 846 885 214</p>
            <p class="company-info">NUIT: 110735901</p>
            <p class="company-info">Email: info@zalalabeach.com</p>
        </div>

        <div class="divider"></div>

        <div class="info">
            <p><strong>Recibo #{{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}</strong></p>
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
                    <th class="item-name">Item</th>
                    <th class="item-qty">Qtd</th>
                    <th class="item-price">Preço</th>
                    <th class="item-total">Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sale->saleItems as $item)
                    <tr>
                        <td class="item-name">{{ Str::limit($item->product->name, 20) }}</td>
                        <td class="item-qty">{{ $item->quantity }}</td>
                        <td class="item-price">MZN {{ number_format($item->unit_price, 2, ',', '.') }}</td>
                        <td class="item-total">MZN {{ number_format($item->unit_price * $item->quantity, 2, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center;">Nenhum item encontrado</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="divider"></div>

        @if($sale->tax_amount > 0)
        <div class="total">
            <p>SUBTOTAL: MZN {{ number_format($sale->total_amount - $sale->tax_amount, 2, ',', '.') }}</p>
            <p>IVA ({{ $sale->tax_rate }}%): MZN {{ number_format($sale->tax_amount, 2, ',', '.') }}</p>
            <p>TOTAL: MZN {{ number_format($sale->total_amount, 2, ',', '.') }}</p>
        </div>
        @else
        <div class="total" style="display:block; background:#fff;">
    TOTAL: <span style="font-weight:bold;">MZN {{ number_format($sale->total_amount, 2, ',', '.') }}</span>
</div>

        @endif

        @if($sale->payment_method)
            <div class="payment-info">
                <p><strong>Forma de Pagamento:</strong></p>
                <p>{{ [
                    'cash' => 'DINHEIRO',
                    'card' => 'CARTÃO',
                    'mpesa' => 'M-PESA',
                    'emola' => 'E-MOLA',
                    'mkesh' => 'M-KESH'
                ][$sale->payment_method] ?? strtoupper($sale->payment_method) }}</p>
                
                @php
                    $totalPaid = 0;
                @endphp
                
                @if($sale->cash_amount > 0)
                    <p>DINHEIRO: MZN {{ number_format($sale->cash_amount, 2, ',', '.') }}</p>
                    @php $totalPaid += $sale->cash_amount; @endphp
                @endif
                @if($sale->card_amount > 0)
                    <p>CARTÃO: MZN {{ number_format($sale->card_amount, 2, ',', '.') }}</p>
                    @php $totalPaid += $sale->card_amount; @endphp
                @endif
                @if($sale->mpesa_amount > 0)
                    <p>M-PESA: MZN {{ number_format($sale->mpesa_amount, 2, ',', '.') }}</p>
                    @php $totalPaid += $sale->mpesa_amount; @endphp
                @endif
                @if($sale->emola_amount > 0)
                    <p>E-MOLA: MZN {{ number_format($sale->emola_amount, 2, ',', '.') }}</p>
                    @php $totalPaid += $sale->emola_amount; @endphp
                @endif
                @if($sale->mkesh_amount > 0)
                    <p>M-KESH: MZN {{ number_format($sale->mkesh_amount, 2, ',', '.') }}</p>
                    @php $totalPaid += $sale->mkesh_amount; @endphp
                @endif
                
                @if($sale->cash_amount > 0 && $sale->cash_amount > $sale->total_amount)
                    <p style="color: #ef4444; font-weight: bold;">TROCO: MZN {{ number_format($sale->cash_amount - $sale->total_amount, 2, ',', '.') }}</p>
                @endif
            </div>
        @endif

        <div class="divider"></div>

        <div class="footer">
            <div class="thank-you">✨ OBRIGADO PELA PREFERÊNCIA! ✨</div>
            <p>VOLTE SEMPRE AO ZALALA BEACH BAR!</p>
            <small style="font-size: 8pt;">Este documento serve como comprovante de venda</small>
            <p>{{ now()->format('d/m/Y H:i') }}</p>
            <small style="font-size: 7pt;">Sistema de Gestão ZALALA v1.0</small>
        </div>
    </div>

    <div class="print-button" style="text-align: center; margin: 20px;">
        <button onclick="printReceipt()" style="margin: 5px; padding: 8px 16px; background: #4f46e5; color: white; border: none; border-radius: 4px; cursor: pointer;">
            <i class="mdi mdi-printer me-2"></i> Imprimir Novamente
        </button>
        <button onclick="closeAndReturn()" style="margin: 5px; padding: 8px 16px; background: #6b7280; color: white; border: none; border-radius: 4px; cursor: pointer;">
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
        
        function closeAndReturn() {
            window.close();
            // Fallback para redirecionamento se não for possível fechar
            setTimeout(function() {
                window.location.href = '/sales';
            }, 100);
        }
        
        window.onload = function() {
            // Imprime automaticamente após carregar
            setTimeout(function() {
                printReceipt();
            }, 100);
        };

        // Evento após impressão
        window.onafterprint = function() {
            // Opcional: fechar automaticamente após impressão
            setTimeout(function() {
                closeAndReturn()
            }, 1000);
        };
    </script>
</body>
</html>