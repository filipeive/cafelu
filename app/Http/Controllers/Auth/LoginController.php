<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/dashboard';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    public function username()
    {
        return 'username';
    }

    // ✅ Verifica o status e atualiza o último login
    protected function authenticated($request, $user)
    {
        if ($user->status !== 'active') {
            Auth::logout();

            return redirect()->route('login')->withErrors([
                'username' => 'Sua conta está ' . $user->status . '. Por favor, contacte o administrador.',
            ]);
        }

        $user->update(['last_login_at' => now()]);
    }
}
