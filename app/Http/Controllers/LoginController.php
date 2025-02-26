<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin; // Ensure your Admin model includes a 'full_name' attribute

class LoginController extends Controller
{
    // Display the login form
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    // Process the login submission
    public function login(Request $request)
    {
        // Validate the login credentials
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Retrieve the admin record from the DB (including full_name)
        $admin = Admin::where('username', $credentials['username'])->first();

        // Check if an admin record exists and verify the MD5 hashed password
        if ($admin && md5($credentials['password']) === $admin->password) {
            // (Optional) You can update the full_name in the model if needed.
            // Here, we're storing the full_name into session for easier access.
            session()->put('full_name', $admin->full_name);

            // Log the admin in using Laravel's built-in authentication
            Auth::login($admin);
            $request->session()->regenerate();

            // Redirect to the dashboard page after successful login
            return redirect()->intended('/dashboard');
        }

        // If authentication fails, redirect back with an error message
        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ])->withInput();
    }
}
