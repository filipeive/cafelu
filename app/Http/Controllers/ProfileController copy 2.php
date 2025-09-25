<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ProfileController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function edit(){
        // Exibir a página do perfil do administrador
        $loggedId = intval(Auth::id());
        $user = User::find($loggedId);

        if($user){
            return view('profile.index', compact('user'));
        } else {
            return redirect()->route('home');
        }
    }

    public function save(Request $request){
        // Salvar as alterações na página do perfil do administrador
        $loggedId = intval(Auth::id());
        $user = User::find($loggedId);

        if($user){
            $data = $request->all();

            if($request->filled('password')){
                $data['password'] = bcrypt($request->password);
            }

            $user->update($data);

            return redirect()->route('profile')->with('success', 'Perfil salvo com sucesso!');
        } else {
            return redirect('home');
        }
    }
}
