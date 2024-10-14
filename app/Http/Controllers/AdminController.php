<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function shows()
    {
        return User::where('role', 'admin')->get();
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'name' => ['required', 'string', 'unique:users,name'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required', 'string', 'min:8', 'same:password']
        ]);

        $user = new User();
        $user->name = $credentials['name'];
        $user->password = Hash::make($credentials['password']);
        $user->role = 'admin';
        $user->save();
    }

    public function edit(Request $request)
    {
        $credentials = $request->validate([
            'id' => ['required', 'integer'],
            'name' => ['required', 'string', 'unique:users,name,' . $request->id],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['nullable', 'string', 'min:8', 'same:password']
        ]);

        $user = User::find($credentials['id']);
        $user->name = $credentials['name'];
        if (isset($credentials['password'])) {
            $user->password = Hash::make($credentials['password']);
        }
        $user->save();
    }

    public function delete(Request $request)
    {
        $credentials = $request->validate([
            'ids' => ['required', 'array']
        ]);

        User::destroy($credentials['ids']);
    }
}
