<?php

namespace App\Http\Controllers\Backend\Users\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserSettingController extends Controller
{
    /**
     * Show Change Password Page
     */
    public function changePasswordIndex()
    {
        return view('backend.users.pages.change_password');
    }

    /**
     * Handle Password Update
     */
    public function updatePassword(Request $request)
    {
        // 1. Validation Logic
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ], [
            'new_password.confirmed' => 'New password and confirmation do not match.',
            'new_password.min' => 'The new password must be at least 8 characters.'
        ]);

        $user = Auth::user();

        // 2. Check if current password is correct
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'The current password you entered is incorrect.');
        }

        // 3. Update to new password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Your password has been updated successfully!');
    }

    public function transactions()
    {
        $transactions = \App\Models\Transaction::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('backend.users.pages.transactions', compact('transactions'));
    }
}
