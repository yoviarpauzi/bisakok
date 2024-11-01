<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Return all admin users.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function shows()
    {
        return User::where('role', 'admin')->get();
    }

    /**
     * Create a new admin user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Update the specified admin user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $credentials = $request->validate([
            'id' => ['required', 'integer'],
            'name' => ['required', 'string', 'unique:users,name,except,' . $request->id],
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

    /**
     * Remove the specified admin user from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $credentials = $request->validate([
            'ids' => ['required', 'array']
        ]);

        User::destroy($credentials['ids']);
    }
}
