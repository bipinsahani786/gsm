<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PayMonthlySalaries extends Command
{
    // Command ka naam
    protected $signature = 'salary:pay';
    protected $description = 'Pay monthly salary to eligible ranked users after every 30 days';

    public function handle()
    {
        $this->info("Starting Monthly Salary Distribution...");

        // Sirf un users ko nikalo jinki koi rank (position) hai
        $users = User::whereNotNull('position_id')->with(['position', 'wallet'])->get();
        $now = Carbon::now('Asia/Kolkata');

        foreach ($users as $user) {
            $position = $user->position;

            // Agar rank nahi hai, active nahi hai, ya salary 0 hai toh chhod do
            if (!$position || !$position->status || $position->salary <= 0) {
                continue;
            }

            // Pata karo cycle kahan se shuru karni hai (Aakhiri salary ki date se, ya fir Rank milne ki date se)
            $startDate = $user->last_salary_paid_at 
                            ? Carbon::parse($user->last_salary_paid_at) 
                            : Carbon::parse($user->position_achieved_at);

            // Agar startDate maujood hai aur use 30 din (ya usse zyada) ho gaye hain
            if ($startDate && $now->diffInDays($startDate) >= 30) {
                
                try {
                    DB::beginTransaction();
                    $wallet = $user->wallet;
                    
                    // 1. Wallet me paise add karo
                    $wallet->increment('income_wallet', $position->salary);

                    // 2. Transaction Passbook entry banao
                    Transaction::create([
                        'user_id' => $user->id,
                        'amount' => $position->salary,
                        'post_balance' => $wallet->personal_wallet + $wallet->income_wallet,
                        'type' => 'monthly_salary',
                        'trx_id' => 'SAL' . strtoupper(uniqid()),
                        'details' => "Monthly Salary for Rank: {$position->name}"
                    ]);

                    // 3. User ki 'Aakhiri Salary' date ko aaj par set kar do taaki agle 30 din baad firse loop chale
                    $user->last_salary_paid_at = $now;
                    $user->save();

                    DB::commit();
                    $this->info("✅ Paid ₹{$position->salary} to {$user->name} (UID: {$user->uid})");

                } catch (\Exception $e) {
                    DB::rollback();
                    $this->error("❌ Failed to pay {$user->name}: " . $e->getMessage());
                }
            }
        }

        $this->info("Salary Distribution Complete!");
    }
}