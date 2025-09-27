@extends('layouts.app')

@section('title', 'Página Não Encontrada')
@section('title-icon', 'mdi-alert-circle-outline')
@section('page-title', 'Página Não Encontrada')

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ url('/') }}" class="text-decoration-none">
            <i class="mdi mdi-home"></i> Início
        </a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">Página Não Encontrada</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center p-5">
                    <div class="error-icon mb-4">
                        <i class="mdi mdi-alert-circle-outline display-1 text-warning"></i>
                    </div>
                    
                    <h1 class="display-4 fw-bold text-warning mb-3">Ops!</h1>
                    <h3 class="mb-3">Página Não Encontrada</h3>
                    
                    <p class="text-muted mb-4">
                        O endereço que você tentou acessar não existe ou pode ter sido movido. 
                        Verifique o URL ou navegue usando o menu principal.
                    </p>
                    
                    <div class="d-flex gap-3 justify-content-center flex-wrap">
                        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary px-4">
                            <i class="mdi mdi-arrow-left me-2"></i>Voltar
                        </a>
                        <a href="{{ url('/dashboard') }}" class="btn btn-primary px-4">
                            <i class="mdi mdi-home me-2"></i>Ir para o Dashboard
                        </a>
                    </div>

                    <div class="mt-4">
                        <small class="text-muted">
                            Se você acredita que isso é um erro, entre em contato com o suporte.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.error-icon {
    opacity: 0.8;
}
.card {
    border-radius: 12px;
    border: 1px solid #e2e8f0;
}
.display-4 {
    font-size: 4rem;
    font-weight: 700;
}
@media (max-width: 768px) {
    .card-body {
        padding: 2rem 1.5rem;
    }
    .display-4 {
        font-size: 3rem;
    }
}
</style>
@endpush