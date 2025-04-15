<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    // Display a listing of the users
    public function index(Request $request)
    {
        //search
        $search = request()->query('search');
        if ($search) {
            $users = User::where('name', 'LIKE', "%$search%")->orWhere('email', 'LIKE', "%$search%")->paginate(10);
        } else {
            $users = User::paginate(10);
        }
        return view('users.index', compact('users', 'search'));
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
            'username' => 'required|unique:users,username|max:50',
            'password' => 'required|min:8',
            'name' => 'required|max:100',
            'role' => 'required|in:admin,manager,waiter',
        ]);

        $user = new User();
        $user->email = $validatedData['email'];
        $user->username = $validatedData['username'];
        $user->password = Hash::make($validatedData['password']);
        $user->name = $validatedData['name'];
        $user->role = $validatedData['role'];
        $user->save();

        return redirect()->route('users.index')->with('success', 'User created successfully');
    }

    // Display the specified user
    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->route('users.index')->with('error', 'User not found');
        }

        return view('users.show', compact('user'));
    }

    // Show the form for editing the specified user
    public function edit($id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->route('users.index')->with('error', 'User not found');
        }

        return view('users.edit', compact('user'));
    }

    // Update the specified user in storage
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->route('users.index')->with('error', 'User not found');
        }

        $validatedData = $request->validate([
            'email' => 'email|unique:users,email,' . $id,
            'username' => 'unique:users,username,' . $id . '|max:50',
            'password' => 'nullable|min:8',
            'name' => 'max:100',
            'role' => 'in:admin,manager,waiter',
        ]);

        $user->email = $validatedData['email'] ?? $user->email;
        $user->username = $validatedData['username'] ?? $user->username;
        if (!empty($validatedData['password'])) {
            $user->password = Hash::make($validatedData['password']);
        }
        $user->name = $validatedData['name'] ?? $user->name;
        $user->role = $validatedData['role'] ?? $user->role;
        $user->save();

        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }

    // Remove the specified user from storage
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->route('users.index')->with('error', 'User not found');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }
}