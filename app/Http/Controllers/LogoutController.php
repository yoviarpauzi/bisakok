<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    /**
     * Logout the user and reset the session.
     * 
     * @param \Illuminate\Http\Request $request
     * @param string $url
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request, string $url)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect($url);
    }

    /**
     * Logout the user and reset the session.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function userLogout(Request $request)
    {
        return $this->logout($request, '/login');
    }

    /**
     * Logout the user and reset the session.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function adminLogout(Request $request)
    {
        return $this->logout($request, '/admin/login');
    }
}
