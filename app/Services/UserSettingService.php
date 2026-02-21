<?php

namespace App\Services;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Support\Facades\Hash;

class UserSettingService
{
    // Password Update Logic
    public function updatePassword(User $user, array $data)
    {
        // Check if current password matches
        if (!Hash::check($data['current_password'], $user->password)) {
            return ['status' => false, 'message' => 'The current password you entered is incorrect.'];
        }

        // Update to new password
        $user->password = Hash::make($data['new_password']);
        $user->save();

        return ['status' => true, 'message' => 'Your password has been updated successfully!'];
    }

    // Transactions Fetch Logic (With Pagination)
    public function getTransactions(User $user, $perPage = 20)
    {
        return Transaction::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }
}