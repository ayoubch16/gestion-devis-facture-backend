<?php

// app/Http/Controllers/UserController.php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function show($id)
    {
        return User::findOrFail($id);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'is_active' => 'boolean',
            'role' => 'required|in:ROLE_USER,ROLE_ADMIN,ROLE_SUPER_ADMIN,ROLE_CLIENT,ROLE_VENDEUR,ROLE_COMPTABLE,ROLE_COMMERCIAL',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        return User::create($validated);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'username' => 'string|unique:users,username,'.$user->id,
            'email' => 'email|unique:users,email,'.$user->id,
            'password' => 'string|min:8',
            'first_name' => 'string',
            'last_name' => 'string',
            'is_active' => 'boolean',
            'role' => 'in:ROLE_USER,ROLE_ADMIN,ROLE_SUPER_ADMIN,ROLE_CLIENT,ROLE_VENDEUR,ROLE_COMPTABLE,ROLE_COMMERCIAL',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);
        return $user;
    }
}
