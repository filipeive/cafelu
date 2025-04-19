@extends('layouts.app')


@section('title', 'Vendas por Produto')

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-4">
                        <h4 class="card-title">Relatório de Vendas por Produto</h4>
                        <a href="{{ route('reports.index') }}" class="btn btn-sm btn-outline-primary">Voltar</a>
                    </div>
                    
                    <form action="{{ route('reports.salesByProduct') }}" method="GET" class="mb-4">
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
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Desempenho de Vendas por Produto</h5>
                                    <canvas id="productSalesChart" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-4">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <p class="mb-2">Total de Produtos Vendidos</p>
                                            <h3 class="mb-0">{{ $salesByProduct->sum('quantity') }}</h3>
                                        </div>
                                        <i class="mdi mdi-package-variant icon-lg"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4 mb-4">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <p class="mb-2">Valor Total de Vendas</p>
                                            <h3 class="mb-0">MZN {{ number_format($salesByProduct->sum('total'), 2, ',', '.') }}</h3>
                                        </div>
                                        <i class="mdi mdi-cash-multiple icon-lg"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4 mb-4">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <p class="mb-2">Ticket Médio por Produto</p>
                                            <h3 class="mb-0">MZN {{ number_format($salesByProduct->avg('total'), 2, ',', '.') }}</h3>
                                        </div>
                                        <i class="mdi mdi-chart-areaspline icon-lg"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Produto</th>
                                    <th>Quantidade Vendida</th>
                                    <th>Valor Total</th>
                                    <th>% do Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $totalSales = $salesByProduct->sum('total'); @endphp
                                @foreach($salesByProduct as $product)
                                <tr>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->quantity }}</td>
                                    <td>MZN {{ number_format($product->total, 2, ',', '.') }}</td>
                                    <td>
                                        @php $percentage = ($product->total / $totalSales) * 100; @endphp
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
        // Get the top 10 products for chart
        const productLabels = [
            @foreach($salesByProduct->take(10) as $product)
                "{{ $product->name }}",
            @endforeach
        ];
        
        const productQuantities = [
            @foreach($salesByProduct->take(10) as $product)
                {{ $product->quantity }},
            @endforeach
        ];
        
        const productTotals = [
            @foreach($salesByProduct->take(10) as $product)
                {{ $product->total }},
            @endforeach
        ];
        
        // Product Sales Chart
        const productSalesCtx = document.getElementById('productSalesChart').getContext('2d');
        const productSalesChart = new Chart(productSalesCtx, {
            type: 'bar',
            data: {
                labels: productLabels,
                datasets: [
                    {
                        label: 'Quantidade Vendida',
                        data: productQuantities,
                        backgroundColor: '#4B49AC',
                        borderColor: '#4B49AC',
                        borderWidth: 1,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Valor Total (MZN)',
                        data: productTotals,
                        backgroundColor: '#FFC100',
                        borderColor: '#FFC100',
                        borderWidth: 1,
                        type: 'line',
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Quantidade'
                        }
                    },
                    y1: {
                        beginAtZero: true,
                        position: 'right',
                        grid: {
                            drawOnChartArea: false
                        },
                        title: {
                            display: true,
                            text: 'Valor (MZN)'
                        }
                    }
                }
            }
        });
    });
</script>
@endsection