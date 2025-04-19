@extends('layouts.app')

@section('title', 'Relatório de Vendas')

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-4">
                        <h4 class="card-title">Relatório de Vendas</h4>
                        <a href="{{ route('reports.index') }}" class="btn btn-sm btn-outline-primary">Voltar</a>
                    </div>
                    
                    <form action="{{ route('reports.sales') }}" method="GET" class="mb-4">
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
                        <div class="col-md-8 grid-margin">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Vendas Diárias</h5>
                                    <canvas id="dailySalesChart" height="250"></canvas>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4 grid-margin">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Métodos de Pagamento</h5>
                                    <canvas id="paymentMethodsChart" height="250"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 grid-margin">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Top 10 Produtos Mais Vendidos</h5>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Produto</th>
                                                    <th>Quantidade</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($topProducts as $product)
                                                <tr>
                                                    <td>{{ $product->name }}</td>
                                                    <td>{{ $product->quantity }}</td>
                                                    <td>MZN {{ number_format($product->total, 2, ',', '.') }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 grid-margin">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Vendas por Categoria</h5>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Categoria</th>
                                                    <th>Quantidade</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($salesByCategory as $category)
                                                <tr>
                                                    <td>{{ $category->name }}</td>
                                                    <td>{{ $category->quantity }}</td>
                                                    <td>MZN {{ number_format($category->total, 2, ',', '.') }}</td>
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
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Daily Sales Chart
        const dailySalesCtx = document.getElementById('dailySalesChart').getContext('2d');
        const dailySalesChart = new Chart(dailySalesCtx, {
            type: 'line',
            data: {
                labels: [
                    @foreach($dailySales as $sale)
                        '{{ \Carbon\Carbon::parse($sale->date)->format("d/m") }}',
                    @endforeach
                ],
                datasets: [{
                    label: 'Vendas Diárias (MZN)',
                    data: [
                        @foreach($dailySales as $sale)
                            {{ $sale->total }},
                        @endforeach
                    ],
                    borderColor: '#4B49AC',
                    tension: 0.1,
                    fill: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
        
        // Payment Methods Chart
        const paymentMethodsCtx = document.getElementById('paymentMethodsChart').getContext('2d');
        const paymentMethodsChart = new Chart(paymentMethodsCtx, {
            type: 'doughnut',
            data: {
                labels: [
                    @foreach($paymentMethods as $method)
                        '{{ $method->payment_method }}',
                    @endforeach
                ],
                datasets: [{
                    data: [
                        @foreach($paymentMethods as $method)
                            {{ $method->total }},
                        @endforeach
                    ],
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
    });
</script>
@endsection