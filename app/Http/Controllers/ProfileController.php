<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Mostrar formulário de edição do perfil
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    // Atualizar perfil
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:users,email,' . $user->id,
            'username' => 'required|string|max:50|unique:users,username,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'photo' => 'nullable|image|max:2048',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->username = $validated['username'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        if ($request->hasFile('photo')) {
            // Apaga avatar antigo (se existir) e salva o novo
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $path = $request->file('photo')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Perfil atualizado com sucesso.');
    }
}