<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    public function logout(Request $request, string $url)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect($url);
    }

    public function userLogout(Request $request)
    {
        return $this->logout($request, '/login');
    }

    public function adminLogout(Request $request)
    {
        return $this->logout($request, '/admin/login');
    }
}
