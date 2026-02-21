<?php

namespace App\Http\Controllers\Backend\Users\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserAuthController extends Controller
{
    protected $authService;

    // Service class ko inject kiya
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function showLoginForm()
    {
        return view('backend.users.auth.login');
    }

    public function showRegisterForm()
    {
        return view('backend.users.auth.register');
    }

    // Login Logic
    public function login(Request $request)
    {
        $request->validate([
            'login_input' => 'required',
            'password' => 'required',
        ]);

        $user = $this->authService->verifyCredentials($request->login_input, $request->password);

        if ($user) {
            // Web Session login
            Auth::guard('web')->login($user);
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
            'rid' => 'nullable|exists:users,uid',
        ]);

   
        $user = $this->authService->registerUser($request->all());

        // Web Session login
        Auth::guard('web')->login($user);
        return redirect()->route('user.dashboard');
    }

    public function logout()
    {
        Auth::guard('web')->logout();
        return redirect()->route('login');
    }
}