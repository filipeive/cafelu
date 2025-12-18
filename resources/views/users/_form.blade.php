<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Nome Completo</label>
            <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" required
                class="w-full px-4 py-2 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">
            @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">E-mail</label>
            <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" required
                class="w-full px-4 py-2 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">
            @error('email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Nome de Usuário</label>
            <input type="text" name="username" value="{{ old('username', $user->username ?? '') }}" required
                class="w-full px-4 py-2 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">
            @error('username') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Função</label>
            <select name="role" required
                class="w-full px-4 py-2 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">
                <option value="">Selecione uma função</option>
                <option value="admin" {{ (old('role', $user->role ?? '') === 'admin') ? 'selected' : '' }}>Administrador
                </option>
                <option value="manager" {{ (old('role', $user->role ?? '') === 'manager') ? 'selected' : '' }}>Gerente
                </option>
                <option value="waiter" {{ (old('role', $user->role ?? '') === 'waiter') ? 'selected' : '' }}>Garçom
                </option>
            </select>
            @error('role') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Status</label>
            <select name="status" required
                class="w-full px-4 py-2 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">
                <option value="active" {{ (old('status', $user->status ?? '') === 'active') ? 'selected' : '' }}>Ativo
                </option>
                <option value="inactive" {{ (old('status', $user->status ?? '') === 'inactive') ? 'selected' : '' }}>
                    Inativo</option>
                <option value="suspended" {{ (old('status', $user->status ?? '') === 'suspended') ? 'selected' : '' }}>
                    Suspenso</option>
            </select>
            @error('status') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-6 border-t border-gray-100 dark:border-gray-700">
        <div>
            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                Senha {{ isset($user) ? '(deixe em branco para manter)' : '' }}
            </label>
            <input type="password" name="password" {{ isset($user) ? '' : 'required' }}
                class="w-full px-4 py-2 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">
            @error('password') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        @if(isset($user))
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Confirmar Senha</label>
                <input type="password" name="password_confirmation"
                    class="w-full px-4 py-2 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">
            </div>
        @endif
    </div>
</div>