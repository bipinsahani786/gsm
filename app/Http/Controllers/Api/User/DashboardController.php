<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Services\UserDashboardService;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(UserDashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    #[OA\Get(path: "/api/user/dashboard", summary: "Get User Dashboard Data", security: [["sanctum" => []]], tags: ["User Dashboard"])]
    #[OA\Response(response: 200, description: "Dashboard data fetched successfully")]
    #[OA\Response(response: 401, description: "Unauthenticated")]
    public function index(Request $request)
    {
        $data = $this->dashboardService->getDashboardData($request->user());

        return response()->json([
            'status' => true,
            'message' => 'Dashboard data loaded successfully',
            'data' => $data
        ], 200);
    }

    #[OA\Post(path: "/api/user/level/{id}/join", summary: "Join or Upgrade a Level", security: [["sanctum" => []]], tags: ["User Dashboard"])]
    #[OA\Parameter(name: "id", in: "path", required: true, description: "ID of the level to join", schema: new OA\Schema(type: "integer"))]
    #[OA\Response(response: 200, description: "Successfully joined/upgraded")]
    #[OA\Response(response: 400, description: "Business logic error (Insufficient balance, etc.)")]
    #[OA\Response(response: 401, description: "Unauthenticated")]
    public function joinLevel(Request $request, $id)
    {
        $result = $this->dashboardService->processLevelJoin($request->user(), $id);

        if (!$result['status']) {
            return response()->json([
                'status' => false,
                'message' => $result['message']
            ], 400); // 400 Bad Request for business logic errors
        }

        return response()->json([
            'status' => true,
            'message' => $result['message']
        ], 200);
    }

    #[OA\Get(path: "/api/user/guide", summary: "Get Banners and Income Methods", security: [["sanctum" => []]], tags: ["User Dashboard"])]
    #[OA\Response(response: 200, description: "Guide data fetched successfully")]
    #[OA\Response(response: 401, description: "Unauthenticated")]
    public function guide()
    {
        $data = $this->dashboardService->getGuideData();

        return response()->json([
            'status' => true,
            'message' => 'Guide data loaded successfully',
            'data' => $data
        ], 200);
    }
}