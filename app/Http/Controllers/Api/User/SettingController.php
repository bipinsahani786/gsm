<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Services\UserSettingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use OpenApi\Attributes as OA;

class SettingController extends Controller
{
    protected $settingService;

    public function __construct(UserSettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    #[OA\Post(path: "/api/user/settings/change-password", summary: "Change Account Password", security: [["sanctum" => []]], tags: ["User Settings"])]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ["current_password", "new_password", "new_password_confirmation"],
            properties: [
                new OA\Property(property: "current_password", type: "string", format: "password", example: "oldsecret123"),
                new OA\Property(property: "new_password", type: "string", format: "password", example: "newsecret123"),
                new OA\Property(property: "new_password_confirmation", type: "string", format: "password", example: "newsecret123")
            ]
        )
    )]
    #[OA\Response(response: 200, description: "Password updated successfully")]
    #[OA\Response(response: 400, description: "Incorrect current password")]
    #[OA\Response(response: 422, description: "Validation Error")]
    #[OA\Response(response: 401, description: "Unauthenticated")]
    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed', // Must match new_password_confirmation
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Validation Error', 'errors' => $validator->errors()], 422);
        }

        $result = $this->settingService->updatePassword($request->user(), $request->all());

        if (!$result['status']) {
            return response()->json(['status' => false, 'message' => $result['message']], 400);
        }

        return response()->json(['status' => true, 'message' => $result['message']], 200);
    }

    #[OA\Get(path: "/api/user/settings/transactions", summary: "Get User Transactions (Passbook)", security: [["sanctum" => []]], tags: ["User Settings"])]
    #[OA\Parameter(name: "per_page", in: "query", description: "Items per page (default: 20)", required: false, schema: new OA\Schema(type: "integer"))]
    #[OA\Parameter(name: "page", in: "query", description: "Page number", required: false, schema: new OA\Schema(type: "integer"))]
    #[OA\Response(response: 200, description: "Transactions fetched successfully")]
    #[OA\Response(response: 401, description: "Unauthenticated")]
    public function transactions(Request $request)
    {
        // Get per_page from query parameter or default to 20
        $perPage = $request->query('per_page', 20);
        
        $transactions = $this->settingService->getTransactions($request->user(), $perPage);

        return response()->json([
            'status' => true,
            'message' => 'Transactions loaded',
            'data' => $transactions
        ], 200);
    }
}