<div class="mb-3">
    <label class="form-label">Nome Completo</label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name ?? '') }}" required>
    @error('name') <div class="text-danger">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label">E-mail</label>
    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email ?? '') }}" required>
    @error('email') <div class="text-danger">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label">Nome de Usuário</label>
    <input type="text" name="username" class="form-control" value="{{ old('username', $user->username ?? '') }}" required>
    @error('username') <div class="text-danger">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label">Função</label>
    <select name="role" class="form-select" required>
        <option value="">Selecione uma função</option>
        <option value="admin" {{ (old('role', $user->role ?? '') === 'admin') ? 'selected' : '' }}>Administrador</option>
        <option value="manager" {{ (old('role', $user->role ?? '') === 'manager') ? 'selected' : '' }}>Gerente</option>
        <option value="waiter" {{ (old('role', $user->role ?? '') === 'waiter') ? 'selected' : '' }}>Garçom</option>
    </select>
    @error('role') <div class="text-danger">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label">Status</label>
    <select name="status" class="form-select" required>
        <option value="active" {{ (old('status', $user->status ?? '') === 'active') ? 'selected' : '' }}>Ativo</option>
        <option value="inactive" {{ (old('status', $user->status ?? '') === 'inactive') ? 'selected' : '' }}>Inativo</option>
        <option value="suspended" {{ (old('status', $user->status ?? '') === 'suspended') ? 'selected' : '' }}>Suspenso</option>
    </select>
    @error('status') <div class="text-danger">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label">Senha {{ isset($user) ? '(deixe em branco para manter)' : '' }}</label>
    <input type="password" name="password" class="form-control" {{ isset($user) ? '' : 'required' }}>
    @error('password') <div class="text-danger">{{ $message }}</div> @enderror
</div>

@if(isset($user))
    <div class="mb-3">
        <label class="form-label">Confirmar Senha</label>
        <input type="password" name="password_confirmation" class="form-control">
    </div>
@endif