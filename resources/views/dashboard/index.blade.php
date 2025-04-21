<!-- resources/views/dashboard/index.blade.php -->
@extends('layouts.app')

@section('styles')
    <style>
        :root {
            --primary-color: #4f46e5;
            --success-color: #22c55e;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
        }

        .dashboard-container {
            padding: 1.5rem;
            background-color: rgba(255, 255, 255, 0.2);
            min-height: 100vh;
            border-radius: 10px;
        }

        .stats-card {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .icon-container {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }

        .quick-action {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
            border: 1px solid #e5e7eb;
            cursor: pointer;
        }

        .quick-action:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .quick-action .icon {
            transition: transform 0.3s ease;
        }

        .quick-action:hover .icon {
            transform: scale(1.1);
        }

        .table-status {
            border-radius: 0.75rem;
            padding: 1rem;
            transition: all 0.3s ease;
        }

        .table-status:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .chart-container {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-top: 1.5rem;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.5s ease forwards;
        }

        .header-title {
            font-size: 1.875rem;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 2rem;
        }

        /* Gradientes para os cards */
        .gradient-primary {
            background: linear-gradient(135deg, #4f46e5, #6366f1);
        }

        .gradient-success {
            background: linear-gradient(135deg, #22c55e, #16a34a);
        }

        .gradient-danger {
            background: linear-gradient(135deg, #ef4444, #dc2626);
        }

        .gradient-warning {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }
    </style>
@endsection

@section('content')
    <!-- Conteúdo do Dashboard -->
    <div class="dashboard-container">
        <!-- Cabeçalho -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="header-title animate-fade-in">Dashboard <strong style="color: var(--warning-color)"> Café
                    Lufamina</strong></h1>
            <a href="{{ route('pos.index') }}" class="btn btn-primary">
                <i class="mdi mdi-cash-register me-2"></i>
                Abrir PDV
            </a>
        </div>

        <!-- Cards de Estatísticas -->
        <div class="row g-4 mb-4">
            <div class="col-md-3 animate-fade-in" style="animation-delay: 0.1s;">
                <div class="stats-card gradient-primary text-white">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="icon-container bg-white bg-opacity-25">
                                <i class="mdi mdi-cash-multiple mdi-24px text-white"></i>
                            </div>
                            <h6 class="card-title mb-2">Vendas Hoje</h6>
                            <h3 class="mb-0">MZN {{ number_format($totalSalesToday, 2, ',', '.') }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 animate-fade-in" style="animation-delay: 0.2s;">
                <div class="stats-card gradient-success text-white">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="icon-container bg-white bg-opacity-25">
                                <i class="mdi mdi-food-fork-drink mdi-24px text-white"></i>
                            </div>
                            <h6 class="card-title mb-2">Pedidos Abertos</h6>
                            <h3 class="mb-0">{{ $openOrders }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 animate-fade-in" style="animation-delay: 0.3s;">
                <div class="stats-card gradient-danger text-white">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="icon-container bg-white bg-opacity-25">
                                <i class="mdi mdi-alert-circle mdi-24px text-white"></i>
                            </div>
                            <h6 class="card-title mb-2">Estoque Baixo</h6>
                            <h3 class="mb-0">{{ $lowStockProducts->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 animate-fade-in" style="animation-delay: 0.4s;">
                <div class="stats-card gradient-warning text-white">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="icon-container bg-white bg-opacity-25">
                                <i class="mdi mdi-table-furniture mdi-24px text-white"></i>
                            </div>
                            <h6 class="card-title mb-2">Total de Mesas</h6>
                            <h3 class="mb-0">{{ $tables->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ações Rápidas -->
        <div class="card mb-4 animate-fade-in" style="animation-delay: 0.5s;">
            <div class="card-body">
                <h4 class="card-title mb-4">
                    <i class="mdi mdi-lightning-bolt text-warning me-2"></i>
                    Ações Rápidas
                </h4>
                <div class="row g-4">
                    @php
                        $quick_actions = [
                            [
                                'icon' => 'mdi-table-large',
                                'title' => 'Gerenciar Mesas',
                                'desc' => 'Controle de mesas',
                                'route' => 'tables.index',
                                'color' => 'primary',
                            ],
                            [
                                'icon' => 'mdi-cart',
                                'title' => 'Pedidos',
                                'desc' => 'Gerir Pedidos',
                                'route' => 'orders.index',
                                'color' => 'success',
                            ],
                            [
                                'icon' => 'mdi-chart-bar',
                                'title' => 'Relatórios',
                                'desc' => 'Análise de dados',
                                'route' => 'reports.index',
                                'color' => 'info',
                            ],
                            [
                                'icon' => 'mdi-cash-register',
                                'title' => 'Venda Rápida',
                                'desc' => 'PDV rápido',
                                'route' => 'pos.index',
                                'color' => 'warning',
                            ],
                        ];
                    @endphp

                    @foreach ($quick_actions as $action)
                        <div class="col-md-3">
                            <a href="{{ route($action['route']) }}" class="text-decoration-none">
                                <div class="quick-action">
                                    <div class="icon-container bg-{{ $action['color'] }} bg-opacity-10 mx-auto mb-3">
                                        <i class="mdi {{ $action['icon'] }} mdi-24px text-{{ $action['color'] }} icon"></i>
                                    </div>
                                    <h5 class="font-weight-bold">{{ $action['title'] }}</h5>
                                    <p class="text-muted mb-0">{{ $action['desc'] }}</p>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Gráfico de Vendas por Hora -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Vendas por Hora</h4>
                        <div class="chart-container" style="position: relative; height:300px;">
                            <livewire:hourly-sales-chart />
                        </div>
                    </div>
                </div>
            </div> 


            <!-- Status das Mesas -->
            <div class="col-md-6 animate-fade-in" style="animation-delay: 0.7s;">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Status das Mesas</h4>
                        <div class="row g-3">
                            @foreach ($tables as $table)
                                <div class="col-6">
                                    <div
                                        class="table-status {{ $table->status === 'occupied' ? 'bg-danger' : 'bg-success' }} bg-opacity-10">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6
                                                    class="mb-1 {{ $table->status === 'occupied' ? 'text-danger' : 'text-success' }}">
                                                    Mesa {{ $table->number }}
                                                </h6>
                                                <small
                                                    class="{{ $table->status === 'occupied' ? 'text-danger' : 'text-success' }}">
                                                    {{ $table->status === 'occupied' ? 'Ocupada' : 'Livre' }}
                                                </small>
                                            </div>
                                            <a href="{{ route('tables.index', $table->id) }}"
                                                class="btn btn-sm {{ $table->status === 'occupied' ? 'btn-outline-danger' : 'btn-outline-success' }}">
                                                Gerenciar
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Produtos com Estoque Baixo -->
        <div class="row mt-4">
            <div class="col-md-12 animate-fade-in" style="animation-delay: 0.8s;">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Produtos com Estoque Baixo</h4>
                        <div class="table-responsive">
                            <table class="table table-striped table-borderless">
                                <thead>
                                    <tr>
                                        <th>Produto</th>
                                        <th>Categoria</th>
                                        <th>Estoque Atual</th>
                                        <th>Preço</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($lowStockProducts as $product)
                                        <tr>
                                            <td>{{ $product->name }}</td>
                                            <td>{{ $product->category ? $product->category->name : 'N/A' }}</td>
                                            <td>
                                                <span class="badge bg-danger">{{ $product->stock_quantity }}</span>
                                            </td>
                                            <td>MZN {{ number_format($product->price, 2, ',', '.') }}</td>
                                            <td>
                                                <a href="{{ route('products.edit', $product->id) }}"
                                                    class="btn btn-sm btn-primary">
                                                    <i class="mdi mdi-pencil"></i>
                                                </a>
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

