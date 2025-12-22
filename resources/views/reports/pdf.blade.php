<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <title>Relatório de Gestão - Cafelu</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            color: #333;
            line-height: 1.5;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #eee;
            padding-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            color: #1a56db;
        }

        .header p {
            margin: 5px 0;
            color: #666;
        }

        .summary-grid {
            width: 100%;
            margin-bottom: 30px;
        }

        .summary-card {
            background: #f9fafb;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
        }

        .summary-card h4 {
            margin: 0;
            font-size: 12px;
            color: #6b7280;
            text-transform: uppercase;
        }

        .summary-card p {
            margin: 5px 0 0;
            font-size: 18px;
            font-weight: bold;
            color: #111827;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin: 20px 0 10px;
            color: #111827;
            border-left: 4px solid #1a56db;
            padding-left: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th {
            background: #f3f4f6;
            text-align: left;
            padding: 10px;
            font-size: 12px;
            font-weight: bold;
            color: #4b5563;
            border-bottom: 1px solid #e5e7eb;
        }

        td {
            padding: 10px;
            font-size: 12px;
            border-bottom: 1px solid #f3f4f6;
        }

        .text-right {
            text-align: right;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #9ca3af;
            padding: 20px 0;
        }

        .badge {
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
        }

        .badge-success {
            background: #def7ec;
            color: #03543f;
        }

        .badge-danger {
            background: #fde8e8;
            color: #9b1c1c;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Cafelu - Sistema de Gestão</h1>
        <p>Relatório de {{ ucfirst($reportType === 'all' ? 'Geral' : $reportType) }}</p>
        <p>Período: {{ \Carbon\Carbon::parse($dateFrom)->format('d/m/Y') }} até
            {{ \Carbon\Carbon::parse($dateTo)->format('d/m/Y') }}</p>
    </div>

    <table class="summary-grid">
        <tr>
            <td width="25%">
                <div class="summary-card">
                    <h4>Receita Total</h4>
                    <p>MT {{ number_format($totalRevenue, 2) }}</p>
                </div>
            </td>
            <td width="25%">
                <div class="summary-card">
                    <h4>Lucro Líquido</h4>
                    <p>MT {{ number_format($netProfit, 2) }}</p>
                </div>
            </td>
            <td width="25%">
                <div class="summary-card">
                    <h4>Total Vendas</h4>
                    <p>{{ $totalSales }}</p>
                </div>
            </td>
            <td width="25%">
                <div class="summary-card">
                    <h4>Despesas</h4>
                    <p>MT {{ number_format($totalExpenses, 2) }}</p>
                </div>
            </td>
        </tr>
    </table>

    @if($reportType === 'sales' || $reportType === 'all')
        <div class="section-title">Detalhamento de Vendas</div>
        <table>
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Cliente</th>
                    <th>Método</th>
                    <th class="text-right">Total</th>
                    <th class="text-right">Lucro</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sales as $sale)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($sale->sale_date)->format('d/m/Y H:i') }}</td>
                        <td>{{ $sale->customer_name ?? 'Geral' }}</td>
                        <td>{{ ucfirst($sale->payment_method) }}</td>
                        <td class="text-right">MT {{ number_format($sale->total_amount, 2) }}</td>
                        <td class="text-right">MT {{ number_format($sale->profit, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if($reportType === 'expenses' || $reportType === 'all')
        <div class="section-title">Detalhamento de Despesas</div>
        <table>
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Descrição</th>
                    <th>Categoria</th>
                    <th class="text-right">Valor</th>
                </tr>
            </thead>
            <tbody>
                @foreach($expenses as $expense)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($expense->expense_date)->format('d/m/Y') }}</td>
                        <td>{{ $expense->description }}</td>
                        <td>{{ $expense->category->name ?? 'N/A' }}</td>
                        <td class="text-right">MT {{ number_format($expense->amount, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if($reportType === 'products' || $reportType === 'all')
        <div class="section-title">Posição de Estoque</div>
        <table>
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Estoque</th>
                    <th class="text-right">P. Custo</th>
                    <th class="text-right">P. Venda</th>
                    <th class="text-right">Valor Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->stock_quantity }}</td>
                        <td class="text-right">MT {{ number_format($product->purchase_price, 2) }}</td>
                        <td class="text-right">MT {{ number_format($product->selling_price, 2) }}</td>
                        <td class="text-right">MT {{ number_format($product->stock_quantity * $product->purchase_price, 2) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="footer">
        Gerado em {{ now()->format('d/m/Y H:i:s') }} - Cafelu Management System
    </div>
</body>

</html>