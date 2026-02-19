<?php

namespace App\Http\Controllers\Backend\Users\Auth;

use App\Http\Controllers\Controller;
use App\Models\Position;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserAuthController extends Controller
{

    public function showLoginForm()
    {
        return view('backend.users.auth.login'); // Mobile-first login
    }

    public function showRegisterForm()
    {
        return view('backend.users.auth.register'); // Mobile-first register
    }
    //
    public function login(Request $request)
    {
        $request->validate([
            'login_input' => 'required',
            'password' => 'required',
        ]);
        $input = $request->login_input;
        // Check if input is 6-digit UID or Mobile
        $field = is_numeric($input) && strlen($input) == 6 ? 'uid' : 'mobile';

        if (Auth::guard('web')->attempt([$field => $input, 'password' => $request->password])) {
            return redirect()->route('user.dashboard');
        }

        return back()->withErrors(['error' => 'Invalid UID/Mobile or Password']);
    }

    // Register Logic
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|numeric|unique:users',
            'password' => 'required|min:6|confirmed',
            'rid' => 'nullable|exists:users,uid', // Check if referral ID exists in uid column
        ]);
        // 1. Generate Unique Random 6-digit UID
        do {
            $uid = rand(100000, 999999);
        } while (User::where('uid', $uid)->exists());

        // 2. Create User
        $user = User::create([
            'uid' => $uid,
            'name' => $request->name,
            'mobile' => $request->mobile,
            'rid' => $request->rid, // Sponsor ID
            'password' => Hash::make($request->password),
        ]);

        Auth::guard('web')->login($user);
        return redirect()->route('user.dashboard');
    }

    public function logout()
    {
        Auth::guard('web')->logout();
        return redirect()->route('login');
    }
}
