@extends('layouts.auth')

@section('title', 'Redefinir Senha')

@section('content')
    <div class="flex justify-center">
        <div
            class="bg-white/90 dark:bg-gray-900/95 backdrop-blur-md rounded-2xl shadow-2xl p-8 w-full max-w-md border border-gray-200 dark:border-gray-700">
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                    Redefinir Senha
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                    Informe seu e-mail para receber o link de redefinição.
                </p>
            </div>

            @if (session('status'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded" role="alert">
                    <p>{{ session('status') }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Email Address') }}</label>
                    <input id="email" type="email"
                        class="w-full px-4 py-2 rounded-lg bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition duration-200 @error('email') border-red-500 @enderror"
                        name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Botão -->
                <div>
                    <button type="submit"
                        class="w-full bg-primary hover:bg-primary-hover text-white font-semibold py-3 px-6 rounded-full transform hover:-translate-y-0.5 transition duration-200 shadow-lg">
                        {{ __('Send Password Reset Link') }}
                    </button>
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('login') }}" class="text-sm text-primary hover:text-primary-hover font-medium">
                        <i class="mdi mdi-arrow-left mr-1"></i> Voltar para o Login
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection