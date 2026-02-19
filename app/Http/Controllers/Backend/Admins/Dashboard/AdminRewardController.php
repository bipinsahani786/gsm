<?php

namespace App\Http\Controllers\Backend\Admins\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Level;
use App\Models\UpgradeReward;
use Illuminate\Http\Request;

class AdminRewardController extends Controller
{
    public function index()
    {
        $levels = Level::where('status', 1)->get();
        $rewards = UpgradeReward::orderBy('created_at', 'desc')->get();

        return view('backend.admins.pages.upgrade_rewards', compact('levels', 'rewards'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'to_level_id' => 'required|integer',
            'reward_amount' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        UpgradeReward::create([
            'from_level_id' => $request->from_level_id, // can be null
            'to_level_id' => $request->to_level_id,
            'reward_amount' => $request->reward_amount,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => 1
        ]);

        return back()->with('success', 'Promotional Reward added successfully!');
    }

    public function destroy($id)
    {
        UpgradeReward::findOrFail($id)->delete();
        return back()->with('success', 'Reward deleted successfully!');
    }

    public function toggleStatus($id)
    {
        $reward = UpgradeReward::findOrFail($id);
        $reward->status = !$reward->status;
        $reward->save();

        $message = $reward->status ? 'Promotion Activated Successfully!' : 'Promotion Paused!';
        return back()->with('success', $message);
    }
}
