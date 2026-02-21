<?php

namespace App\Http\Controllers\Backend\Users\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\UserAccountService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserAccountController extends Controller
{
    protected $accountService;

    public function __construct(UserAccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    public function bindIndex()
    {
        $data = $this->accountService->getAccountDetails(Auth::user());
        
        return view('backend.users.pages.bind_account', $data);
    }

    public function bindStore(Request $request)
    {
        $request->validate([
            'type' => 'required|in:bank,crypto'
        ]);

        $this->accountService->bindAccount(Auth::user(), $request->all());

        return back()->with('success', strtoupper($request->type) . ' details updated successfully!');
    }
}