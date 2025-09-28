@extends('layouts.auth')

@section('title', 'Registrar-se')

@section('form-title', 'Crie sua Conta')
@section('form-subtitle', 'Preencha os campos abaixo para registrar-se no sistema')

@section('form-content')
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Nome -->
        <div class="form-group">
            <label for="name" class="form-label">Nome Completo</label>
            <div class="input-group">
                <i class="mdi mdi-account input-icon"></i>
                <input id="name" type="text" 
                    class="form-control with-icon @error('name') is-invalid @enderror" 
                    name="name" value="{{ old('name') }}" required autofocus>
            </div>
            @error('name')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <!-- Nome de usuário -->
        <div class="form-group">
            <label for="username" class="form-label">Usuário</label>
            <div class="input-group">
                <i class="mdi mdi-account-circle input-icon"></i>
                <input id="username" type="text" 
                    class="form-control with-icon @error('username') is-invalid @enderror" 
                    name="username" value="{{ old('username') }}" required>
            </div>
            @error('username')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <!-- Email -->
        <div class="form-group">
            <label for="email" class="form-label">E-mail</label>
            <div class="input-group">
                <i class="mdi mdi-email input-icon"></i>
                <input id="email" type="email" 
                    class="form-control with-icon @error('email') is-invalid @enderror" 
                    name="email" value="{{ old('email') }}" required>
            </div>
            @error('email')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <!-- Senha -->
        <div class="form-group">
            <label for="password" class="form-label">Senha</label>
            <div class="input-group">
                <i class="mdi mdi-lock input-icon"></i>
                <input id="password" type="password" 
                    class="form-control with-icon @error('password') is-invalid @enderror" 
                    name="password" required autocomplete="new-password">
                <button type="button" class="password-toggle">
                    <i class="mdi mdi-eye-outline"></i>
                </button>
            </div>
            @error('password')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <!-- Confirmar Senha -->
        <div class="form-group">
            <label for="password-confirm" class="form-label">Confirmar Senha</label>
            <div class="input-group">
                <i class="mdi mdi-lock-check input-icon"></i>
                <input id="password-confirm" type="password" 
                    class="form-control with-icon" 
                    name="password_confirmation" required autocomplete="new-password">
            </div>
        </div>

        <!-- Botão -->
        <div class="d-grid">
            <button type="submit" class="btn btn-primary">
                Registrar-se
            </button>
        </div>

        <!-- Link de login -->
        <div class="auth-links mt-3">
            <p>Já tem uma conta? <a href="{{ route('login') }}">Entrar</a></p>
        </div>
    </form>
@endsection
