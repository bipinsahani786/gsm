<?php

namespace App\Http\Controllers\Backend\Admins\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{


    public function showLoginForm()
    {
        return view('backend.admins.auth.login');
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::guard('admin')->attempt($request->only('email', 'password'))) {
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['error' => 'Admin access denied.']);
    }

    public function logout()
    {
        // dd(Auth::guard('admin')->logout());
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
