<?php

namespace App\Http\Controllers\Backend\Admins\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Configuration;
use Illuminate\Http\Request;

class AdminConfigurationController extends Controller
{
    public function index()
    {
        $config = Configuration::pluck('value', 'key')->all();
        return view('backend.admins.pages.settings', compact('config'));
    }

    public function update(Request $request)
    {
        // ===== BASIC SETTINGS =====
        Configuration::updateOrCreate(['key' => 'upi_id'], ['value' => $request->upi_id]);
        Configuration::updateOrCreate(['key' => 'min_recharge'], ['value' => $request->min_recharge]);
        Configuration::updateOrCreate(['key' => 'usdt_rate'], ['value' => $request->usdt_rate]);

        // Referral Percentages Save Karein
        Configuration::updateOrCreate(['key' => 'referral_l1'], ['value' => $request->referral_l1]);
        Configuration::updateOrCreate(['key' => 'referral_l2'], ['value' => $request->referral_l2]);
        Configuration::updateOrCreate(['key' => 'referral_l3'], ['value' => $request->referral_l3]);

        // ===== TASK COMMISSION PERCENTAGES SAVE KAREIN =====
        Configuration::updateOrCreate(['key' => 'task_commission_l1'], ['value' => $request->task_commission_l1]);
        Configuration::updateOrCreate(['key' => 'task_commission_l2'], ['value' => $request->task_commission_l2]);
        Configuration::updateOrCreate(['key' => 'task_commission_l3'], ['value' => $request->task_commission_l3]);

        if ($request->hasFile('qr_code')) {

            $file = $request->file('qr_code');
            $name = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/payments'), $name);

            Configuration::updateOrCreate([
                'key' => 'qr_code'
            ], [
                'value' => 'uploads/payments/' . $name
            ]);
        }


        // ===== USDT NETWORK SETTINGS =====
        $existing_methods = json_decode(Configuration::get('usdt_methods', '[]'), true);
        $usdt_data = [];

        if ($request->networks) {
            foreach ($request->networks as $index => $network) {

                $qr_path = $existing_methods[$index]['qr'] ?? null;

                if ($request->hasFile("usdt_qrs.$index")) {

                    $file = $request->file("usdt_qrs.$index");
                    $name = time() . '_' . $index . '_' . $file->getClientOriginalName();
                    $file->move(public_path('uploads/payments'), $name);

                    $qr_path = 'uploads/payments/' . $name;
                }

                $usdt_data[] = [
                    'network' => $network,
                    'address' => $request->addresses[$index] ?? null,
                    'qr' => $qr_path,
                    'min_recharge' => $request->network_min[$index] ?? 0,
                    'rate' => $request->network_rate[$index] ?? 0,
                ];
            }
        }

        Configuration::updateOrCreate([
            'key' => 'usdt_methods'
        ], [
            'value' => json_encode($usdt_data)
        ]);

        return back()->with('success', 'Settings Updated!');
    }

    public function withdrawalSettings()
    {
        // Saari settings ek saath fetch karein
        $settings = Configuration::whereIn('key', [
            'min_withdraw_personal',
            'min_withdraw_income',
            'max_withdraw_limit',
            'max_withdraw_daily',
            'withdraw_commission',
            'daily_withdraw_limit'
        ])->pluck('value', 'key');

        return view('backend.admins.pages.withdrawal_settings', compact('settings'));
    }

    public function updateWithdrawalSettings(Request $request)
    {
        $data = $request->except('_token');

        foreach ($data as $key => $value) {
            Configuration::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return back()->with('success', 'Withdrawal settings updated successfully!');
    }
}
