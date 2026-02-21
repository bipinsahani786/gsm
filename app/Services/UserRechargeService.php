<?php

namespace App\Services;

use App\Models\Configuration;
use App\Models\Deposit;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserRechargeService
{
    // Recharge Configuration Data Fetch Karna
    public function getRechargeConfig()
    {
        $config = Configuration::all()->pluck('value', 'key');

        return [
            'upi_id' => $config['upi_id'] ?? '',
            'qr_code' => $config['qr_code'] ?? '',
            'usdt_rate' => (float)($config['usdt_rate'] ?? 92),
            'min_recharge' => (float)($config['min_recharge'] ?? 500),
            'usdt_methods' => isset($config['usdt_methods']) ? json_decode($config['usdt_methods'], true) : []
        ];
    }

    // Recharge Request Submit Karna
    public function submitRecharge(User $user, array $data, $file = null)
    {
        try {
            DB::beginTransaction();

            $deposit = new Deposit();
            $deposit->user_id = $user->id;
            $deposit->amount = $data['amount'];
            $deposit->method = $data['method'];
            $deposit->utr_hash = ($data['method'] == 'upi') ? ($data['utr'] ?? null) : ($data['hash'] ?? null);

            // Screenshot Upload Logic
            if ($file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/deposits'), $filename);
                $deposit->screenshot = 'uploads/deposits/' . $filename;
            }

            $deposit->save();

            DB::commit();
            return ['status' => true, 'message' => 'Recharge request submitted successfully!', 'data' => $deposit];

        } catch (\Exception $e) {
            DB::rollback();
            return ['status' => false, 'message' => 'Something went wrong: ' . $e->getMessage()];
        }
    }

    // History Fetch Karna
    public function getRechargeHistory(User $user)
    {
        return Deposit::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
    }
}