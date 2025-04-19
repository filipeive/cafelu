@extends('layouts.app')

@section('title', 'Vendas por Forma de Pagamento')

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-4">
                        <h4 class="card-title">Relatório de Vendas por Forma de Pagamento</h4>
                        <a href="{{ route('reports.index') }}" class="btn btn-sm btn-outline-primary">Voltar</a>
                    </div>
                    
                    <form action="{{ route('reports.salesByPaymentMethod') }}" method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="start_date">Data Inicial</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $startDate->format('Y-m-d') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="end_date">Data Final</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $endDate->format('Y-m-d') }}">
                                </div>
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary">Filtrar</button>
                            </div>
                        </div>
                    </form>
                    
                    <div class="row">
                        <div class="col-md-6 grid-margin">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Distribuição por Forma de Pagamento</h5>
                                    <canvas id="paymentMethodChart" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 grid-margin">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Contagem de Transações</h5>
                                    <canvas id="transactionCountChart" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        @foreach($salesByPaymentMethod as $index => $method)
                            @php
                                $colors = ['primary', 'success', 'warning', 'danger', 'info'];
                                $color = $colors[$index % count($colors)];
                                $icons = ['mdi-credit-card', 'mdi-cash', 'mdi-cash-multiple', 'mdi-cellphone', 'mdi-bank'];
                                $icon = $icons[$index % count($icons)];
                            @endphp
                            <div class="col-md-4 mb-4">
                                <div class="card bg-{{ $color }} text-white">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <p class="mb-2">{{ $method->payment_method }}</p>
                                                <h3 class="mb-0">MZN {{ number_format($method->total, 2, ',', '.') }}</h3>
                                                <small>{{ $method->count }} transações</small>
                                            </div>
                                            <i class="mdi {{ $icon }} icon-lg"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Forma de Pagamento</th>
                                    <th>Quantidade de Transações</th>
                                    <th>Valor Total</th>
                                    <th>Valor Médio por Transação</th>
                                    <th>% do Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $totalSales = $salesByPaymentMethod->sum('total'); @endphp
                                @foreach($salesByPaymentMethod as $method)
                                <tr>
                                    <td>
                                        @if($method->payment_method == 'Dinheiro')
                                            <i class="mdi mdi-cash text-success"></i>
                                        @elseif($method->payment_method == 'Cartão de Crédito')
                                            <i class="mdi mdi-credit-card text-primary"></i>
                                        @elseif($method->payment_method == 'Cartão de Débito')
                                            <i class="mdi mdi-credit-card-outline text-info"></i>
                                        @elseif($method->payment_method == 'Pix')
                                            <i class="mdi mdi-cellphone text-warning"></i>
                                        @elseif($method->payment_method == 'Transferência')
                                            <i class="mdi mdi-bank text-danger"></i>
                                        @else
                                            <i class="mdi mdi-cash-multiple"></i>
                                        @endif
                                        {{ $method->payment_method }}
                                    </td>
                                    <td>{{ $method->count }}</td>
                                    <td>MZN {{ number_format($method->total, 2, ',', '.') }}</td>
                                    <td>MZN {{ number_format($method->total / $method->count, 2, ',', '.') }}</td>
                                    <td>
                                        @php $percentage = ($method->total / $totalSales) * 100; @endphp
                                        <div class="progress">
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <small>{{ number_format($percentage, 2) }}%</small>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const paymentLabels = [
            @foreach($salesByPaymentMethod as $method)
                "{{ $method->payment_method }}",
            @endforeach
        ];
        
        const paymentTotals = [
            @foreach($salesByPaymentMethod as $method)
                {{ $method->total }},
            @endforeach
        ];
        
        const transactionCounts = [
            @foreach($salesByPaymentMethod as $method)
                {{ $method->count }},
            @endforeach
        ];
        
        // Payment Method Distribution Chart
        const paymentMethodCtx = document.getElementById('paymentMethodChart').getContext('2d');
        const paymentMethodChart = new Chart(paymentMethodCtx, {
            type: 'pie',
            data: {
                labels: paymentLabels,
                datasets: [{
                    data: paymentTotals,
                    backgroundColor: [
                        '#4B49AC',
                        '#FFC100',
                        '#248AFD',
                        '#FF4747',
                        '#57B657'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
        
        // Transaction Count Chart
        const transactionCountCtx = document.getElementById('transactionCountChart').getContext('2d');
        const transactionCountChart = new Chart(transactionCountCtx, {
            type: 'bar',
            data: {
                labels: paymentLabels,
                datasets: [{
                    label: 'Quantidade de Transações',
                    data: transactionCounts,
                    backgroundColor: '#FFC100',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>
@endsection