@extends('layouts.app')

@section('title', 'Novo Usuário')
@section('page-title', 'Novo Usuário')
@section('title-icon', 'mdi-account-plus')

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('users.store') }}" method="POST">
            @csrf

            @include('users._form', ['user' => null])

            <div class="d-flex gap-2">
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Criar Usuário</button>
            </div>
        </form>
    </div>
</div>
@endsection