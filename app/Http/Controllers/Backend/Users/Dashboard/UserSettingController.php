<?php

namespace App\Http\Controllers\Backend\Users\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\UserSettingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserSettingController extends Controller
{
    protected $settingService;

    public function __construct(UserSettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    public function changePasswordIndex()
    {
        return view('backend.users.pages.change_password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ], [
            'new_password.confirmed' => 'New password and confirmation do not match.',
            'new_password.min' => 'The new password must be at least 8 characters.'
        ]);

        $result = $this->settingService->updatePassword(Auth::user(), $request->all());

        if (!$result['status']) {
            return back()->with('error', $result['message']);
        }

        return back()->with('success', $result['message']);
    }

    public function transactions()
    {
        // Service se 20 per page ke hisab se transactions le aayenge
        $transactions = $this->settingService->getTransactions(Auth::user(), 20);

        return view('backend.users.pages.transactions', compact('transactions'));
    }
}