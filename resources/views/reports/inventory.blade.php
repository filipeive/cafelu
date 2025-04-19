@extends('layouts.app')


@section('title', 'Relatório de Estoque')

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-4">
                        <h4 class="card-title">Relatório de Estoque - Produtos com Baixo Estoque</h4>
                        <a href="{{ route('reports.index') }}" class="btn btn-sm btn-outline-primary">Voltar</a>
                    </div>
                    
                    <form action="{{ route('reports.inventory') }}" method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="low_stock_threshold">Limite de Estoque Baixo</label>
                                    <input type="number" class="form-control" id="low_stock_threshold" name="low_stock_threshold" min="1" value="{{ $lowStockThreshold }}">
                                </div>
                            </div>
                            <div class="col-md-6 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary">Filtrar</button>
                            </div>
                        </div>
                    </form>
                    
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card bg-danger text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <p class="mb-2">Produtos com Estoque Crítico</p>
                                            <h3 class="mb-0">{{ $lowStockProducts->where('stock_quantity', '<', 5)->count() }}</h3>
                                        </div>
                                        <i class="mdi mdi-alert-circle-outline icon-lg"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card bg-warning text-dark">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <p class="mb-2">Total Produtos com Estoque Baixo</p>
                                            <h3 class="mb-0">{{ $lowStockProducts->count() }}</h3>
                                        </div>
                                        <i class="mdi mdi-alert icon-lg"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <p class="mb-2">Limite Definido</p>
                                            <h3 class="mb-0">{{ $lowStockThreshold }} unidades</h3>
                                        </div>
                                        <i class="mdi mdi-format-list-bulleted icon-lg"></i>
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
                                    <th>Categoria</th>
                                    <th>Estoque Atual</th>
                                    <th>Status</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lowStockProducts as $product)
                                <tr>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->category->name }}</td>
                                    <td>{{ $product->stock_quantity }}</td>
                                    <td>
                                        @if ($product->stock_quantity <= 0)
                                            <span class="badge bg-danger">Sem estoque</span>
                                        @elseif ($product->stock_quantity < 5)
                                            <span class="badge bg-danger">Crítico</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Baixo</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-primary">Repor</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">Nenhum produto com estoque baixo encontrado.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection