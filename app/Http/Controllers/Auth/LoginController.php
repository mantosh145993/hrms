<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // public function showAdminLoginForm()
    // {
    //     return view('auth.admin-login');
    // }

    // public function showEmployeeLoginForm()
    // {
    //     return view('auth.employee-login');
    // }

    // public function adminLogin(Request $request)
    // {
    //     $credentials = $request->only('email', 'password');

    //     if (Auth::attempt(array_merge($credentials, ['role' => 'admin']))) {
    //         return redirect()->route('admin.dashboard');
    //     }

    //     return back()->withErrors(['email' => 'Invalid Admin credentials']);
    // }

    // public function employeeLogin(Request $request)
    // {
    //     $credentials = $request->only('email', 'password');

    //     if (Auth::attempt(array_merge($credentials, ['role' => 'employee']))) {
    //         return redirect()->route('employee.dashboard');
    //     }

    //     return back()->withErrors(['email' => 'Invalid Employee credentials']);
    // }

    // public function logout(Request $request)
    // {
    //     // Save role before logging out
    //     $role = Auth::user()->role ?? null;

    //     Auth::logout();
    //     $request->session()->invalidate();
    //     $request->session()->regenerateToken();

    //     if ($role === 'admin' || $role === 'manager') {
    //         return redirect()->route('admin.login');
    //     } else {
    //         return redirect()->route('employee.login');
    //     }
    // }
    public function showLoginForm()
    {
        return view('Auth.login'); // your login.blade.php
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $role = Auth::user()->role;

            if ($role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($role === 'manager') {
                return redirect()->route('manager.dashboard');
            } else {
                return redirect()->route('employee.dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
