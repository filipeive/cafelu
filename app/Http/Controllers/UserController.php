<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller
{
    // Display a listing of the users
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = User::query();

        // Scope to current tenant if not superadmin
        if (Auth::check() && !Auth::user()->isSuperAdmin()) {
            $tenantId = Auth::user()->tenant_id ?? current_tenant_id();
            if ($tenantId) {
                $query->where('tenant_id', $tenantId);
            }
        } elseif (session()->has('current_tenant_id')) {
            $query->where('tenant_id', session('current_tenant_id'));
        }

        $users = $query->when($search, function ($q, $search) {
            $q->where(function ($sub) use ($search) {
                $sub->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%");
            });
        })->latest('id')->paginate(10);

        $loggedId = intval(Auth::id());

        return view('users.index', compact('users', 'search', 'loggedId'));
    }
    // Show the form for creating a new user
    public function create()
    {
        return view('users.create');
    }

    // Store a newly created user in storage
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email|unique:users,email',
            'username' => 'required|max:50|unique:users,username',
            'password' => 'required|min:4',
            'name' => 'required|max:100',
            'role' => 'required|in:admin,manager,waiter',
            'status' => 'nullable|in:active,inactive,suspended',
        ]);

        $user = new User();
        $user->tenant_id = Auth::user()->tenant_id ?? current_tenant_id();
        $user->email = $validatedData['email'];
        $user->username = $validatedData['username'];
        $user->password = Hash::make($validatedData['password']);
        $user->name = $validatedData['name'];
        $user->role = $validatedData['role'];
        $user->status = $validatedData['status'] ?? 'active';
        $user->save();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => __('messages.user_created'),
                'user' => $user
            ]);
        }

        return redirect()->route('users.index')->with('success', __('messages.user_created'));
    }

    // Display the specified user
    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->route('users.index')->with('error', __('messages.user_not_found'));
        }

        return view('users.show', compact('user'));
    }

    // Show the form for editing the specified user
    public function edit($id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->route('users.index')->with('error', __('messages.user_not_found'));
        }

        return view('users.edit', compact('user'));
    }

    // Update the specified user in storage
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->route('users.index')->with('error', __('messages.user_not_found'));
        }

        // Restrict restaurant admin from editing other tenants' users
        if (Auth::check() && !Auth::user()->isSuperAdmin() && Auth::user()->tenant_id) {
            if ($user->tenant_id && $user->tenant_id !== Auth::user()->tenant_id) {
                abort(403);
            }
        }

        $validatedData = $request->validate([
            'email' => 'required|email|unique:users,email,' . $id,
            'username' => 'required|max:50|unique:users,username,' . $id,
            'password' => 'nullable|min:4',
            'name' => 'required|max:100',
            'role' => 'required|in:admin,manager,waiter',
            'status' => 'nullable|in:active,inactive,suspended',
        ]);

        $user->email = $validatedData['email'];
        $user->username = $validatedData['username'];
        if (!empty($validatedData['password'])) {
            $user->password = Hash::make($validatedData['password']);
        }
        $user->name = $validatedData['name'];
        $user->role = $validatedData['role'];
        if (!empty($validatedData['status'])) {
            $user->status = $validatedData['status'];
        }
        $user->save();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => __('messages.user_updated'),
                'user' => $user
            ]);
        }

        return redirect()->route('users.index')->with('success', __('messages.user_updated'));
    }

    // Remove the specified user from storage
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->route('users.index')->with('error', __('messages.user_not_found'));
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', __('messages.user_deleted'));
    }
}