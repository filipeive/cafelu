@extends('layouts.app')

@section('title', 'Meu Perfil')

@section('content')
    <div class="w-full">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">Meu Perfil</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Gerencie suas informações pessoais e segurança da conta.</p>
        </div>

        @if(session('success'))
            <div
                class="mb-6 p-4 bg-green-100 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-xl text-green-700 dark:text-green-300 flex items-center gap-3">
                <i class="mdi mdi-check-circle text-xl"></i>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <form action="{{ route('customer.profile.update') }}" method="POST" class="p-8 space-y-8">
                @csrf
                @method('PUT')

                <!-- Personal Info Section -->
                <div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                        <i class="mdi mdi-account text-orange-500"></i> Informações Pessoais
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Nome
                                Completo</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                                class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 text-gray-900 dark:text-white focus:ring-orange-500 focus:border-orange-500 transition-all">
                            @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="email"
                                class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">E-mail</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                                class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 text-gray-900 dark:text-white focus:ring-orange-500 focus:border-orange-500 transition-all">
                            @error('email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="phone"
                                class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Telefone</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                                class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 text-gray-900 dark:text-white focus:ring-orange-500 focus:border-orange-500 transition-all"
                                placeholder="Ex: +258 84 000 0000">
                            @error('phone') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <hr class="border-gray-100 dark:border-gray-700">

                <!-- Security Section -->
                <div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                        <i class="mdi mdi-lock text-orange-500"></i> Segurança (Opcional)
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Deixe em branco se não desejar alterar sua
                        senha.</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="password" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Nova
                                Senha</label>
                            <input type="password" name="password" id="password"
                                class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 text-gray-900 dark:text-white focus:ring-orange-500 focus:border-orange-500 transition-all">
                            @error('password') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="password_confirmation"
                                class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Confirmar Nova
                                Senha</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 text-gray-900 dark:text-white focus:ring-orange-500 focus:border-orange-500 transition-all">
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="pt-6 flex justify-end gap-4">
                    <a href="{{ route('customer.dashboard') }}"
                        class="px-6 py-3 rounded-xl font-bold text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        Cancelar
                    </a>
                    <button type="submit"
                        class="px-8 py-3 bg-orange-500 text-white rounded-xl font-bold hover:bg-orange-600 transition-all shadow-lg shadow-orange-500/30 flex items-center gap-2">
                        <i class="mdi mdi-content-save"></i> Salvar Alterações
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection