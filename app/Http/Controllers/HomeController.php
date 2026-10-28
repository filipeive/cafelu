<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (auth()->check() && auth()->user()->role === 'customer') {
            return redirect()->route('customer.dashboard');
        }
        if (auth()->check() && auth()->user()->isSuperAdmin() && !session('is_support_mode')) {
            return redirect()->route('admin.tenants.index');
        }
        return view('home');
    }
}
