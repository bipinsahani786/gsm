<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Services\UserWithdrawService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use OpenApi\Attributes as OA;

class WithdrawController extends Controller
{
    protected $withdrawService;

    public function __construct(UserWithdrawService $withdrawService)
    {
        $this->withdrawService = $withdrawService;
    }

    #[OA\Get(path: "/api/user/withdraw/config", summary: "Get limits and bound accounts", security: [["sanctum" => []]], tags: ["User Withdrawal"])]
    #[OA\Response(response: 200, description: "Withdrawal limits and accounts fetched")]
    #[OA\Response(response: 401, description: "Unauthenticated")]
    public function config(Request $request)
    {
        $data = $this->withdrawService->getWithdrawData($request->user());

        return response()->json([
            'status' => true,
            'message' => 'Withdrawal configuration loaded',
            'data' => $data
        ], 200);
    }

    #[OA\Post(path: "/api/user/withdraw", summary: "Submit a Withdrawal Request", security: [["sanctum" => []]], tags: ["User Withdrawal"])]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ["amount", "wallet_type", "method"],
            properties: [
                new OA\Property(property: "amount", type: "number", example: 1000),
                new OA\Property(property: "wallet_type", type: "string", enum: ["personal", "income"], example: "income"),
                new OA\Property(property: "method", type: "string", enum: ["bank", "crypto"], example: "bank")
            ]
        )
    )]
    #[OA\Response(response: 200, description: "Withdrawal request submitted successfully")]
    #[OA\Response(response: 400, description: "Business logic error (Limits, Balance etc.)")]
    #[OA\Response(response: 422, description: "Validation Error")]
    #[OA\Response(response: 401, description: "Unauthenticated")]
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1',
            'wallet_type' => 'required|in:personal,income',
            'method' => 'required|in:bank,crypto'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Validation Error', 'errors' => $validator->errors()], 422);
        }

        $result = $this->withdrawService->processWithdraw($request->user(), $request->all());

        if (!$result['status']) {
            return response()->json(['status' => false, 'message' => $result['message']], 400);
        }

        return response()->json(['status' => true, 'message' => $result['message'], 'data' => $result['data'] ?? null], 200);
    }

    #[OA\Get(path: "/api/user/withdraw/history", summary: "Get withdrawal history", security: [["sanctum" => []]], tags: ["User Withdrawal"])]
    #[OA\Response(response: 200, description: "History fetched successfully")]
    #[OA\Response(response: 401, description: "Unauthenticated")]
    public function history(Request $request)
    {
        $data = $this->withdrawService->getHistory($request->user());

        return response()->json([
            'status' => true,
            'message' => 'Withdrawal history loaded',
            'data' => $data
        ], 200);
    }
}