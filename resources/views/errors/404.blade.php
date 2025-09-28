@extends('layouts.app')

@section('title', '404 - Página Não Encontrada')
@section('title-icon', 'mdi-map-marker-off')
@section('page-title', 'Página Não Encontrada')

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ url('/') }}" class="text-decoration-none">
            <i class="mdi mdi-home"></i> Início
        </a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">Erro 404</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center p-5">
                    <div class="error-icon mb-4">
                        <i class="mdi mdi-map-marker-off display-1 text-warning"></i>
                    </div>
                    
                    <h1 class="display-4 fw-bold text-warning mb-3">404</h1>
                    <h3 class="mb-3">Página Não Encontrada</h3>
                    
                    <p class="text-muted mb-4">
                        O endereço que você tentou acessar não existe ou foi removido. 
                        Verifique se o URL está correto e tente novamente.
                    </p>
                    
                    <div class="d-flex gap-3 justify-content-center flex-wrap">
                        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary px-4">
                            <i class="mdi mdi-arrow-left me-2"></i>Voltar
                        </a>
                        <a href="{{ url('/dashboard') }}" class="btn btn-primary px-4">
                            <i class="mdi mdi-home me-2"></i>Ir para o Início
                        </a>
                    </div>
                    
                    @if(app()->environment('local'))
                    <div class="mt-4 p-3 bg-light rounded">
                        <small class="text-muted">
                            <strong>URL:</strong> {{ request()->url() }}
                        </small>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection