<?php

namespace App\Http\Controllers\Backend\Users\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Configuration;
use App\Models\Deposit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserRechargeController extends Controller
{
    public function index()
    {
        // 1. Data fetch karein
        $config = Configuration::all()->pluck('value', 'key');

        // 2. Variables assign karein (Fallback ke saath)
        $upi_id = $config['upi_id'] ?? '';
        $qr_code = $config['qr_code'] ?? '';
        $usdt_rate = $config['usdt_rate'] ?? 92;
        $min_recharge = $config['min_recharge'] ?? 500;

        // 3. JSON decode karein 
        $usdt_methods = isset($config['usdt_methods'])
            ? json_decode($config['usdt_methods'], true)
            : [];

        return view('backend.users.pages.recharge', compact(
            'upi_id',
            'qr_code',
            'usdt_methods',
            'usdt_rate',
            'min_recharge'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:500',
            'method' => 'required|in:upi,usdt',
            'screenshot' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            DB::beginTransaction(); // Transaction start karein

            $deposit = new Deposit();
            $deposit->user_id = Auth::id();
            $deposit->amount = $request->amount;
            $deposit->method = $request->method;

            // UPI ke liye UTR, USDT ke liye Hash uthayein
            $deposit->utr_hash = ($request->method == 'upi') ? $request->utr : $request->hash;

            // Screenshot Upload Logic
            if ($request->hasFile('screenshot')) {
                $file = $request->file('screenshot');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/deposits'), $filename);
                $deposit->screenshot = 'uploads/deposits/' . $filename;
            }

            $deposit->save();

            DB::commit();
            return back()->with('success', 'Recharge request submitted successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function history()
    {
      
        $deposits = Deposit::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get(); //

        return view('backend.users.pages.recharge_history', compact('deposits'));
    }
}
