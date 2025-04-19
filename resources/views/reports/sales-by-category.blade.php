@extends('layouts.app')

@section('title', 'Vendas por Categoria')

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-4">
                        <h4 class="card-title">Relatório de Vendas por Categoria</h4>
                        <a href="{{ route('reports.index') }}" class="btn btn-sm btn-outline-primary">Voltar</a>
                    </div>
                    
                    <form action="{{ route('reports.salesByCategory') }}" method="GET" class="mb-4">
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
                                    <h5 class="card-title">Desempenho por Categoria</h5>
                                    <canvas id="categorySalesChart" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 grid-margin">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Distribuição de Vendas</h5>
                                    <canvas id="categoryDistributionChart" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-4">
                            <div class="card bg-danger text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <p class="mb-2">Total de Categorias</p>
                                            <h3 class="mb-0">{{ $salesByCategory->count() }}</h3>
                                        </div>
                                        <i class="mdi mdi-tag-multiple icon-lg"></i>
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
                                            <h3 class="mb-0">MZN {{ number_format($salesByCategory->sum('total'), 2, ',', '.') }}</h3>
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
                                            <p class="mb-2">Média por Categoria</p>
                                            <h3 class="mb-0">MZN {{ number_format($salesByCategory->avg('total'), 2, ',', '.') }}</h3>
                                        </div>
                                        <i class="mdi mdi-chart-pie icon-lg"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Categoria</th>
                                    <th>Quantidade Vendida</th>
                                    <th>Valor Total</th>
                                    <th>% do Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $totalSales = $salesByCategory->sum('total'); @endphp
                                @foreach($salesByCategory as $category)
                                <tr>
                                    <td>{{ $category->name }}</td>
                                    <td>{{ $category->quantity }}</td>
                                    <td>MZN {{ number_format($category->total, 2, ',', '.') }}</td>
                                    <td>
                                        @php $percentage = ($category->total / $totalSales) * 100; @endphp
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
        const categoryLabels = [
            @foreach($salesByCategory as $category)
                "{{ $category->name }}",
            @endforeach
        ];
        
        const categoryQuantities = [
            @foreach($salesByCategory as $category)
                {{ $category->quantity }},
            @endforeach
        ];
        
        const categoryTotals = [
            @foreach($salesByCategory as $category)
                {{ $category->total }},
            @endforeach
        ];
        
        // Category Sales Chart
        const categorySalesCtx = document.getElementById('categorySalesChart').getContext('2d');
        const categorySalesChart = new Chart(categorySalesCtx, {
            type: 'bar',
            data: {
                labels: categoryLabels,
                datasets: [{
                    label: 'Valor Total (MZN)',
                    data: categoryTotals,
                    backgroundColor: '#4B49AC',
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
        
        // Category Distribution Chart
        const categoryDistributionCtx = document.getElementById('categoryDistributionChart').getContext('2d');
        const categoryDistributionChart = new Chart(categoryDistributionCtx, {
            type: 'pie',
            data: {
                labels: categoryLabels,
                datasets: [{
                    data: categoryTotals,
                    backgroundColor: [
                        '#4B49AC',
                        '#FFC100',
                        '#248AFD',
                        '#FF4747',
                        '#57B657',
                        '#7978E9',
                        '#F3797E',
                        '#F89F9F',
                        '#7DA0FA',
                        '#FF8F00'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    });
</script>
@endsection