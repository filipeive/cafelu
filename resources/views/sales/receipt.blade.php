<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.receipt_number') }} #{{ $sale->id }}</title>
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
            max-width: 35mm;
            height: auto;
            display: block;
            margin: 0 auto 1.5mm;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
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
            img.logo {
                display: block !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                max-width: 35mm !important;
                height: auto !important;
                margin: 0 auto 2mm !important;
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
        @php
            $receiptLogo = get_receipt_logo_data();
            $tenant = current_tenant();
            $companyName = $tenant?->name ?? \App\Models\Setting::get('company_name', 'Café Lu & Yoshi');
            $companySubtitle = \App\Models\Setting::get('company_subtitle');
            $companyAddress = $tenant?->address ?? \App\Models\Setting::get('company_address');
            $companyCity = $tenant?->city ?? \App\Models\Setting::get('company_city');
            $companyPhone = $tenant?->phone ?? \App\Models\Setting::get('company_phone');
            $companyNuit = $tenant?->nuit ?? \App\Models\Setting::get('company_nuit');
            $companyEmail = $tenant?->email ?? \App\Models\Setting::get('company_email');
        @endphp
        <div class="header">
            @if($receiptLogo)
                <img src="{{ $receiptLogo }}" alt="Logo" class="logo">
            @endif
            <h2 class="company-name">{{ $companyName }}</h2>
            @if($companySubtitle)
                <h3 class="company-subtitle">{{ $companySubtitle }}</h3>
            @endif
            @if($companyAddress)
                <p class="company-info">{{ $companyAddress }}</p>
            @endif
            @if($companyCity)
                <p class="company-info">{{ $companyCity }}</p>
            @endif
            @if($companyPhone)
                <p class="company-info">Tel: {{ $companyPhone }}</p>
            @endif
            @if($companyNuit)
                <p class="company-info">NUIT: {{ $companyNuit }}</p>
            @endif
            @if($companyEmail)
                <p class="company-info">Email: {{ $companyEmail }}</p>
            @endif
            <p>{{ __('messages.date') }}: {{ date('d/m/Y H:i', strtotime($sale->sale_date)) }}</p>
        </div>

        <div class="divider"></div>

        <div class="info">
            <p><strong>{{ __('messages.receipt_number') }} #{{ str_pad($sale->id, 5, '0', STR_PAD_LEFT) }}</strong></p>
            <p><strong>{{ __('messages.date') }}:</strong> {{ \Carbon\Carbon::parse($sale->sale_date)->format('d/m/Y H:i') }}</p>
            @if($sale->table)
                <p><strong>{{ __('messages.table') }}:</strong> {{ $sale->table->number }}</p>
            @endif
            <p><strong>{{ __('messages.attendant') }}:</strong> {{ $sale->user->name ?? 'Sistema' }}</p>
        </div>

        <div class="divider"></div>

        <table>
            <thead>
                <tr>
                    <th>{{ __('messages.item') }}</th>
                    <th>{{ __('messages.qty') }}</th>
                    <th>{{ __('messages.price') }}</th>
                    <th>{{ __('messages.total') }}</th>
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
                        <td colspan="4" style="text-align: center;">{{ __('messages.no_items_found') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="divider"></div>

        <div class="total">
            <p>{{ __('messages.total') }}: {{ number_format($sale->total_amount, 2, ',', '.') }} MZN</p>
        </div>

        @if($sale->payment_method)
            <div class="info">
                <p><strong>{{ __('messages.payment_method_label') }}:</strong> 
                    @switch($sale->payment_method)
                        @case('cash')
                            {{ __('messages.cash') }}
                            @break
                        @case('card')
                            {{ __('messages.card') }}
                            @break
                        @case('mpesa')
                            {{ __('messages.mpesa') }}
                            @break
                        @case('emola')
                            {{ __('messages.emola') }}
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
            <p style="font-weight: bold; color: #FF0000;">{{ __('messages.thanks_preference') }}</p>
            <p>{{ __('messages.come_back_soon') }}</p>
            <small style="font-size: 8pt;">{{ __('messages.not_invoice_desc') }}</small>
            <p>{{ now()->format('d/m/Y H:i') }}</p>
        </div>
    </div>

    <div class="print-button" style="text-align: center; margin: 20px;">
        <button onclick="window.print()">{{ __('messages.print_receipt') }}</button>
        <button onclick="closeAndReturn()">{{ __('messages.close_and_return') }}</button>
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