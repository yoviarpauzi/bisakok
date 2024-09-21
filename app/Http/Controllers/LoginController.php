<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function authenticate(Request $request, array $credentials, string $url)
    {
        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            return redirect($url);
        }

        return back()->withErrors(['message' => 'Your credentials do not match our records!']);
    }

    public function authUser(Request $request)
    {
        $credentials = $request->validate([
            'nisn' => ['required', 'string', 'min:10'],
            'password' => ['required', 'string', 'min:8']
        ]);

        return $this->authenticate($request, $credentials, '/dashboard');
    }

    public function authAdmin(Request $request)
    {
        $credentials = $request->validate([
            'name' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8']
        ]);

        return $this->authenticate($request, $credentials, '/admin/dashboard');
    }
}
