@extends('layouts.app')

@section('title', 'Editar Usuário')
@section('page-title', 'Editar Usuário')
@section('title-icon', 'mdi-pencil')

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            @include('users._form', ['user' => $user])

            <div class="d-flex gap-2">
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Salvar</button>
            </div>
        </form>
    </div>
</div>
@endsection