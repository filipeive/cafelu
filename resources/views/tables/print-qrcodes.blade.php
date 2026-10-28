<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Codes das Mesas - {{ current_tenant()?->name ?? \App\Models\Setting::get('company_name', 'RestPOS') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/qrcode-generator@1.4.4/qrcode.min.js"></script>
    <style>
        @page {
            size: A4 portrait;
            margin: 10mm;
        }
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f8fafc;
            color: #0f172a;
            margin: 0;
            padding: 20px;
        }
        .toolbar {
            max-width: 900px;
            margin: 0 auto 24px auto;
            background: #0f172a;
            color: #fff;
            padding: 16px 24px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.15);
        }
        .toolbar h1 {
            font-size: 16px;
            font-weight: 800;
            margin: 0;
        }
        .toolbar p {
            font-size: 12px;
            color: #94a3b8;
            margin: 2px 0 0 0;
        }
        .toolbar-actions {
            display: flex;
            gap: 10px;
        }
        .btn {
            padding: 10px 18px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 700;
            border: none;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-primary {
            background: #f97316;
            color: #fff;
        }
        .btn-secondary {
            background: #334155;
            color: #fff;
        }
        .grid {
            max-width: 900px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 18px;
        }
        .qr-card {
            background: #ffffff;
            border: 2px dashed #fdba74;
            border-radius: 24px;
            padding: 22px 18px;
            text-align: center;
            page-break-inside: avoid;
            break-inside: avoid;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
        }
        .brand-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #0f172a;
            color: #fff;
            padding: 5px 14px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 8px;
        }
        .logo-img {
            max-height: 38px;
            width: auto;
            margin-bottom: 6px;
            object-fit: contain;
        }
        .table-label {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #64748b;
        }
        .table-number {
            font-size: 32px;
            font-weight: 900;
            color: #ea580c;
            margin: 2px 0;
            line-height: 1.1;
        }
        .table-capacity {
            font-size: 11px;
            color: #64748b;
            margin-bottom: 10px;
        }
        .qr-box {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 10px;
            margin: 6px auto 12px auto;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .cta-title {
            font-size: 13px;
            font-weight: 800;
            color: #0f172a;
            margin: 0 0 4px 0;
        }
        .cta-desc {
            font-size: 10.5px;
            color: #475569;
            line-height: 1.4;
            max-width: 240px;
            margin: 0 auto 8px auto;
        }
        .short-url {
            font-family: monospace;
            font-size: 10px;
            color: #94a3b8;
            border-top: 1px solid #f1f5f9;
            padding-top: 6px;
            width: 100%;
        }
        @media print {
            body {
                background: #ffffff;
                padding: 0;
            }
            .toolbar {
                display: none !important;
            }
            .grid {
                max-width: 100%;
                gap: 12mm;
            }
            .qr-card {
                border: 2px dashed #ea580c;
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    @php
        $companyName = current_tenant()?->name ?? \App\Models\Setting::get('company_name', 'RestPOS');
        $logoData = function_exists('get_receipt_logo_data') ? get_receipt_logo_data() : null;
    @endphp

    <div class="toolbar">
        <div>
            <h1>Impressão de Placas QR Code para Mesas ({{ $tables->count() }})</h1>
            <p>Recorte e coloque cada placa na respetiva mesa para pedidos presenciais automáticos e chamada de garçom.</p>
        </div>
        <div class="toolbar-actions">
            <a href="{{ route('tables.index') }}" class="btn btn-secondary">
                <i class="mdi mdi-arrow-left"></i> Voltar às Mesas
            </a>
            <button type="button" onclick="window.print()" class="btn btn-primary">
                <i class="mdi mdi-printer"></i> Imprimir Agora (A4)
            </button>
        </div>
    </div>

    <div class="grid">
        @foreach($tables as $table)
            <div class="qr-card">
                <div>
                    @if($logoData)
                        <img src="{{ $logoData }}" alt="{{ $companyName }}" class="logo-img"><br>
                    @endif
                    <div class="brand-pill">
                        <span>{{ $companyName }}</span>
                    </div>
                    <div class="table-label">Menu Digital & Pedidos</div>
                    <div class="table-number">MESA {{ $table->number }}</div>
                    <div class="table-capacity">{{ $table->capacity }} Lugares</div>
                </div>

                <div class="qr-box" data-qr-url="{{ $table->qr_code_url }}"></div>

                <div>
                    <p class="cta-title">Aponte a Câmara do Telemóvel</p>
                    <p class="cta-desc">
                        Consulte o cardápio ilustrado, faça o seu pedido diretamente na <strong>Mesa {{ $table->number }}</strong> ou chame o garçom.
                    </p>
                    <div class="short-url">{{ $table->qr_code_url }}</div>
                </div>
            </div>
        @endforeach
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('[data-qr-url]').forEach(function (el) {
                const url = el.getAttribute('data-qr-url');
                try {
                    if (typeof qrcode === 'function') {
                        const qr = qrcode(0, 'M');
                        qr.addData(url);
                        qr.make();
                        el.innerHTML = qr.createSvgTag({ cellSize: 4, margin: 2, scalable: true });
                        const svg = el.querySelector('svg');
                        if (svg) {
                            svg.setAttribute('width', '155');
                            svg.setAttribute('height', '155');
                        }
                        return;
                    }
                } catch (e) {}
                el.innerHTML = `<img src="https://api.qrserver.com/v1/create-qr-code/?size=160x160&data=${encodeURIComponent(url)}" width="155" height="155" alt="QR">`;
            });
        });
    </script>
</body>
</html>
