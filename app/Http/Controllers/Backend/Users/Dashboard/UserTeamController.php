<?php

namespace App\Http\Controllers\Backend\Users\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Level;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserTeamController extends Controller
{
//    public function index()
//     {
//         $user = Auth::user();

//         // Level 1: Jinka 'rid' mere 'uid' ke barabar hai
//         $level1 = User::with('level')->where('rid', $user->uid)->get();
        
//         // Level 1 walo ke 'uid' nikal lo taaki unke neeche ke log dhundh sakein
//         $level1Uids = $level1->pluck('uid');

//         // Level 2: Jinka 'rid' Level 1 walo ke 'uid' mein se koi ek hai
//         $level2 = User::with('level')->whereIn('rid', $level1Uids)->get();
//         $level2Uids = $level2->pluck('uid');

//         // Level 3: Jinka 'rid' Level 2 walo ke 'uid' mein se koi ek hai
//         $level3 = User::with('level')->whereIn('rid', $level2Uids)->get();

//         // Team Statistics
//         $totalTeam = $level1->count() + $level2->count() + $level3->count();
        
//         $activeTeam = $level1->whereNotNull('level_id')->count() +
//                       $level2->whereNotNull('level_id')->count() +
//                       $level3->whereNotNull('level_id')->count();

//         // Total Commission Earned (Referral + Task Commission)
//         $totalCommission = Transaction::where('user_id', $user->id)
//             ->whereIn('type', ['referral_commission', 'team_task_commission'])
//             ->sum('amount');

//         // Referral Link Generate (Using 'uid')
//         $referralCode = $user->uid; 
//         $referralLink = url('/register?ref=' . $referralCode);

//         return view('backend.users.pages.team', compact(
//             'user', 'level1', 'level2', 'level3', 
//             'totalTeam', 'activeTeam', 'totalCommission', 'referralLink', 'referralCode'
//         ));
//     }


public function index()
    {
        $user = Auth::user();

        // Level 1, 2, 3 ke users fetch karna (Sath me unka Level aur Wallet bhi load karna)
        $level1 = User::with(['level', 'wallet'])->where('rid', $user->uid)->get();
        $level1Uids = $level1->pluck('uid');

        $level2 = User::with(['level', 'wallet'])->whereIn('rid', $level1Uids)->get();
        $level2Uids = $level2->pluck('uid');

        $level3 = User::with(['level', 'wallet'])->whereIn('rid', $level2Uids)->get();

        // Sabhi team members ko ek list mein jodh lena
        $allTeamMembers = $level1->concat($level2)->concat($level3);

        $totalTeam = $allTeamMembers->count();
        $activeTeam = $allTeamMembers->whereNotNull('level_id')->count();

        // 🟢 NAYA LOGIC: Har Package (Plan Level) ke hisaab se team count nikalna
        $systemLevels = Level::where('status', 1)->get();
        $planCounts = [];
        
        foreach ($systemLevels as $plan) {
            // Count karna ki puri team me is plan ($plan->id) wale kitne log hain
            $planCounts[$plan->name] = $allTeamMembers->where('level_id', $plan->id)->count();
        }

        // Total Commission Earned
        $totalCommission = Transaction::where('user_id', $user->id)
            ->whereIn('type', ['referral_commission', 'team_task_commission'])
            ->sum('amount');

        $referralCode = $user->uid; 
        $referralLink = url('/register?ref=' . $referralCode);

        return view('backend.users.pages.team', compact(
            'user', 'level1', 'level2', 'level3', 
            'totalTeam', 'activeTeam', 'totalCommission', 'referralLink', 'referralCode', 'planCounts'
        ));
    }
}
