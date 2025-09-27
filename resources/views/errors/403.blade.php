@extends('layouts.app')

@section('title', '403 - Acesso Negado')
@section('title-icon', 'mdi-lock-alert')
@section('page-title', 'Acesso Negado')

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ url('/') }}" class="text-decoration-none">
            <i class="mdi mdi-home"></i> Início
        </a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">Erro 403</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center p-5">
                    <div class="error-icon mb-4">
                        <i class="mdi mdi-lock-alert display-1 text-danger"></i>
                    </div>
                    
                    <h1 class="display-4 fw-bold text-danger mb-3">403</h1>
                    <h3 class="mb-3">Acesso Negado</h3>
                    
                    <p class="text-muted mb-4">
                        Você não tem permissão para acessar esta página. 
                        Verifique suas credenciais ou entre em contato com o administrador.
                    </p>
                    
                    <div class="d-flex gap-3 justify-content-center flex-wrap">
                        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary px-4">
                            <i class="mdi mdi-arrow-left me-2"></i>Voltar
                        </a>
                        <a href="{{ url('/dashboard') }}" class="btn btn-primary px-4">
                            <i class="mdi mdi-home me-2"></i>Ir para o Início
                        </a>
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
}
</style>
@endpush