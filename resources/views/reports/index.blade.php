@extends('layouts.app')

@section('title', 'Dashboard de Relatórios')

@section('content')
    <div class="row mb-4">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Relatórios do Sistema</h4>
                    <p class="card-description">Selecione o tipo de relatório que deseja visualizar</p>
                    
                    <div class="row mt-4">
                        <div class="col-md-4 mb-4">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <i class="mdi mdi-cash-multiple text-primary icon-lg mb-3"></i>
                                    <h5>Relatório de Vendas</h5>
                                    <p class="text-muted">Resumo geral das vendas realizadas</p>
                                    <a href="{{ route('reports.sales') }}" class="btn btn-primary">Visualizar</a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4 mb-4">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <i class="mdi mdi-package-variant text-success icon-lg mb-3"></i>
                                    <h5>Relatório de Estoque</h5>
                                    <p class="text-muted">Produtos com estoque baixo</p>
                                    <a href="{{ route('reports.inventory') }}" class="btn btn-success">Visualizar</a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4 mb-4">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <i class="mdi mdi-food text-info icon-lg mb-3"></i>
                                    <h5>Vendas por Produto</h5>
                                    <p class="text-muted">Análise de vendas por produto</p>
                                    <a href="{{ route('reports.salesByProduct') }}" class="btn btn-info">Visualizar</a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4 mb-4">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <i class="mdi mdi-tag-multiple text-danger icon-lg mb-3"></i>
                                    <h5>Vendas por Categoria</h5>
                                    <p class="text-muted">Análise de vendas por categoria</p>
                                    <a href="{{ route('reports.salesByCategory') }}" class="btn btn-danger">Visualizar</a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4 mb-4">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <i class="mdi mdi-credit-card-multiple text-warning icon-lg mb-3"></i>
                                    <h5>Vendas por Forma de Pagamento</h5>
                                    <p class="text-muted">Análise por método de pagamento</p>
                                    <a href="{{ route('reports.salesByPaymentMethod') }}" class="btn btn-warning">Visualizar</a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4 mb-4">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <i class="mdi mdi-calendar-text text-secondary icon-lg mb-3"></i>
                                    <h5>Vendas por Data</h5>
                                    <p class="text-muted">Análise de vendas por período</p>
                                    <a href="{{ route('reports.salesByDate') }}" class="btn btn-secondary">Visualizar</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
@endsection