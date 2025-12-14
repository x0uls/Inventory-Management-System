<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLogin()
    {
        // Redirect logged-in users to dashboard
        if (Auth::check()) {
            return redirect("/dashboard");
        }

        return view("login");
    }

    public function login(Request $request)
    {
        $credentials = $request->only("email", "password");

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect('/dashboard')->with('success', 'Welcome back!');
        }

        return back()->withErrors(['login' => 'Invalid login credentials']);
    }
}
