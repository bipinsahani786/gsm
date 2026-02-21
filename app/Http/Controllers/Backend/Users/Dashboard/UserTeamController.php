<?php

namespace App\Http\Controllers\Backend\Users\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\UserTeamService;
use Illuminate\Support\Facades\Auth;

class UserTeamController extends Controller
{
    protected $teamService;

    public function __construct(UserTeamService $teamService)
    {
        $this->teamService = $teamService;
    }

    public function index()
    {
        // Service se pura 3-level tree data le aaye
        $data = $this->teamService->getTeamData(Auth::user());

        return view('backend.users.pages.team', $data);
    }
}