<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fecho de Turno #{{ $shift->id }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 10pt;
            margin: 0;
            padding: 0;
            width: 80mm;
            -webkit-print-color-adjust: exact;
            box-sizing: border-box;
            background: #fff;
            color: #000;
        }
        .receipt {
            padding: 4mm;
            width: calc(80mm - 8mm);
            box-sizing: border-box;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 3mm;
        }
        .company-name {
            font-size: 13pt;
            font-weight: bold;
            margin: 1mm 0;
            text-transform: uppercase;
        }
        .title {
            font-size: 11pt;
            font-weight: bold;
            margin: 2mm 0;
            border-top: 1px dashed #000;
            border-bottom: 1px dashed #000;
            padding: 1.5mm 0;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            font-size: 9pt;
            margin: 1mm 0;
        }
        .divider {
            border-top: 1px dashed #000;
            margin: 2.5mm 0;
        }
        .double-divider {
            border-top: 2px double #000;
            margin: 2.5mm 0;
        }
        .bold {
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .actions {
            margin: 5mm auto;
            text-align: center;
        }
        .btn-print {
            padding: 8px 16px;
            background: #f97316;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            font-family: sans-serif;
            font-size: 12px;
        }
        @media print {
            .actions {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <div class="actions">
        <button class="btn-print" onclick="window.print()">Imprimir Talão (Fecho Z)</button>
    </div>

    <div class="receipt">
        <div class="header">
            <div class="company-name">{{ config('app.name', 'CAFELU') }}</div>
            <div style="font-size: 8pt;">SISTEMA DE GESTAO DE RESTAURANTE</div>
            <div class="title">RELATORIO DE FECHO Z (TURNO #{{ $shift->id }})</div>
        </div>

        <div class="info-row">
            <span>Operador:</span>
            <span class="bold">{{ $shift->user?->name ?? 'Operador' }}</span>
        </div>
        <div class="info-row">
            <span>Abertura:</span>
            <span>{{ $shift->opened_at->format('d/m/Y H:i') }}</span>
        </div>
        <div class="info-row">
            <span>Fecho:</span>
            <span>{{ $shift->closed_at ? $shift->closed_at->format('d/m/Y H:i') : 'EM CURSO' }}</span>
        </div>
        <div class="info-row">
            <span>Estado:</span>
            <span class="bold">{{ strtoupper($shift->status) }}</span>
        </div>

        <div class="divider"></div>

        <div class="bold" style="font-size: 9pt; margin-bottom: 1.5mm;">GAVETA & SALDO EM DINHEIRO</div>
        <div class="info-row">
            <span>Fundo de Maneio:</span>
            <span>MZN {{ number_format($shift->opening_balance, 2, ',', '.') }}</span>
        </div>
        <div class="info-row">
            <span>+ Vendas em Dinheiro:</span>
            <span>MZN {{ number_format($shift->cash_sales, 2, ',', '.') }}</span>
        </div>
        <div class="info-row bold">
            <span>= Esperado em Gaveta:</span>
            <span>MZN {{ number_format($shift->expected_cash, 2, ',', '.') }}</span>
        </div>
        <div class="info-row">
            <span>Contagem Fisica:</span>
            <span>MZN {{ number_format($shift->closing_balance_actual ?? 0, 2, ',', '.') }}</span>
        </div>
        <div class="info-row bold">
            <span>Diferenca (Quebra/Sobra):</span>
            <span>
                @if($shift->difference < 0)
                    -MZN {{ number_format(abs($shift->difference), 2, ',', '.') }} (QUEBRA)
                @elseif($shift->difference > 0)
                    +MZN {{ number_format($shift->difference, 2, ',', '.') }} (SOBRA)
                @else
                    MZN 0,00 (EXATO)
                @endif
            </span>
        </div>

        <div class="divider"></div>

        <div class="bold" style="font-size: 9pt; margin-bottom: 1.5mm;">VENDAS DIGITAIS</div>
        <div class="info-row">
            <span>Cartao POS (TPA):</span>
            <span>MZN {{ number_format($shift->card_sales, 2, ',', '.') }}</span>
        </div>
        <div class="info-row">
            <span>M-Pesa:</span>
            <span>MZN {{ number_format($shift->mpesa_sales, 2, ',', '.') }}</span>
        </div>
        <div class="info-row">
            <span>e-Mola:</span>
            <span>MZN {{ number_format($shift->emola_sales, 2, ',', '.') }}</span>
        </div>

        <div class="double-divider"></div>

        <div class="info-row bold" style="font-size: 11pt;">
            <span>TOTAL FATURADO:</span>
            <span>MZN {{ number_format($shift->total_sales, 2, ',', '.') }}</span>
        </div>
        <div class="info-row" style="font-size: 8pt;">
            <span>Total de Vendas:</span>
            <span>{{ $shift->sales->count() }} transacoes</span>
        </div>

        @if($shift->notes)
            <div class="divider"></div>
            <div style="font-size: 8pt;">
                <span class="bold">Obs:</span> {{ $shift->notes }}
            </div>
        @endif

        <div class="divider"></div>

        <div style="margin-top: 6mm; text-align: center;">
            <div style="border-top: 1px solid #000; width: 80%; margin: 0 auto 1.5mm;"></div>
            <div style="font-size: 8pt;">Assinatura do Operador</div>
        </div>

        <div style="margin-top: 5mm; text-align: center;">
            <div style="border-top: 1px solid #000; width: 80%; margin: 0 auto 1.5mm;"></div>
            <div style="font-size: 8pt;">Assinatura do Supervisor</div>
        </div>

        <div style="margin-top: 5mm; text-align: center; font-size: 7pt; color: #555;">
            Documento emitido em {{ now()->format('d/m/Y H:i:s') }}
        </div>
    </div>

    <script>
        window.addEventListener('load', () => {
            // Se aberto via popup, pode disparar print automático
            // window.print();
        });
    </script>
</body>
</html>
