<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Services\UserRechargeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use OpenApi\Attributes as OA;

class RechargeController extends Controller
{
    protected $rechargeService;

    public function __construct(UserRechargeService $rechargeService)
    {
        $this->rechargeService = $rechargeService;
    }

    #[OA\Get(path: "/api/user/recharge/config", summary: "Get UPI/USDT details for recharge", security: [["sanctum" => []]], tags: ["User Recharge"])]
    #[OA\Response(response: 200, description: "Configuration fetched successfully")]
    #[OA\Response(response: 401, description: "Unauthenticated")]
    public function config()
    {
        $data = $this->rechargeService->getRechargeConfig();

        return response()->json([
            'status' => true,
            'message' => 'Recharge configuration loaded',
            'data' => $data
        ], 200);
    }

    #[OA\Post(path: "/api/user/recharge", summary: "Submit a new recharge request", security: [["sanctum" => []]], tags: ["User Recharge"])]
    #[OA\RequestBody(
        required: true,
        content: new OA\MediaType(
            mediaType: "multipart/form-data",
            schema: new OA\Schema(
                required: ["amount", "method", "screenshot"],
                properties: [
                    new OA\Property(property: "amount", type: "number", description: "Minimum amount usually 500", example: 1000),
                    new OA\Property(property: "method", type: "string", enum: ["upi", "usdt"], example: "upi"),
                    new OA\Property(property: "utr", type: "string", description: "Required if method is upi (12 digit UTR number)", example: "123456789012"),
                    new OA\Property(property: "hash", type: "string", description: "Required if method is usdt (TxHash)", example: "Txabcdef123456789"),
                    new OA\Property(property: "screenshot", type: "string", format: "binary", description: "Payment screenshot image file")
                ]
            )
        )
    )]
    #[OA\Response(response: 200, description: "Recharge request submitted")]
    #[OA\Response(response: 422, description: "Validation Error")]
    #[OA\Response(response: 401, description: "Unauthenticated")]
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:500',
            'method' => 'required|in:upi,usdt',
            'utr' => 'required_if:method,upi',
            'hash' => 'required_if:method,usdt',
            'screenshot' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Validation Error', 'errors' => $validator->errors()], 422);
        }

        $result = $this->rechargeService->submitRecharge($request->user(), $request->all(), $request->file('screenshot'));

        if (!$result['status']) {
            return response()->json(['status' => false, 'message' => $result['message']], 400);
        }

        return response()->json(['status' => true, 'message' => $result['message'], 'data' => $result['data']], 200);
    }

    #[OA\Get(path: "/api/user/recharge/history", summary: "Get user recharge history", security: [["sanctum" => []]], tags: ["User Recharge"])]
    #[OA\Response(response: 200, description: "History fetched successfully")]
    #[OA\Response(response: 401, description: "Unauthenticated")]
    public function history(Request $request)
    {
        $data = $this->rechargeService->getRechargeHistory($request->user());

        return response()->json([
            'status' => true,
            'message' => 'Recharge history loaded',
            'data' => $data
        ], 200);
    }
}