<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Cotação Comercial - {{ $quotation->quotation_number }}</title>
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
            background-color: #0284c7;
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
            color: #ffffff;
        }
        .doc-number {
            font-size: 13px;
            font-weight: bold;
            margin-top: 3px;
            color: #e0f2fe;
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
            background-color: #0284c7;
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
        $tenant = current_tenant() ?? $quotation->tenant;
        $companyName = $tenant?->name ?? \App\Models\Setting::get('company_name', 'Café Lu & Yoshi');
        $companyNuit = $tenant?->nuit ?? \App\Models\Setting::get('company_nuit', 'N/D');
        $companyAddress = $tenant?->address ?? \App\Models\Setting::get('company_address', 'Moçambique');
        $companyCity = $tenant?->city ?? \App\Models\Setting::get('company_city', 'Quelimane');
        $companyPhone = $tenant?->phone ?? \App\Models\Setting::get('company_phone', '');
        $companyEmail = $tenant?->email ?? \App\Models\Setting::get('company_email', '');
        $logoData = function_exists('get_receipt_logo_data') ? get_receipt_logo_data() : null;
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
                    <div class="doc-title">Cotação / Proforma</div>
                    <div class="doc-number">{{ $quotation->quotation_number }}</div>
                    <div style="font-size: 9.5px; margin-top: 4px; color: #e0f2fe;">
                        Proposta Comercial &bull; Sem Efeito de Factura
                    </div>
                </div>
                <div style="margin-top: 10px; font-size: 10px; text-align: right; color: #475569;">
                    <div><strong>Data de Emissão:</strong> {{ $quotation->date ? $quotation->date->format('d/m/Y') : now()->format('d/m/Y') }}</div>
                    @if($quotation->valid_until)
                        <div><strong>Válida Até:</strong> {{ $quotation->valid_until->format('d/m/Y') }}</div>
                    @endif
                    <div><strong>Estado:</strong> {{ $quotation->status_label }}</div>
                    <div><strong>Elaborado por:</strong> {{ $quotation->user?->name ?? 'Comercial' }}</div>
                </div>
            </td>
        </tr>
    </table>

    <!-- Info Box: Cliente & Condições -->
    <table class="info-box-table">
        <tr>
            <td style="width: 58%; padding-right: 10px;">
                <div class="info-box">
                    <div class="info-box-title">Destinatário / Proposta Apresentada a</div>
                    <div class="info-box-content">
                        <strong>Nome / Empresa:</strong> {{ $quotation->customer_name }}<br>
                        <strong>NUIT:</strong> {{ $quotation->customer_nuit ?: 'Consumidor Final' }}<br>
                        <strong>Telefone:</strong> {{ $quotation->customer_phone ?: 'N/D' }}<br>
                        <strong>Endereço:</strong> {{ $quotation->customer_address ?: ($companyCity ?: 'Moçambique') }}
                    </div>
                </div>
            </td>
            <td style="width: 42%;">
                <div class="info-box">
                    <div class="info-box-title">Condições Comerciais</div>
                    <div class="info-box-content">
                        <strong>Regime Fiscal:</strong>
                        @if($quotation->tax_regime === 'exempt')
                            Isento de IVA (Artigo 9º do CIVA)
                        @elseif($quotation->tax_regime === 'simplified')
                            Regime Simplificado (ISPC)
                        @else
                            Regime Geral (IVA {{ number_format($quotation->tax_rate, 0) }}%)
                        @endif
                        <br>
                        <strong>Preços:</strong> {{ $quotation->prices_include_tax ? 'Incluem IVA' : 'Acresce IVA' }}<br>
                        <strong>Moeda:</strong> Metical (MZN)<br>
                        <strong>Validade:</strong> {{ $quotation->valid_until ? $quotation->valid_until->format('d/m/Y') : '15 dias' }}
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <!-- Tabela de Itens -->
    <table class="items-table">
        <thead>
            <tr>
                <th style="text-align: left; width: 6%;">#</th>
                <th style="text-align: left; width: 42%;">Descrição do Artigo / Serviço</th>
                <th class="text-center" style="width: 10%;">Qtd</th>
                <th class="text-right" style="width: 15%;">Preço Unit.</th>
                <th class="text-right" style="width: 12%;">Desc.</th>
                <th class="text-right" style="width: 15%;">Total (MZN)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($quotation->items as $idx => $item)
                <tr>
                    <td>{{ $idx + 1 }}</td>
                    <td>
                        <strong>{{ $item->item_name }}</strong>
                        @if($item->description)
                            <br><span style="font-size: 9px; color: #64748b;">{{ $item->description }}</span>
                        @endif
                    </td>
                    <td class="text-center">{{ number_format($item->quantity, 0) }}</td>
                    <td class="text-right">{{ number_format($item->unit_price, 2, ',', '.') }}</td>
                    <td class="text-right">{{ $item->discount_amount > 0 ? '-' . number_format($item->discount_amount, 2, ',', '.') : '-' }}</td>
                    <td class="text-right font-bold">{{ number_format($item->total_price, 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Totais -->
    <table class="totals-table">
        <tr>
            <td style="width: 55%; padding-right: 15px;">
                @if($quotation->notes || $quotation->terms_conditions)
                    <div class="info-box">
                        <div class="info-box-title">Termos e Observações</div>
                        <div class="info-box-content">
                            @if($quotation->terms_conditions)
                                <div>{{ $quotation->terms_conditions }}</div>
                            @endif
                            @if($quotation->notes)
                                <div style="margin-top: 4px;"><strong>Notas:</strong> {{ $quotation->notes }}</div>
                            @endif
                        </div>
                    </div>
                @endif
            </td>
            <td style="width: 45%;">
                <table class="totals-box">
                    <tr>
                        <td>Subtotal:</td>
                        <td class="text-right font-bold">{{ number_format($quotation->subtotal, 2, ',', '.') }} MZN</td>
                    </tr>
                    @if($quotation->discount_amount > 0)
                        <tr>
                            <td>Descontos:</td>
                            <td class="text-right">-{{ number_format($quotation->discount_amount, 2, ',', '.') }} MZN</td>
                        </tr>
                    @endif
                    <tr>
                        <td>IVA ({{ $quotation->tax_regime === 'exempt' ? 'Isento' : (number_format($quotation->tax_rate, 0) . '%') }}):</td>
                        <td class="text-right">{{ number_format($quotation->tax_amount, 2, ',', '.') }} MZN</td>
                    </tr>
                    <tr class="grand-total">
                        <td>TOTAL PROPOSTA:</td>
                        <td class="text-right">{{ number_format($quotation->total_amount, 2, ',', '.') }} MZN</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div class="terms-box">
        <strong>Nota Importante:</strong> Esta cotação constitui uma proposta comercial e não serve como recibo de quitação. Após aprovação pelo cliente, será emitida a respetiva Factura ou Factura-Recibo oficial.
    </div>

    <table class="signatures-table">
        <tr>
            <td style="width: 50%;">
                <div class="sig-line"></div>
                <div>Proponente ({{ $companyName }})</div>
            </td>
            <td style="width: 50%;">
                <div class="sig-line"></div>
                <div>Aceite pelo Cliente ({{ $quotation->customer_name }})</div>
            </td>
        </tr>
    </table>

    <div class="footer-note">
        Obrigado pela preferência! &bull; Processado por RestPOS SaaS &bull; Emitido em {{ now()->format('d/m/Y H:i:s') }}
    </div>
</body>
</html>
