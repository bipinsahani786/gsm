<?php

namespace App\Services;

use App\Models\Level;
use App\Models\Transaction;
use App\Models\User;

class UserTeamService
{
    public function getTeamData(User $user)
    {
        // Level 1, 2, 3 ke users fetch karna
        $level1 = User::with(['level', 'wallet'])->where('rid', $user->uid)->get();
        $level1Uids = $level1->pluck('uid');

        $level2 = User::with(['level', 'wallet'])->whereIn('rid', $level1Uids)->get();
        $level2Uids = $level2->pluck('uid');

        $level3 = User::with(['level', 'wallet'])->whereIn('rid', $level2Uids)->get();

        // Sabhi team members ko ek list mein jodh lena
        $allTeamMembers = $level1->concat($level2)->concat($level3);

        $totalTeam = $allTeamMembers->count();
        $activeTeam = $allTeamMembers->whereNotNull('level_id')->count();

        // Har Package (Plan Level) ke hisaab se team count nikalna
        $systemLevels = Level::where('status', 1)->get();
        $planCounts = [];
        
        foreach ($systemLevels as $plan) {
            $planCounts[$plan->name] = $allTeamMembers->where('level_id', $plan->id)->count();
        }

        // Total Commission Earned
        $totalCommission = Transaction::where('user_id', $user->id)
            ->whereIn('type', ['referral_commission', 'team_task_commission'])
            ->sum('amount');

        $referralCode = $user->uid; 
        $referralLink = url('/register?ref=' . $referralCode);

        return [
            'user' => $user, 
            'level1' => $level1, 
            'level2' => $level2, 
            'level3' => $level3, 
            'totalTeam' => $totalTeam, 
            'activeTeam' => $activeTeam, 
            'totalCommission' => $totalCommission, 
            'referralLink' => $referralLink, 
            'referralCode' => $referralCode, 
            'planCounts' => $planCounts
        ];
    }
}