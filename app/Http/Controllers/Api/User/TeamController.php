<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Services\UserTeamService;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class TeamController extends Controller
{
    protected $teamService;

    public function __construct(UserTeamService $teamService)
    {
        $this->teamService = $teamService;
    }

    #[OA\Get(path: "/api/user/team", summary: "Get User's 3-Level Team Network", security: [["sanctum" => []]], tags: ["User Team"])]
    #[OA\Response(response: 200, description: "Team network data fetched successfully")]
    #[OA\Response(response: 401, description: "Unauthenticated")]
    public function index(Request $request)
    {
        $data = $this->teamService->getTeamData($request->user());

        // JSON response ko achhe structure mein format kar rahe hain
        return response()->json([
            'status' => true,
            'message' => 'Team network loaded successfully',
            'data' => [
                'statistics' => [
                    'total_team' => $data['totalTeam'],
                    'active_team' => $data['activeTeam'],
                    'total_commission' => $data['totalCommission'],
                    'plan_wise_count' => $data['planCounts'],
                ],
                'referral' => [
                    'code' => $data['referralCode'],
                    'link' => $data['referralLink'],
                ],
                'levels' => [
                    'level_1' => $data['level1'],
                    'level_2' => $data['level2'],
                    'level_3' => $data['level3'],
                ]
            ]
        ], 200);
    }
}