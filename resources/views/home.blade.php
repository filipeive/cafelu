@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header text-center">
                    <h2>{{ __('Bem-vindo ao Lu & Yosh Catering - Café Lufamina') }}</h2>
                </div>

                <div class="card-body">
                    <div class="text-center mb-4">
                        <p>{{ __('Explore nosso sistema para gerenciar pedidos, cardápios e muito mais!') }}</p>
                    </div>

                    <div class="row text-center">
                        <div class="col-md-4">
                            <a href="{{ route('menu.index') }}" class="btn btn-primary btn-block">
                                {{ __('Gerenciar Cardápio') }}
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('orders.index') }}" class="btn btn-success btn-block">
                                {{ __('Ver Pedidos') }}
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('reports.index') }}" class="btn btn-warning btn-block">
                                {{ __('Relatórios') }}
                            </a>
                        </div>
                    </div>

                    <div class="mt-5 text-center">
                        <img src="{{ asset('assets/images/Logo.png') }}" alt="Logo do Restaurante" class="img-fluid" style="max-height: 200px;">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
