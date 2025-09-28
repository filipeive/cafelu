@extends('layouts.app')

@section('title', '419 - Sessão Expirada')
@section('title-icon', 'mdi-clock-alert')
@section('page-title', 'Sessão Expirada')

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ url('/') }}" class="text-decoration-none">
            <i class="mdi mdi-home"></i> Início
        </a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">Erro 419</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center p-5">
                    <div class="error-icon mb-4">
                        <i class="mdi mdi-clock-alert display-1 text-warning"></i>
                    </div>
                    
                    <h1 class="display-4 fw-bold text-warning mb-3">419</h1>
                    <h3 class="mb-3">Sessão Expirada</h3>
                    
                    <p class="text-muted mb-4">
                        Sua sessão expirou por inatividade ou por um problema de segurança. 
                        Por favor, faça login novamente para continuar.
                    </p>
                    
                    <div class="d-flex gap-3 justify-content-center flex-wrap">
                        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary px-4">
                            <i class="mdi mdi-arrow-left me-2"></i>Voltar
                        </a>
                        <a href="{{ url('/login') }}" class="btn btn-primary px-4">
                            <i class="mdi mdi-login me-2"></i>Fazer Login
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection