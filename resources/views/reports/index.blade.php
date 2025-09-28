@extends('layouts.app')

@section('title', 'Relatórios')
@section('page-title', 'Relatórios')
@section('title-icon', 'mdi-chart-line')

@section('breadcrumbs')
    <li class="breadcrumb-item active d-flex align-items-center">
        <i class="mdi mdi-chart-line me-1"></i>
        Painel de Relatórios&nbsp;
        <small class="ms-2 text-success d-flex align-items-center">
            <i class="mdi mdi-lightbulb-on-outline me-1"></i>
            Explore dados financeiros, vendas, estoque e desempenho em tempo real.
        </small>
    </li>
@endsection

@push('styles')
<style>
    .reports-header {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: var(--border-radius-lg);
        box-shadow: var(--shadow-lg);
        padding: 2rem;
        margin-bottom: 2rem;
        position: relative;
    }
    .dropdown-menu {
        min-width: 180px;
        box-shadow: var(--shadow-lg);
        border-radius: var(--border-radius-md);
        transition: var(--transition);
        border: 1px solid rgba(0, 0, 0, 0.1);
        background: white;
        z-index: 1000;
    }

    .reports-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--beach-gradient);
    }

    .reports-title {
        font-size: 2rem;
        font-weight: 800;
        background: var(--beach-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 0.5rem;
    }

    .quick-actions {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .quick-action-btn {
        background: var(--primary-gradient);
        border: none;
        color: white;
        border-radius: 50px;
        padding: 0.75rem 1.5rem;
        font-size: 0.9rem;
        font-weight: 600;
        transition: var(--transition);
    }

    .quick-action-btn:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
        color: white;
    }

    .quick-action-btn.secondary { background: var(--secondary-gradient); }
    .quick-action-btn.secondary:hover { background: var(--secondary-dark); }
    .quick-action-btn.info { background: linear-gradient(135deg, var(--info-color), #3b82f6); }
    .quick-action-btn.danger { background: linear-gradient(135deg, var(--danger-color), #f87171); }

    .stats-card {
        transition: var(--transition);
        border-left: 4px solid transparent;
    }

    .stats-card.revenue { border-left-color: var(--primary-color); }
    .stats-card.profit { border-left-color: var(--info-color); }
    .stats-card.net-profit { border-left-color: var(--success-color); }
    .stats-card.net-loss { border-left-color: var(--danger-color); }

    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg);
    }

    /* Tabelas usando classes existentes */
    .table-hover tbody tr:hover {
        background-color: rgba(8, 145, 178, 0.03);
    }
</style>
@endpush

@section('content')
    <!-- Header -->
    <div class="content-wrapper fade-in">
        <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
            <div class="quick-actions d-flex flex-wrap gap-2">
                <!-- Vendas -->
                <div class="dropdown">
                    <button class="quick-action-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="mdi mdi-cart me-1"></i> Vendas <i class="mdi mdi-chevron-down ms-1"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('reports.daily-sales') }}"><i class="mdi mdi-calendar-today me-1"></i> Diárias</a></li>
                        <li><a class="dropdown-item" href="{{ route('reports.monthly-sales') }}"><i class="mdi mdi-calendar-month me-1"></i> Mensais</a></li>
                        <li><a class="dropdown-item" href="{{ route('reports.sales-by-product') }}"><i class="mdi mdi-package-variant me-1"></i> Por Produto</a></li>
                        <li><a class="dropdown-item" href="{{ route('reports.sales-specialized') }}"><i class="mdi mdi-star-outline me-1"></i> Especializado</a></li>
                    </ul>
                </div>
                <!-- Financeiro -->
                <div class="dropdown">
                    <button class="quick-action-btn secondary" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="mdi mdi-cash-multiple me-1"></i> Financeiro <i class="mdi mdi-chevron-down ms-1"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('reports.profit-loss') }}"><i class="mdi mdi-chart-line me-1"></i> Lucro / Prejuízo</a></li>
                        <li><a class="dropdown-item" href="{{ route('reports.cash-flow') }}"><i class="mdi mdi-bank-transfer me-1"></i> Fluxo de Caixa</a></li>
                        <li><a class="dropdown-item" href="{{ route('reports.expenses-specialized') }}"><i class="mdi mdi-receipt me-1"></i> Despesas Especializado</a></li>
                    </ul>
                </div>
                <!-- Análises -->
                <div class="dropdown">
                    <button class="quick-action-btn info" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="mdi mdi-chart-pie me-1"></i> Análises <i class="mdi mdi-chevron-down ms-1"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('reports.customer-profitability') }}"><i class="mdi mdi-account-star me-1"></i> Rentabilidade Clientes</a></li>
                        <li><a class="dropdown-item" href="{{ route('reports.abc-analysis') }}"><i class="mdi mdi-alpha-b-box me-1"></i> Análise ABC</a></li>
                        <li><a class="dropdown-item" href="{{ route('reports.period-comparison') }}"><i class="mdi mdi-compare me-1"></i> Comparação de Períodos</a></li>
                        <li><a class="dropdown-item" href="{{ route('reports.business-insights') }}"><i class="mdi mdi-lightbulb-on-outline me-1"></i> Insights do Negócio</a></li>
                    </ul>
                </div>
                <!-- Estoque -->
                <div class="dropdown">
                    <button class="quick-action-btn danger" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="mdi mdi-package-variant-closed me-1"></i> Estoque <i class="mdi mdi-chevron-down ms-1"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('reports.low-stock') }}"><i class="mdi mdi-alert-outline me-1"></i> Baixo Estoque</a></li>
                        <li><a class="dropdown-item" href="{{ route('reports.inventory') }}"><i class="mdi mdi-clipboard-list-outline me-1"></i> Inventário</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card mb-4 fade-in" style="z-index: 0; position: relative; ">
        <div class="card-header bg-white">
            <h5 class="card-title mb-0 d-flex align-items-center">
                <i class="mdi mdi-filter-variant me-2 text-primary"></i>
                Filtros de Período e Tipo
            </h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('reports.index') }}" id="filters-form">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Data Inicial</label>
                        <input type="date" class="form-control" name="date_from" value="{{ $dateFrom }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Data Final</label>
                        <input type="date" class="form-control" name="date_to" value="{{ $dateTo }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Tipo de Relatório</label>
                        <select class="form-select" name="report_type">
                            <option value="all" {{ $reportType == 'all' ? 'selected' : '' }}>Todos</option>
                            <option value="sales" {{ $reportType == 'sales' ? 'selected' : '' }}>Vendas</option>
                            <option value="expenses" {{ $reportType == 'expenses' ? 'selected' : '' }}>Despesas</option>
                            <option value="products" {{ $reportType == 'products' ? 'selected' : '' }}>Produtos</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="mdi mdi-magnify me-1"></i> Filtrar
                        </button>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Exportar</label>
                        <div class="d-flex gap-1">
                            <a href="{{ route('reports.export', array_merge(['date_from' => $dateFrom, 'date_to' => $dateTo, 'report_type' => $reportType], ['format' => 'pdf'])) }}"
                                class="btn btn-danger btn-sm flex-fill" title="PDF">
                                <i class="mdi mdi-file-pdf-box"></i>
                            </a>
                            <a href="{{ route('reports.export', array_merge(['date_from' => $dateFrom, 'date_to' => $dateTo, 'report_type' => $reportType], ['format' => 'excel'])) }}"
                                class="btn btn-success btn-sm flex-fill" title="Excel">
                                <i class="mdi mdi-file-excel-box"></i>
                            </a>
                            <a href="{{ route('reports.export.csv', array_merge(['date_from' => $dateFrom, 'date_to' => $dateTo, 'report_type' => $reportType])) }}"
                                class="btn btn-outline-secondary btn-sm flex-fill" title="CSV">
                                <i class="mdi mdi-file-delimited"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Stats Cards Principais -->
    <div class="row mb-4">
        <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
            <div class="card stats-card revenue h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted mb-2 fw-semibold">Total de Vendas</h6>
                            <h3 class="mb-0 text-primary fw-bold">{{ $totalSales }}</h3>
                            <small class="text-muted">em vendas
                                @if (isset($revenueGrowth))
                                    <span class="badge bg-{{ $revenueGrowth >= 0 ? 'success' : 'danger' }} ms-1">
                                        {{ $revenueGrowth >= 0 ? '+' : '' }}{{ number_format($revenueGrowth, 1) }}%
                                    </span>
                                @endif
                            </small>
                        </div>
                        <div class="text-primary">
                            <i class="mdi mdi-currency-usd fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
            <div class="card stats-card profit h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted mb-2 fw-semibold">Lucro Bruto</h6>
                            <h3 class="mb-0 text-info fw-bold">{{ number_format($grossProfit, 2, ',', '.') }} MT</h3>
                            <small class="text-muted">Margem: {{ number_format($grossMargin, 1) }}%</small>
                        </div>
                        <div class="text-info">
                            <i class="mdi mdi-chart-line fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
            <div class="card stats-card {{ $netProfit >= 0 ? 'net-profit' : 'net-loss' }} h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted mb-2 fw-semibold">Lucro Líquido</h6>
                            <h3 class="mb-0 fw-bold {{ $netProfit >= 0 ? 'text-success' : 'text-danger' }}">
                                {{ number_format($netProfit, 2, ',', '.') }} MT
                            </h3>
                            <small class="text-muted">Margem: {{ number_format($netMargin, 1) }}%</small>
                        </div>
                        <div class="{{ $netProfit >= 0 ? 'text-success' : 'text-danger' }}">
                            <i class="mdi {{ $netProfit >= 0 ? 'mdi-trophy' : 'mdi-alert-circle' }} fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
            <div class="card stats-card revenue h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted mb-2 fw-semibold">Ticket Médio</h6>
                            <h3 class="mb-0 text-primary fw-bold">{{ number_format($averageTicket, 2, ',', '.') }} MT</h3>
                            <small class="text-muted">por venda</small>
                        </div>
                        <div class="text-primary">
                            <i class="mdi mdi-calculator fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards Secundários -->
    <div class="row mb-4">
        <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
            <div class="card bg-light h-100">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Custo dos Produtos Vendidos</h6>
                    <h4 class="text-warning mb-0">{{ number_format($costOfGoodsSold, 2, ',', '.') }} MT</h4>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
            <div class="card bg-light h-100">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Despesas Operacionais</h6>
                    <h4 class="text-danger mb-0">{{ number_format($totalExpenses, 2, ',', '.') }} MT</h4>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
            <div class="card bg-light h-100">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Vendas/Dia (Média)</h6>
                    <h4 class="text-info mb-0">
                        {{ $totalSales > 0 ? number_format($totalSales / max(1, \Carbon\Carbon::parse($dateFrom)->diffInDays(\Carbon\Carbon::parse($dateTo)) + 1), 1) : 0 }}
                    </h4>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
            <div class="card bg-light h-100">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Total de Transações</h6>
                    <h4 class="text-primary mb-0">{{ isset($recentSales) ? $recentSales->count() : 0 }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card fade-in">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0 d-flex align-items-center">
                        <i class="mdi mdi-chart-areaspline me-2 text-primary"></i>
                        Evolução das Vendas e Lucro
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="salesChart" height="100"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card fade-in">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0 d-flex align-items-center">
                        <i class="mdi mdi-chart-pie me-2 text-info"></i>
                        Métodos de Pagamento
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="paymentMethodChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabelas de Dados -->
    <div class="row g-4">
        <!-- Produtos Mais Vendidos -->
        <div class="col-lg-6">
            <div class="card fade-in">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0 d-flex align-items-center">
                        <i class="mdi mdi-trophy me-2 text-warning"></i>
                        Produtos Mais Vendidos
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Produto</th>
                                    <th class="text-center">Qtd</th>
                                    <th class="text-end">Receita</th>
                                    <th class="text-end">Lucro</th>
                                    <th class="text-center">Margem</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topProducts as $product)
                                    <tr>
                                        <td><strong>{{ $product->name }}</strong></td>
                                        <td class="text-center">{{ $product->total_quantity }}</td>
                                        <td class="text-end text-success fw-bold">
                                            {{ number_format($product->total_revenue, 2, ',', '.') }} MT
                                        </td>
                                        <td class="text-end">
                                            <span class="fw-bold {{ $product->profit >= 0 ? 'text-success' : 'text-danger' }}">
                                                {{ number_format($product->profit, 2, ',', '.') }} MT
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-{{ $product->margin >= 20 ? 'success' : ($product->margin >= 10 ? 'warning' : 'danger') }}">
                                                {{ number_format($product->margin, 1) }}%
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            <i class="mdi mdi-package-variant-closed me-2"></i> Nenhum produto vendido no período
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Vendas Recentes -->
        <div class="col-lg-6">
            <div class="card fade-in">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0 d-flex align-items-center">
                        <i class="mdi mdi-clock-outline me-2 text-primary"></i>
                        Vendas Recentes
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Data</th>
                                    <th>Cliente</th>
                                    <th class="text-end">Total</th>
                                    <th>Pagamento</th>
                                    <th class="text-center">Itens</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentSales as $sale)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($sale->sale_date)->format('d/m/Y') }}</td>
                                        <td>{{ $sale->customer_name ?? 'N/A' }}</td>
                                        <td class="text-end text-success fw-bold">
                                            {{ number_format($sale->total_amount, 2, ',', '.') }} MT
                                        </td>
                                        <td>
                                            @php
                                                $paymentClass = match ($sale->payment_method) {
                                                    'cash' => 'success',
                                                    'card' => 'primary',
                                                    'transfer' => 'info',
                                                    'credit' => 'warning',
                                                    default => 'secondary',
                                                };
                                            @endphp
                                            <span class="badge bg-{{ $paymentClass }}">
                                                {{ ucfirst($sale->payment_method) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-light text-dark">{{ $sale->items->count() }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            <i class="mdi mdi-cart-outline me-2"></i> Nenhuma venda registrada
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabelas Detalhadas -->
    @if ($reportType === 'sales' || $reportType === 'all')
        <div class="card mt-4 fade-in">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0 d-flex align-items-center justify-content-between">
                    <span>
                        <i class="mdi mdi-table me-2 text-success"></i>
                        Vendas Detalhadas com Análise de Margem
                    </span>
                    <small class="text-muted">{{ $sales->count() }} vendas</small>
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Data</th>
                                <th>Cliente</th>
                                <th class="text-end">Total</th>
                                <th class="text-end">Custo</th>
                                <th class="text-end">Lucro</th>
                                <th class="text-center">Margem</th>
                                <th>Pagamento</th>
                                <th>Vendedor</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sales as $sale)
                                <tr>
                                    <td><strong class="text-primary">#{{ $sale->id }}</strong></td>
                                    <td>{{ \Carbon\Carbon::parse($sale->sale_date)->format('d/m/Y') }}</td>
                                    <td>{{ $sale->customer_name ?? 'N/A' }}</td>
                                    <td class="text-end text-success fw-bold">
                                        {{ number_format($sale->total_amount, 2, ',', '.') }} MT
                                    </td>
                                    <td class="text-end text-warning">
                                        {{ number_format($sale->cost ?? 0, 2, ',', '.') }} MT
                                    </td>
                                    <td class="text-end">
                                        <span class="fw-bold {{ ($sale->profit ?? 0) >= 0 ? 'text-success' : 'text-danger' }}">
                                            {{ number_format($sale->profit ?? 0, 2, ',', '.') }} MT
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ ($sale->margin ?? 0) >= 20 ? 'success' : (($sale->margin ?? 0) >= 10 ? 'warning' : 'danger') }}">
                                            {{ number_format($sale->margin ?? 0, 1) }}%
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $paymentClass = match ($sale->payment_method) {
                                                'cash' => 'success',
                                                'card' => 'primary',
                                                'transfer' => 'info',
                                                'credit' => 'warning',
                                                default => 'secondary',
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $paymentClass }}">
                                            {{ ucfirst($sale->payment_method) }}
                                        </span>
                                    </td>
                                    <td>{{ $sale->user->name ?? 'N/A' }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('sales.show', $sale) }}" class="btn btn-outline-primary" title="Ver Detalhes">
                                                <i class="mdi mdi-eye"></i>
                                            </a>
                                            <a href="{{ route('sales.print', $sale) }}" class="btn btn-outline-secondary" title="Imprimir">
                                                <i class="mdi mdi-printer"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-5 text-muted">
                                        <i class="mdi mdi-cart-outline fa-2x mb-3"></i>
                                        <p>Nenhuma venda encontrada no período.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if($sales->count() > 0)
                        <tfoot class="table-light">
                            <tr class="fw-bold">
                                <td colspan="3">TOTAIS:</td>
                                <td class="text-end text-success">{{ number_format($sales->sum('total_amount'), 2, ',', '.') }} MT</td>
                                <td class="text-end text-warning">{{ number_format($sales->sum('cost'), 2, ',', '.') }} MT</td>
                                <td class="text-end {{ $sales->sum('profit') >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ number_format($sales->sum('profit'), 2, ',', '.') }} MT
                                </td>
                                <td class="text-center">
                                    @php
                                        $totalMargin = $sales->sum('total_amount') > 0 ? ($sales->sum('profit') / $sales->sum('total_amount')) * 100 : 0;
                                    @endphp
                                    <span class="badge bg-{{ $totalMargin >= 20 ? 'success' : ($totalMargin >= 10 ? 'warning' : 'danger') }}">
                                        {{ number_format($totalMargin, 1) }}%
                                    </span>
                                </td>
                                <td colspan="3"></td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    @endif

    @if ($reportType === 'products' || $reportType === 'all')
        <div class="card mt-4 fade-in">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0 d-flex align-items-center justify-content-between">
                    <span>
                        <i class="mdi mdi-package-variant me-2 text-info"></i>
                        Relatório de Estoque e Performance
                    </span>
                    <small class="text-muted">{{ $products->count() }} produtos</small>
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Produto</th>
                                <th>Categoria</th>
                                <th class="text-center">Estoque</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Vendido</th>
                                <th class="text-end">Custo</th>
                                <th class="text-end">Venda</th>
                                <th class="text-center">Markup</th>
                                <th class="text-end">Receita</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                                <tr>
                                    <td><strong>{{ $product->name }}</strong></td>
                                    <td>{{ $product->category->name ?? 'N/A' }}</td>
                                    <td class="text-center">
                                        {{ $product->type === 'product' ? $product->stock_quantity : '-' }}
                                    </td>
                                    <td class="text-center">
                                        @if ($product->type === 'product')
                                            @if ($product->stock_quantity <= 0)
                                                <span class="badge bg-danger">Esgotado</span>
                                            @elseif($product->stock_quantity <= $product->min_stock_level)
                                                <span class="badge bg-warning">Baixo</span>
                                            @else
                                                <span class="badge bg-success">OK</span>
                                            @endif
                                        @else
                                            <span class="badge bg-info">Serviço</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ $product->quantity_sold > 0 ? 'success' : 'light text-dark' }}">
                                            {{ $product->quantity_sold }}
                                        </span>
                                    </td>
                                    <td class="text-end">{{ number_format($product->purchase_price, 2, ',', '.') }} MT</td>
                                    <td class="text-end text-success fw-bold">
                                        {{ number_format($product->selling_price, 2, ',', '.') }} MT
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ $product->markup >= 50 ? 'success' : ($product->markup >= 20 ? 'warning' : 'danger') }}">
                                            {{ number_format($product->markup, 1) }}%
                                        </span>
                                    </td>
                                    <td class="text-end text-primary fw-bold">
                                        {{ number_format($product->revenue_generated ?? 0, 2, ',', '.') }} MT
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5 text-muted">
                                        <i class="mdi mdi-package-variant-closed fa-2x mb-3"></i>
                                        <p>Nenhum produto encontrado.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    @if ($reportType === 'expenses' || $reportType === 'all')
        <div class="card mt-4 fade-in">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0 d-flex align-items-center justify-content-between">
                    <span>
                        <i class="mdi mdi-receipt me-2 text-danger"></i>
                        Despesas Operacionais
                    </span>
                    <small class="text-muted">{{ $expenses->count() }} despesas</small>
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Data</th>
                                <th>Categoria</th>
                                <th>Descrição</th>
                                <th class="text-end">Valor</th>
                                <th>Recibo</th>
                                <th>Usuário</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($expenses as $expense)
                                <tr>
                                    <td><strong class="text-danger">#{{ $expense->id }}</strong></td>
                                    <td>{{ \Carbon\Carbon::parse($expense->expense_date)->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge bg-light text-dark">
                                            {{ $expense->category->name ?? 'Sem Categoria' }}
                                        </span>
                                    </td>
                                    <td>{{ Str::limit($expense->description, 50) }}</td>
                                    <td class="text-end text-danger fw-bold">
                                        {{ number_format($expense->amount, 2, ',', '.') }} MT
                                    </td>
                                    <td>{{ $expense->receipt_number ?? 'N/A' }}</td>
                                    <td>{{ $expense->user->name ?? 'N/A' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="mdi mdi-receipt fa-2x mb-3"></i>
                                        <p>Nenhuma despesa registrada no período.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if($expenses->count() > 0)
                        <tfoot class="table-light">
                            <tr class="fw-bold">
                                <td colspan="4">TOTAL DE DESPESAS:</td>
                                <td class="text-end text-danger">
                                    {{ number_format($expenses->sum('amount'), 2, ',', '.') }} MT
                                </td>
                                <td colspan="2"></td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Adicionar efeitos de hover suaves nos cards
            const statsCards = document.querySelectorAll('.stats-card');
            statsCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });

            // Gráfico de Vendas
            const salesCtx = document.getElementById('salesChart').getContext('2d');
            new Chart(salesCtx, {
                type: 'line',
                data: {
                    labels: @json($salesChartLabels),
                    datasets: [{
                            label: 'Receita (MT)',
                            data: @json($salesChartData),
                            borderColor: '#0891b2',
                            backgroundColor: 'rgba(8, 145, 178, 0.1)',
                            tension: 0.3,
                            fill: false,
                            yAxisID: 'y'
                        },
                        @if (isset($salesChartProfitData))
                            {
                                label: 'Lucro Bruto (MT)',
                                data: @json($salesChartProfitData),
                                borderColor: '#10b981',
                                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                tension: 0.3,
                                fill: false,
                                yAxisID: 'y'
                            }
                        @endif
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + context.parsed.y.toLocaleString('pt-MZ') + ' MT';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            position: 'left',
                            ticks: {
                                callback: value => value.toLocaleString('pt-MZ') + ' MT'
                            }
                        }
                    }
                }
            });

            // Gráfico de Métodos de Pagamento
            const paymentCtx = document.getElementById('paymentMethodChart').getContext('2d');
            new Chart(paymentCtx, {
                type: 'doughnut',
                data: {
                    labels: @json($paymentMethodLabels),
                    datasets: [{
                        data: @json($paymentMethodData),
                        backgroundColor: [
                            '#10b981', '#0891b2', '#06b6d4', '#f59e0b', '#8b5cf6'
                        ],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((context.parsed * 100) / total).toFixed(1);
                                    return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
@endpush