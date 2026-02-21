<?php

namespace App\Services;

use App\Models\Configuration;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UserTaskService
{
    // Task Page Data Load Karna
    public function getTaskData(User $user)
    {
        if (!$user->level_id || !$user->level) {
            return ['status' => false, 'message' => 'Please activate a level to start earning daily.'];
        }

        $now = Carbon::now('Asia/Kolkata');
        $todayTasksCompleted = Transaction::where('user_id', $user->id)
            ->where('type', 'task_income')
            ->whereDate('created_at', $now->toDateString())
            ->count();

        // Random image for task
        $randomImage = 'https://picsum.photos/seed/' . rand(1, 9999) . '/600/400';

        return [
            'status' => true,
            'level' => $user->level,
            'todayTasksCompleted' => $todayTasksCompleted,
            'randomImage' => $randomImage
        ];
    }

    // Task Submit Karna aur Commission Baatna
    public function submitTask(User $user, array $data)
    {
        $level = $user->level;
        if (!$level) {
            return ['status' => false, 'message' => 'No active level found.'];
        }

        $now = Carbon::now('Asia/Kolkata');
        $wallet = $user->wallet;

        // Check if daily limit is reached
        $todayTasksCompleted = Transaction::where('user_id', $user->id)
            ->where('type', 'task_income')
            ->whereDate('created_at', $now->toDateString())
            ->count();

        if ($todayTasksCompleted >= $level->daily_limit) {
            return ['status' => false, 'message' => 'You have already completed all your tasks for today. Come back tomorrow!'];
        }

        try {
            DB::beginTransaction();

            // 1. Give Task Income to the User
            $taskEarning = $level->rate;
            $wallet->increment('income_wallet', $taskEarning);

            Transaction::create([
                'user_id' => $user->id,
                'amount' => $taskEarning,
                'post_balance' => $wallet->personal_wallet + $wallet->income_wallet,
                'type' => 'task_income',
                'trx_id' => 'TSK' . strtoupper(uniqid()),
                'details' => "Task completed (" . ($data['rating'] ?? 5) . " Star Rating)"
            ]);

            // 2. Distribute Task Commission to Uplines (L1, L2, L3)
            if ($user->sponsor_id) {
                $percentages = [
                    1 => Configuration::get('task_commission_l1', 5),
                    2 => Configuration::get('task_commission_l2', 2),
                    3 => Configuration::get('task_commission_l3', 1)
                ];

                $currentSponsor = $user->sponsor;
                $levelCount = 1;

                while ($currentSponsor && $levelCount <= 3) {
                    // Sirf un uplines ko task commission milega jinka khud ka account active ho
                    if ($currentSponsor->level_id) {
                        $commissionAmount = ($taskEarning * $percentages[$levelCount]) / 100;

                        if ($commissionAmount > 0) {
                            $sponsorWallet = $currentSponsor->wallet;
                            $sponsorWallet->increment('income_wallet', $commissionAmount);

                            Transaction::create([
                                'user_id' => $currentSponsor->id,
                                'amount' => $commissionAmount,
                                'post_balance' => $sponsorWallet->personal_wallet + $sponsorWallet->income_wallet,
                                'type' => 'team_task_commission',
                                'trx_id' => 'TMC' . strtoupper(uniqid()),
                                'details' => "L{$levelCount} Team Task Commission from {$user->name}"
                            ]);
                        }
                    }

                    $currentSponsor = $currentSponsor->sponsor;
                    $levelCount++;
                }
            }

            DB::commit();
            return ['status' => true, 'message' => "Task Completed! You earned ₹{$taskEarning}."];

        } catch (\Exception $e) {
            DB::rollback();
            return ['status' => false, 'message' => 'Something went wrong: ' . $e->getMessage()];
        }
    }
}