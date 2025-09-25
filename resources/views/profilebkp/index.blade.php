@extends('layouts.app')
@section('title', 'Profile')



@section('content_header')
    <h1>Meu Perfil</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/painel') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="breadcrumb-item active">Meu Perfil</li>
    </ol>
@endsection

    
@section('content')
 <!-- Cartão para Mensagens de Alerta -->
 @if (session('success'))
 <div class="alert alert-success alert-dismissible fade show" role="alert">
     {{ session('success') }}
     <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
 </div>
@endif
@if (session('error'))
 <div class="alert alert-warning alert-dismissible fade show" role="alert">
     {{ session('error') }}
     <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
 </div>
@endif
@if (session('updated'))
 <div class="alert alert-success alert-dismissible fade show" role="alert">
     {{ session('updated') }}
     <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
 </div>
@endif
@if (session('deleted'))
 <div class="alert alert-danger alert-dismissible fade show" role="alert">
     {{ session('deleted') }}
     <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
 </div>
@endif

<!-- Formulário para Editar Usuário -->
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            <h5>
                <i class="icon fas fa-ban"></i>
                Ocorreu um erro...
            </h5>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Editar Usuário</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('save') }}">
            @csrf
            @method('PUT')
            <div class="form-group row">
                <label for="name" class="col-sm-2 col-form-label">Nome Completo</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="name" name="name"
                        value="{{ $user->name }}">
                </div>
            </div>
            <div class="form-group row">
                <label for="email" class="col-sm-2 col-form-label">Email</label>
                <div class="col-sm-10">
                    <input type="email" class="form-control" id="email" name="email"
                        value="{{ $user->email }}">
                    <small id="emailHelp" class="form-text text-muted">Este email não será alterado.</small>
                    <input type="hidden" name="email_original" value="{{ $user->email }}">
                </div>
            </div>
            <div class="form-group row">
                <label for="password" class="col-sm-2 col-form-label">Senha</label>
                <div class="col-sm-10">
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                        name="password">
                </div>
            </div>
            <div class="form-group row">
                <label for="password_confirmation" class="col-sm-2 col-form-label">Nova Senha</label>
                <div class="col-sm-10">
                    <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                        id="password_confirmation" name="password_confirmation">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-10 offset-sm-2">
                    <button type="submit" class="btn btn-primary">Salvar</button>
                    <a href="{{ route('home') }}" class="btn btn-default">Voltar</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection


