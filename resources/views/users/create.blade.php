@extends('layouts.app')

@section('content')
    <div class="w-full">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <i class="mdi mdi-account-plus text-orange-500"></i>
                Novo Usuário
            </h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Cadastre um novo usuário no sistema.</p>
        </div>

        <div
            class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <form action="{{ route('users.store') }}" method="POST" class="p-8">
                @csrf

                @include('users._form', ['user' => null])

                <div class="mt-8 flex items-center justify-end gap-3">
                    <a href="{{ route('users.index') }}"
                        class="px-6 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 font-bold rounded-xl hover:bg-gray-50 transition-all">
                        Cancelar
                    </a>
                    <button type="submit"
                        class="px-8 py-2 bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-xl transition-all shadow-lg shadow-orange-500/20">
                        Criar Usuário
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection