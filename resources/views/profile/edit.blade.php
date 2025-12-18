@extends('layouts.app')

@section('title', 'Meu Perfil')

@section('content')
    <div class="w-full max-w-4xl mx-auto">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <i class="mdi mdi-account-circle text-orange-500"></i>
                    Meu Perfil
                </h1>
                <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Gerencie suas informações pessoais e credenciais de
                    acesso.</p>
            </div>
        </div>

        <div
            class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <form action="{{ route('profile.save') }}" method="POST" enctype="multipart/form-data" class="p-8">
                @csrf
                @method('PUT')

                <div class="flex flex-col md:flex-row gap-12">
                    <!-- Avatar Section -->
                    <div class="flex flex-col items-center space-y-4">
                        <div class="relative group">
                            <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=fff' }}"
                                alt="Avatar" id="preview-image"
                                class="w-40 h-40 rounded-full object-cover border-4 border-white dark:border-gray-700 shadow-xl transition-transform group-hover:scale-105">
                            <label
                                class="absolute bottom-2 right-2 w-10 h-10 bg-orange-500 hover:bg-orange-600 text-white rounded-full flex items-center justify-center cursor-pointer shadow-lg transition-all">
                                <i class="mdi mdi-camera text-xl"></i>
                                <input type="file" name="photo" class="hidden" accept="image/*"
                                    onchange="previewFile(this)">
                            </label>
                        </div>
                        <div class="text-center">
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Foto de Perfil</span>
                            @error('photo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Form Fields -->
                    <div class="flex-1 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label
                                    class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2 tracking-wider">Nome
                                    Completo</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                    class="w-full px-4 py-3 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all outline-none">
                                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label
                                    class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2 tracking-wider">Nome
                                    de Usuário</label>
                                <input type="text" name="username" value="{{ old('username', $user->username) }}" required
                                    class="w-full px-4 py-3 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all outline-none">
                                @error('username') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label
                                    class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2 tracking-wider">E-mail</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                    class="w-full px-4 py-3 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all outline-none">
                                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="pt-6 border-t border-gray-100 dark:border-gray-700">
                            <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                <i class="mdi mdi-lock text-orange-500"></i>
                                Alterar Senha
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label
                                        class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2 tracking-wider">Nova
                                        Senha</label>
                                    <input type="password" name="password" autocomplete="new-password"
                                        class="w-full px-4 py-3 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all outline-none"
                                        placeholder="Deixe em branco para manter">
                                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label
                                        class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2 tracking-wider">Confirmar
                                        Senha</label>
                                    <input type="password" name="password_confirmation" autocomplete="new-password"
                                        class="w-full px-4 py-3 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all outline-none">
                                </div>
                            </div>
                        </div>

                        <div class="pt-8 flex items-center gap-4">
                            <button type="submit"
                                class="px-8 py-3 bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-xl transition-all shadow-lg shadow-orange-500/25 flex items-center gap-2">
                                <i class="mdi mdi-check"></i>
                                Salvar Alterações
                            </button>
                            <a href="{{ url()->previous() }}"
                                class="px-8 py-3 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 font-bold rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-all">
                                Cancelar
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewFile(input) {
            var file = input.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function () {
                    document.getElementById("preview-image").src = reader.result;
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
@endsection