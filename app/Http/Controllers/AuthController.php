<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function logout(Request $request)
    {
        // Log out the user (if you're using Laravel Auth)
        Auth::logout();

        // Invalidate and regenerate the session token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Return a view that will remove the accessToken from localStorage and redirect to /login
        return view('auth.logout');
    }
}
