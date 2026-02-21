<?php

namespace App\Services;

use App\Models\BankDetail;
use App\Models\User;

class UserAccountService
{

    public function getAccountDetails(User $user)
    {
        return [
            'bank' => BankDetail::where('user_id', $user->id)->where('type', 'bank')->first(),
            'crypto' => BankDetail::where('user_id', $user->id)->where('type', 'crypto')->first()
        ];
    }

 
    public function bindAccount(User $user, array $data)
    {
        return BankDetail::updateOrCreate(
            ['user_id' => $user->id, 'type' => $data['type']],
            collect($data)->only([
                'account_holder', 'account_number', 'ifsc_code', 
                'bank_name', 'wallet_address', 'network'
            ])->toArray()
        );
    }
}