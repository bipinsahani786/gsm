<?php

namespace App\Services;

use App\Models\User;
use App\Models\Wallet; // Don't forget to import this
use Illuminate\Support\Facades\Hash;

class AuthService
{
    /**
     * Handle User Registration Logic
     */
    public function registerUser(array $data)
    {
        // 1. Generate Unique Random 6-digit UID
        do {
            $uid = rand(100000, 999999);
        } while (User::where('uid', $uid)->exists());

        // 2. Create User
        $user = User::create([
            'uid' => $uid,
            'name' => $data['name'],
            'mobile' => $data['mobile'],
            'rid' => $data['rid'] ?? null,
            'password' => Hash::make($data['password']),
            'status' => 1
        ]);

        return $user;
    }

    /**
     * Verify Login Credentials (Used for both Web and API)
     */
    public function verifyCredentials(string $input, string $password)
    {
        // Check if input is 6-digit UID or Mobile
        $field = is_numeric($input) && strlen($input) == 6 ? 'uid' : 'mobile';

        // Find user by UID or Mobile
        $user = User::where($field, $input)->first();

        // Check user existence and password hash
        if ($user && Hash::check($password, $user->password)) {
            return $user;
        }

        return null; // Invalid credentials
    }
}