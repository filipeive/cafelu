@extends('layouts.app')

@section('title', 'Meu Perfil')
@section('page-title', 'Meu Perfil')
@section('title-icon', 'mdi-account-circle')

@section('content')
<div class="container">
    <div class="card p-4">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('profile.save') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3 text-center">
                <img src="{{ $user->avatar ? asset('storage/'.$user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=fff' }}" 
                     alt="Avatar" id="preview-image" class="rounded-circle" style="width:120px;height:120px;object-fit:cover;">
            </div>

            <div class="mb-3">
                <label class="form-label">Foto</label>
                <input type="file" name="photo" class="form-control" accept="image/*">
                @error('photo') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Nome</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                @error('name') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                @error('email') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" value="{{ old('username', $user->username) }}" required>
                @error('username') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Senha (deixe em branco para manter)</label>
                <input type="password" name="password" class="form-control" autocomplete="new-password">
                @error('password') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Confirme a senha</label>
                <input type="password" name="password_confirmation" class="form-control" autocomplete="new-password">
            </div>

            <div class="d-flex gap-2">
                <a href="{{ url()->previous() }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Salvar</button>
            </div>
        </form>
    </div>
</div>
@endsection