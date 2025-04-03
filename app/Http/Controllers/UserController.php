<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'required|integer|min:1|max:120',
            'role' => 'required|string|in:admin,doctor,patient',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'adress' => 'nullable|string|max:255',
            'status' => 'nullable|string|in:active,inactive',
            'phoneNumber' => 'required|string|max:20',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        
        $user = User::create($validated);
        
        return response()->json($user, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'string|max:255',
            'age' => 'integer|min:1|max:120',
            'role' => 'string|in:admin,doctor,patient',
            'email' => ['string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8',
            'adress' => 'nullable|string|max:255',
            'status' => 'string|in:active,inactive',
            'phoneNumber' => 'string|max:20',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }
        
        $user->update($validated);
        
        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(null, 204);
    }
}
