<?php

namespace App\Http\Controllers\Backend\Users\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Configuration;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserTaskController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Agar user ke paas koi active level nahi hai
        if (!$user->level_id || !$user->level) {
            return redirect()->route('user.dashboard')->with('error', 'Please activate a level to start earning daily.');
        }

        $level = $user->level;
        $now = Carbon::now('Asia/Kolkata');

        // Aaj ke total complete kiye gaye tasks count karo
        $todayTasksCompleted = Transaction::where('user_id', $user->id)
            ->where('type', 'task_income')
            ->whereDate('created_at', $now->toDateString())
            ->count();

        // Ek random image generate karo (Picsum API se) taaki har bar naya photo aaye
        $randomImage = 'https://picsum.photos/seed/' . rand(1, 9999) . '/600/400';

        return view('backend.users.pages.tasks', compact('level', 'todayTasksCompleted', 'randomImage'));
    }

    public function submit(Request $request)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5'
        ]);

        $user = Auth::user();
        $wallet = $user->wallet;
        $level = $user->level;

        if (!$level) {
            return back()->with('error', 'No active level found.');
        }

        $now = Carbon::now('Asia/Kolkata');

        // Check if daily limit is reached
        $todayTasksCompleted = Transaction::where('user_id', $user->id)
            ->where('type', 'task_income')
            ->whereDate('created_at', $now->toDateString())
            ->count();

        if ($todayTasksCompleted >= $level->daily_limit) {
            return back()->with('error', 'You have already completed all your tasks for today. Come back tomorrow!');
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
                'details' => "Task completed ({$request->rating} Star Rating)"
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
                        
                        // Upline ki income = Downline ki earning ka X%
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
            return back()->with('success', "Task Completed! You earned ₹{$taskEarning}.");

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
}
