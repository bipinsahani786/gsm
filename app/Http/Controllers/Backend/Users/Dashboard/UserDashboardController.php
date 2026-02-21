<?php

namespace App\Http\Controllers\Backend\Users\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\UserDashboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(UserDashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function profile()
    {
        return view('backend.users.pages.profile');
    }

    // Dashboard Home
    public function index()
    {
        $data = $this->dashboardService->getDashboardData(Auth::user());

        return view('backend.users.pages.dashboard', $data);
    }

    // Join / Upgrade Level
    public function joinLevel(Request $request, $id)
    {
        $result = $this->dashboardService->processLevelJoin(Auth::user(), $id);

        if (!$result['status']) {
            return back()->with('error', $result['message']);
        }

        return back()->with('success', $result['message']);
    }

    // Guide / Income Methods Page
    public function guide()
    {

        $data = $this->dashboardService->getGuideData();
        
        return view('backend.users.pages.guide', $data);
    }
}