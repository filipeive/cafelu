<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>{{ $sale->official_invoice_title }} - {{ $sale->invoice_number ?? ('VD-' . $sale->id) }}</title>
    <style>
        @page {
            margin: 12mm 15mm 15mm 15mm;
            size: a4 portrait;
        }
        body {
            font-family: 'DejaVu Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #1e293b;
            margin: 0;
            padding: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-table td {
            vertical-align: top;
        }
        .logo {
            max-height: 68px;
            max-width: 190px;
            margin-bottom: 6px;
        }
        .company-title {
            font-size: 18px;
            font-weight: bold;
            color: #0f172a;
            margin: 0 0 4px 0;
        }
        .company-details {
            font-size: 10px;
            color: #475569;
            line-height: 1.4;
        }
        .doc-badge {
            background-color: #0f172a;
            color: #ffffff;
            padding: 10px 14px;
            text-align: right;
            border-radius: 6px;
        }
        .doc-title {
            font-size: 15px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0;
            color: #f97316;
        }
        .doc-number {
            font-size: 13px;
            font-weight: bold;
            margin-top: 3px;
        }
        .info-box-table {
            margin-top: 15px;
            margin-bottom: 18px;
        }
        .info-box {
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 10px 12px;
            background-color: #f8fafc;
        }
        .info-box-title {
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            color: #64748b;
            margin-bottom: 6px;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 3px;
        }
        .info-box-content {
            font-size: 10.5px;
            color: #1e293b;
            line-height: 1.45;
        }
        .items-table {
            margin-top: 10px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
        }
        .items-table th {
            background-color: #0f172a;
            color: #ffffff;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 8px 10px;
        }
        .items-table td {
            padding: 7px 10px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 10.5px;
        }
        .items-table tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .totals-table {
            margin-top: 15px;
        }
        .totals-table td {
            vertical-align: top;
        }
        .totals-box {
            width: 260px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            background-color: #f8fafc;
            margin-left: auto;
        }
        .totals-box td {
            padding: 5px 12px;
            font-size: 10.5px;
        }
        .totals-box tr.grand-total {
            background-color: #0f172a;
            color: #ffffff;
            font-weight: bold;
            font-size: 12px;
        }
        .totals-box tr.grand-total td {
            color: #ffffff;
            padding: 8px 12px;
        }
        .terms-box {
            margin-top: 18px;
            font-size: 9.5px;
            color: #64748b;
            line-height: 1.4;
            border-top: 1px dashed #cbd5e1;
            padding-top: 8px;
        }
        .signatures-table {
            margin-top: 35px;
        }
        .signatures-table td {
            text-align: center;
            font-size: 10px;
            color: #475569;
        }
        .sig-line {
            border-top: 1px solid #94a3b8;
            width: 70%;
            margin: 0 auto 5px auto;
        }
        .footer-note {
            margin-top: 22px;
            text-align: center;
            font-size: 9px;
            color: #94a3b8;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
    </style>
</head>
<body>
    @php
        $tenant = current_tenant() ?? $sale->tenant;
        $companyName = $tenant?->name ?? \App\Models\Setting::get('company_name', 'Café Lu & Yoshi');
        $companyNuit = $tenant?->nuit ?? \App\Models\Setting::get('company_nuit', 'N/D');
        $companyAddress = $tenant?->address ?? \App\Models\Setting::get('company_address', 'Moçambique');
        $companyCity = $tenant?->city ?? \App\Models\Setting::get('company_city', 'Quelimane');
        $companyPhone = $tenant?->phone ?? \App\Models\Setting::get('company_phone', '');
        $companyEmail = $tenant?->email ?? \App\Models\Setting::get('company_email', '');
        $logoData = function_exists('get_receipt_logo_data') ? get_receipt_logo_data() : null;

        $invoiceTitle = $sale->official_invoice_title;
        $invoiceNumber = $sale->invoice_number ?? ('RC-' . date('Y') . '/' . str_pad((string)$sale->id, 4, '0', STR_PAD_LEFT));
        $subtotal = (float) ($sale->subtotal > 0 ? $sale->subtotal : $sale->total_amount);
        $discount = (float) ($sale->discount_amount ?? 0);
        $taxRate = (float) ($sale->tax_rate ?? 16);
        $taxAmount = (float) ($sale->tax_amount ?? 0);
        if ($taxAmount <= 0 && ($sale->tax_regime ?? 'normal') === 'normal' && $sale->total_amount > 0) {
            $base = ((float) $sale->total_amount) / (1 + ($taxRate / 100));
            $taxAmount = round(((float) $sale->total_amount) - $base, 2);
        }
    @endphp

    <!-- Top Header -->
    <table class="header-table">
        <tr>
            <td style="width: 55%;">
                @if($logoData)
                    <img src="{{ $logoData }}" alt="Logo" class="logo"><br>
                @endif
                <div class="company-title">{{ $companyName }}</div>
                <div class="company-details">
                    <strong>NUIT:</strong> {{ $companyNuit }}<br>
                    <strong>Endereço:</strong> {{ $companyAddress }}{{ $companyCity ? ' - ' . $companyCity : '' }}<br>
                    @if($companyPhone)
                        <strong>Tel:</strong> {{ $companyPhone }}
                    @endif
                    @if($companyEmail)
                        | <strong>E-mail:</strong> {{ $companyEmail }}
                    @endif
                </div>
            </td>
            <td style="width: 45%;">
                <div class="doc-badge">
                    <div class="doc-title">{{ $invoiceTitle }}</div>
                    <div class="doc-number">{{ $invoiceNumber }}</div>
                    <div style="font-size: 9.5px; margin-top: 4px; color: #cbd5e1;">
                        Original &bull; Processado por Computador
                    </div>
                </div>
                <div style="margin-top: 10px; font-size: 10px; text-align: right; color: #475569;">
                    <div><strong>Data de Emissão:</strong> {{ $sale->sale_date ? $sale->sale_date->format('d/m/Y H:i') : $sale->created_at->format('d/m/Y H:i') }}</div>
                    @if($sale->due_date)
                        <div><strong>Data de Vencimento:</strong> {{ $sale->due_date->format('d/m/Y') }}</div>
                    @endif
                    <div><strong>Forma de Pagamento:</strong> {{ $sale->formatted_payment_method }}</div>
                    <div><strong>Operador:</strong> {{ $sale->user?->name ?? 'Sistema' }}</div>
                </div>
            </td>
        </tr>
    </table>

    <!-- Info Box: Cliente & Enquadramento -->
    <table class="info-box-table">
        <tr>
            <td style="width: 58%; padding-right: 10px;">
                <div class="info-box">
                    <div class="info-box-title">Exmo.(s) Sr.(s) / Dados do Cliente</div>
                    <div class="info-box-content">
                        <strong>Nome / Entidade:</strong> {{ $sale->customer_name ?: 'Cliente Geral' }}<br>
                        <strong>NUIT:</strong> {{ $sale->customer_nuit ?: 'Consumidor Final' }}<br>
                        <strong>Telefone:</strong> {{ $sale->customer_phone ?: 'N/D' }}<br>
                        <strong>Endereço:</strong> {{ $sale->customer_address ?: ($companyCity ?: 'Moçambique') }}
                    </div>
                </div>
            </td>
            <td style="width: 42%;">
                <div class="info-box">
                    <div class="info-box-title">Enquadramento Fiscal & Referência</div>
                    <div class="info-box-content">
                        <strong>Regime de IVA:</strong>
                        @if(($sale->tax_regime ?? 'normal') === 'exempt')
                            Isento de IVA (Artigo 9º do CIVA)
                        @elseif(($sale->tax_regime ?? 'normal') === 'simplified')
                            Regime Simplificado (ISPC)
                        @else
                            Regime Geral (IVA {{ number_format($taxRate, 0) }}%)
                        @endif
                        <br>
                        <strong>Estado:</strong> {{ $sale->status === 'completed' ? 'Liquidado / Pago' : 'Pendente / A Prazo' }}<br>
                        <strong>Moeda:</strong> Metical (MZN)<br>
                        @if($sale->quotation)
                            <strong>Ref. Cotação:</strong> {{ $sale->quotation->quotation_number }}
                        @else
                            <strong>Venda Interna:</strong> #{{ str_pad((string)$sale->id, 5, '0', STR_PAD_LEFT) }}
                        @endif
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <!-- Tabela de Itens -->
    <table class="items-table">
        <thead>
            <tr>
                <th style="text-align: left; width: 8%;">#</th>
                <th style="text-align: left; width: 46%;">Descrição do Artigo / Serviço</th>
                <th class="text-center" style="width: 12%;">Qtd</th>
                <th class="text-right" style="width: 17%;">Preço Unit.</th>
                <th class="text-right" style="width: 17%;">Total (MZN)</th>
            </tr>
        </thead>
        <tbody>
            @if($sale->saleItems && $sale->saleItems->count() > 0)
                @foreach($sale->saleItems as $idx => $item)
                    <tr>
                        <td>{{ $idx + 1 }}</td>
                        <td><strong>{{ $item->product?->name ?? 'Artigo #' . $item->product_id }}</strong></td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-right">{{ number_format($item->unit_price, 2, ',', '.') }}</td>
                        <td class="text-right font-bold">{{ number_format($item->unit_price * $item->quantity, 2, ',', '.') }}</td>
                    </tr>
                @endforeach
            @elseif($sale->quotation && $sale->quotation->items->count() > 0)
                @foreach($sale->quotation->items as $idx => $qItem)
                    <tr>
                        <td>{{ $idx + 1 }}</td>
                        <td>
                            <strong>{{ $qItem->item_name }}</strong>
                            @if($qItem->description)
                                <br><span style="font-size: 9px; color: #64748b;">{{ $qItem->description }}</span>
                            @endif
                        </td>
                        <td class="text-center">{{ number_format($qItem->quantity, 0) }}</td>
                        <td class="text-right">{{ number_format($qItem->unit_price, 2, ',', '.') }}</td>
                        <td class="text-right font-bold">{{ number_format($qItem->total_price, 2, ',', '.') }}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>

    <!-- Totais -->
    <table class="totals-table">
        <tr>
            <td style="width: 55%; padding-right: 15px;">
                @if($sale->notes)
                    <div class="info-box">
                        <div class="info-box-title">Observações</div>
                        <div class="info-box-content">{{ $sale->notes }}</div>
                    </div>
                @endif
            </td>
            <td style="width: 45%;">
                <table class="totals-box">
                    <tr>
                        <td>Subtotal:</td>
                        <td class="text-right font-bold">{{ number_format($subtotal, 2, ',', '.') }} MZN</td>
                    </tr>
                    @if($discount > 0)
                        <tr>
                            <td>Descontos:</td>
                            <td class="text-right">-{{ number_format($discount, 2, ',', '.') }} MZN</td>
                        </tr>
                    @endif
                    <tr>
                        <td>IVA ({{ ($sale->tax_regime ?? 'normal') === 'exempt' ? 'Isento' : (number_format($taxRate, 0) . '%') }}):</td>
                        <td class="text-right">{{ number_format($taxAmount, 2, ',', '.') }} MZN</td>
                    </tr>
                    <tr class="grand-total">
                        <td>TOTAL GERAL:</td>
                        <td class="text-right">{{ number_format($sale->total_amount, 2, ',', '.') }} MZN</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div class="terms-box">
        <strong>Condições & Validação:</strong> Documento emitido eletronicamente pelo sistema RestPOS. Os bens ou serviços constantes neste documento foram colocados à disposição do adquirente na data de emissão.
    </div>

    <table class="signatures-table">
        <tr>
            <td style="width: 50%;">
                <div class="sig-line"></div>
                <div>O Emitente ({{ $companyName }})</div>
            </td>
            <td style="width: 50%;">
                <div class="sig-line"></div>
                <div>O Cliente ({{ $sale->customer_name ?: 'Assinatura' }})</div>
            </td>
        </tr>
    </table>

    <div class="footer-note">
        Obrigado pela preferência! &bull; Processado por RestPOS SaaS &bull; Impresso em {{ now()->format('d/m/Y H:i:s') }}
    </div>
</body>
</html>

