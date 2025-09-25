<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserActivity;
use App\Models\TemporaryPassword;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    use LogsActivity;

    public function index(Request $request)
    {
        $query = User::with('activeTemporaryPasswords')->orderBy('name');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $sortBy = $request->get('sort', 'name');
        $sortDirection = $request->get('direction', 'asc');

        if (in_array($sortBy, ['name', 'email', 'created_at', 'last_login_at'])) {
            $query->orderBy($sortBy, $sortDirection);
        }

        $users = $query->paginate(15)->withQueryString();
        /* 'admin' => 'Administrador',
            'manager' => 'Gerente',
            'cashier' => 'Caixa',
            'waiter' => 'Garçom',
            'cook' => 'Cozinheiro', */
        $stats = [
            'total' => User::count(),
            'active' => User::where('is_active', true)->count(),
            'admin' => User::where('role', 'admin')->count(),
            'manager' => User::where('role', 'manager')->count(),
            'cashier' => User::where('role', 'cashier')->count(),
            'waiter' => User::where('role', 'waiter')->count(),
            'cook' => User::where('role', 'cook')->count(),
            'with_temp_password' => User::whereHas('activeTemporaryPasswords')->count(),
        ];

        return view('users.index', compact('users', 'stats'));
    }

    public function create()
    {
        $roles = ['admin', 'manager', 'staff']; // Usando array simples
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:admin,manager,staff',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $data = $request->except('photo', 'password_confirmation');

        if ($request->hasFile('photo')) {
            $data['photo_path'] = $request->file('photo')->store('user-photos', 'public');
        }

        $data['password'] = Hash::make($request->password);
        $data['is_active'] = true;

        $user = User::create($data);

        $this->logActivity('create', "Criou novo usuário: {$user->name}", $user);

        session()->flash('success', 'Usuário criado com sucesso!');
        return redirect()->route('users.index');
    }

    public function show(User $user)
    {
        $user->load(['activities' => fn($q) => $q->latest()->limit(5), 
                     'temporaryPasswords' => fn($q) => $q->latest()->limit(3)]);

        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = ['admin', 'manager', 'staff'];
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', \Illuminate\Validation\Rule::unique('users')->ignore($user->id)],
            'role' => 'required|in:admin,manager,staff',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        $request->validate($rules);

        $oldName = $user->name;
        $data = $request->except('photo', 'password_confirmation');

        if ($request->hasFile('photo')) {
            if ($user->photo_path) {
                Storage::disk('public')->delete($user->photo_path);
            }
            $data['photo_path'] = $request->file('photo')->store('user-photos', 'public');
        }

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
            $user->activeTemporaryPasswords()->update([
                'used' => true,
                'used_at' => now()
            ]);
        }

        $user->update($data);

        $this->logActivity('update', "Atualizou usuário de '{$oldName}' para '{$user->name}'", $user);

        session()->flash('success', 'Usuário atualizado com sucesso!');
        return redirect()->route('users.index');
    }

    public function destroy(User $user)
    {
        $userName = $user->name;

        if ($user->photo_path) {
            Storage::disk('public')->delete($user->photo_path);
        }

        $user->delete();

        $this->logActivity('delete', "Excluiu usuário: {$userName}", $user);

        session()->flash('success', 'Usuário excluído com sucesso!');
        return redirect()->route('users.index');
    }

    public function toggleStatus(User $user)
    {
        $currentStatus = $user->is_active;
        $user->update(['is_active' => !$currentStatus]);

        if (!$currentStatus) {
            $user->activeTemporaryPasswords()->update([
                'used' => true,
                'used_at' => now()
            ]);
        }

        $this->logActivity('status_change', "Alterou status do usuário '{$user->name}' para " . (!$currentStatus ? 'ativo' : 'inativo'), $user);

        session()->flash('success', "Usuário {$user->name} " . (!$currentStatus ? 'ativado' : 'desativado') . " com sucesso!");
        return redirect()->back();
    }

    public function activity(User $user)
    {
        $activities = $user->activities()->paginate(20);
        return view('users.activity', compact('user', 'activities'));
    }

    public function resetPassword(User $user)
    {
        $temporaryPassword = $this->generateSecurePassword();
        $tempPassword = TemporaryPassword::createForUser($user, $temporaryPassword, 24);
        $user->update(['password' => Hash::make($temporaryPassword)]);

        $this->logActivity('password_reset', "Resetou senha do usuário: {$user->name} (Expira em 24h)", $user);

        session()->flash('success', "Senha temporária gerada para {$user->name} (expira em 24h).");
        return redirect()->back();
    }

    public function temporaryPasswords(User $user)
    {
        $temporaryPasswords = $user->temporaryPasswords()->with('createdBy')->paginate(10);
        return view('users.temporary-passwords', compact('user', 'temporaryPasswords'));
    }

    public function invalidateTemporaryPasswords(User $user)
    {
        $count = $user->activeTemporaryPasswords()->count();
        $user->activeTemporaryPasswords()->update([
            'used' => true,
            'used_at' => now()
        ]);

        $this->logActivity('password_invalidate', "Invalidou {$count} senha(s) temporária(s) do usuário: {$user->name}", $user);

        session()->flash('success', "Invalidadas {$count} senha(s) temporária(s) com sucesso!");
        return redirect()->back();
    }

    private function generateSecurePassword(int $length = 12): string
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%&*';
        $password = '';
        $password .= substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 1);
        $password .= substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 1);
        $password .= substr(str_shuffle('0123456789'), 0, 1);
        $password .= substr(str_shuffle('!@#$%&*'), 0, 1);
        for ($i = 4; $i < $length; $i++) {
            $password .= $characters[random_int(0, strlen($characters) - 1)];
        }
        return str_shuffle($password);
    }
}
