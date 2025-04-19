@extends('layouts.app')


@section('title', 'Vendas por Data')

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-4">
                        <h4 class="card-title">Relatório de Vendas por Data</h4>
                        <a href="{{ route('reports.index') }}" class="btn btn-sm btn-outline-primary">Voltar</a>
                    </div>
                    
                    <form action="{{ route('reports.salesByDate') }}" method="GET" class="mb-4">
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
                    
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <p class="mb-2">Total de Vendas</p>
                                            <h3 class="mb-0">MZN {{ number_format($salesByDate->sum('total'), 2, ',', '.') }}</h3>
                                        </div>
                                        <i class="mdi mdi-cash-multiple icon-lg"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <p class="mb-2">Média Diária</p>
                                            <h3 class="mb-0">MZN {{ number_format($salesByDate->avg('total'), 2, ',', '.') }}</h3>
                                        </div>
                                        <i class="mdi mdi-chart-line icon-lg"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <p class="mb-2">Período</p>
                                            <h3 class="mb-0">{{ $salesByDate->count() }} dias</h3>
                                        </div>
                                        <i class="mdi mdi-calendar-range icon-lg"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12 grid-margin">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Evolução de Vendas no Período</h5>
                                    <canvas id="salesTrendChart" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Detalhamento de Vendas por Data</h5>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Data</th>
                                                    <th>Valor Total</th>
                                                    <th>% do Total</th>
                                                    <th>Comparação</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php 
                                                    $totalSales = $salesByDate->sum('total');
                                                    $avgSales = $salesByDate->avg('total');
                                                @endphp
                                                @foreach($salesByDate as $sale)
                                                <tr>
                                                    <td>{{ \Carbon\Carbon::parse($sale->date)->format('d/m/Y') }}</td>
                                                    <td>MZN {{ number_format($sale->total, 2, ',', '.') }}</td>
                                                    <td>
                                                        @php $percentage = ($sale->total / $totalSales) * 100; @endphp
                                                        <div class="progress">
                                                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                        <small>{{ number_format($percentage, 2) }}%</small>
                                                    </td>
                                                    <td>
                                                        @if($sale->total > $avgSales)
                                                            <span class="badge bg-success">
                                                                <i class="mdi mdi-arrow-up-bold"></i>
                                                                {{ number_format((($sale->total / $avgSales) - 1) * 100, 2) }}% acima da média
                                                            </span>
                                                        @elseif($sale->total < $avgSales)
                                                            <span class="badge bg-danger">
                                                                <i class="mdi mdi-arrow-down-bold"></i>
                                                                {{ number_format((1 - ($sale->total / $avgSales)) * 100, 2) }}% abaixo da média
                                                            </span>
                                                        @else
                                                            <span class="badge bg-secondary">
                                                                <i class="mdi mdi-equal"></i>
                                                                Na média
                                                            </span>
                                                        @endif
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
                    
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Análise de Desempenho</h5>
                                    @php
                                        $maxSale = $salesByDate->max('total');
                                        $minSale = $salesByDate->min('total');
                                        $maxDate = $salesByDate->where('total', $maxSale)->first()->date;
                                        $minDate = $salesByDate->where('total', $minSale)->first()->date;
                                    @endphp
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="alert alert-success">
                                                <h6><i class="mdi mdi-star"></i> Melhor Dia</h6>
                                                <p class="mb-1">{{ \Carbon\Carbon::parse($maxDate)->format('d/m/Y') }}</p>
                                                <h4>MZN {{ number_format($maxSale, 2, ',', '.') }}</h4>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="alert alert-danger">
                                                <h6><i class="mdi mdi-emoticon-sad"></i> Pior Dia</h6>
                                                <p class="mb-1">{{ \Carbon\Carbon::parse($minDate)->format('d/m/Y') }}</p>
                                                <h4>MZN {{ number_format($minSale, 2, ',', '.') }}</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
        const dateLabels = [
            @foreach($salesByDate as $sale)
                "{{ \Carbon\Carbon::parse($sale->date)->format('d/m') }}",
            @endforeach
        ];
        
        const salesTotals = [
            @foreach($salesByDate as $sale)
                {{ $sale->total }},
            @endforeach
        ];
        
        // Sales Trend Chart
        const salesTrendCtx = document.getElementById('salesTrendChart').getContext('2d');
        const salesTrendChart = new Chart(salesTrendCtx, {
            type: 'line',
            data: {
                labels: dateLabels,
                datasets: [{
                    label: 'Vendas Diárias (MZN)',
                    data: salesTotals,
                    backgroundColor: 'rgba(75, 73, 172, 0.2)',
                    borderColor: '#4B49AC',
                    borderWidth: 2,
                    pointBackgroundColor: '#4B49AC',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Valor (MZN)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Data'
                        }
                    }
                }
            }
        });
    });
</script>
@endsection