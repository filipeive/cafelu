@extends('layouts.app')

@section('title', 'Por Categoria')
@section('title-icon', 'mdi-format-list-bulleted')

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('kitchen.dashboard') }}" class="d-flex align-items-center">
            <i class="mdi mdi-chef-hat me-1"></i> Cozinha
        </a>
    </li>
    <li class="breadcrumb-item active d-flex align-items-center">
        <i class="mdi mdi-format-list-bulleted me-1"></i> Por Categoria
    </li>
@endsection

@section('content')
<div class="row g-4">
    <div class="col-12">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-0">
                    <i class="mdi mdi-format-list-bulleted me-2"></i>
                    Pedidos por Categoria
                </h2>
                <p class="text-muted mb-0">Organização dos itens em preparo por categoria</p>
            </div>
            <a href="{{ route('kitchen.dashboard') }}" class="btn btn-outline-secondary">
                <i class="mdi mdi-arrow-left me-2"></i> Voltar
            </a>
        </div>
    </div>

    @if($itemsByCategory->count() > 0)
        @foreach($itemsByCategory as $categoryData)
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h4 class="mb-0">
                        <i class="mdi mdi-food-variant me-2 text-primary"></i>
                        {{ $categoryData['category'] }} ({{ $categoryData['items']->count() }} itens)
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach($categoryData['items'] as $item)
                        <div class="col-xl-4 col-lg-6">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <h6 class="mb-1">{{ $item['product_name'] }}</h6>
                                            <p class="mb-1">
                                                <strong>Pedido #{{ str_pad($item['order_id'], 4, '0', STR_PAD_LEFT) }}</strong>
                                                <br>
                                                Mesa {{ $item['table_number'] }}
                                            </p>
                                        </div>
                                        <span class="badge bg-primary">{{ $item['quantity'] }}x</span>
                                    </div>
                                    
                                    @if($item['notes'])
                                        <div class="alert alert-info p-2 mb-2">
                                            <small class="mb-0">Obs: {{ $item['notes'] }}</small>
                                        </div>
                                    @endif
                                    
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="badge bg-secondary">
                                                {{ $item['elapsed_minutes'] }} min
                                            </span>
                                        </div>
                                        <div>
                                            @switch($item['status'])
                                                @case('pending')
                                                    <span class="badge bg-warning text-dark">Pendente</span>
                                                @break
                                                @case('preparing')
                                                    <span class="badge bg-info text-white">Preparando</span>
                                                @break
                                                @case('ready')
                                                    <span class="badge bg-success text-white">Pronto</span>
                                                @break
                                            @endswitch
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    @else
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="mdi mdi-format-list-bulleted" style="font-size: 3rem; color: #6b7280;"></i>
                    <h5 class="mt-3">Nenhum item em preparo</h5>
                    <p class="text-muted">Aguardando novos pedidos...</p>
                    <a href="{{ route('kitchen.dashboard') }}" class="btn btn-primary mt-3">
                        <i class="mdi mdi-arrow-left me-2"></i> Voltar ao Dashboard
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection