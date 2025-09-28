@extends('layouts.app')

@section('title', 'Rentabilidade por Cliente')
@section('page-title', 'Rentabilidade por Cliente')
@section('title-icon', 'mdi-account-star')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Relatórios</a></li>
    <li class="breadcrumb-item active">Rentabilidade por Cliente</li>
@endsection

@push('styles')
<style>
    .profit-header-card {
        background: rgba(255,255,255,0.95);
        backdrop-filter: blur(20px);
        border-radius: var(--border-radius-lg, 18px);
        box-shadow: var(--shadow-lg, 0 4px 24px rgba(0,0,0,0.07));
        padding: 2rem;
        margin-bottom: 2rem;
        position: relative;
    }
    .profit-header-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 4px;
        background: var(--beach-gradient, linear-gradient(90deg,#0891b2,#fbbf24));
    }
    .profit-title {
        font-size: 2rem;
        font-weight: 800;
        background: var(--beach-gradient, linear-gradient(90deg,#0891b2,#fbbf24));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .profit-title i {
        font-size: 2.2rem;
    }
    .table-hover tbody tr:hover {
        background-color: rgba(8,145,178,0.03);
    }
</style>
@endpush

@section('content')
<div class="profit-header-card mb-4">
    <div class="profit-title">
        <i class="mdi mdi-account-star text-warning"></i>
        Rentabilidade por Cliente
    </div>
    <div class="text-muted fw-semibold mb-2">
        Veja quais clientes geram mais receita e lucro para o seu negócio.
    </div>
</div>

<div class="card shadow border-0 fade-in">
    <div class="card-header bg-white">
        <h5 class="card-title mb-0 d-flex align-items-center">
            <i class="mdi mdi-account-group me-2 text-primary"></i>
            Análise de Clientes por Rentabilidade
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Cliente</th>
                        <th>Telefone</th>
                        <th class="text-center">Vendas</th>
                        <th class="text-end">Receita Total</th>
                        <th class="text-end">Lucro Total</th>
                        <th class="text-center">Margem</th>
                        <th class="text-end">Ticket Médio</th>
                        <th>Última Compra</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customerAnalysis as $customer)
                        <tr>
                            <td><strong>{{ $customer['customer_name'] }}</strong></td>
                            <td>{{ $customer['phone'] ?? 'N/A' }}</td>
                            <td class="text-center">{{ $customer['sales_count'] }}</td>
                            <td class="text-end text-success fw-bold">
                                {{ number_format($customer['total_revenue'], 2, ',', '.') }} MT
                            </td>
                            <td class="text-end {{ $customer['total_profit'] >= 0 ? 'text-success' : 'text-danger' }} fw-bold">
                                {{ number_format($customer['total_profit'], 2, ',', '.') }} MT
                            </td>
                            <td class="text-center">
                                <span class="badge bg-{{ $customer['profit_margin'] >= 25 ? 'success' : ($customer['profit_margin'] >= 15 ? 'warning' : 'danger') }}">
                                    {{ number_format($customer['profit_margin'], 1) }}%
                                </span>
                            </td>
                            <td class="text-end">{{ number_format($customer['average_ticket'], 2, ',', '.') }} MT</td>
                            <td>{{ \Carbon\Carbon::parse($customer['last_purchase'])->format('d/m/Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                <i class="mdi mdi-account-off fa-2x mb-2"></i>
                                Nenhum cliente com vendas no período
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection