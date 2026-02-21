<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Services\UserAccountService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use OpenApi\Attributes as OA;

class AccountController extends Controller
{
    protected $accountService;

    public function __construct(UserAccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    #[OA\Get(path: "/api/user/account/bind", summary: "Get saved Bank and Crypto details", security: [["sanctum" => []]], tags: ["User Account"])]
    #[OA\Response(response: 200, description: "Account details fetched successfully")]
    #[OA\Response(response: 401, description: "Unauthenticated")]
    public function bindIndex(Request $request)
    {
        $data = $this->accountService->getAccountDetails($request->user());

        return response()->json([
            'status' => true,
            'message' => 'Account details loaded successfully',
            'data' => $data
        ], 200);
    }

    #[OA\Post(path: "/api/user/account/bind", summary: "Bind or Update Bank/Crypto Account", security: [["sanctum" => []]], tags: ["User Account"])]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ["type"],
            properties: [
                new OA\Property(property: "type", type: "string", enum: ["bank", "crypto"], example: "bank"),
                new OA\Property(property: "bank_name", type: "string", example: "State Bank of India (Only for Bank)"),
                new OA\Property(property: "account_holder", type: "string", example: "Bipin Sahani (Only for Bank)"),
                new OA\Property(property: "account_number", type: "string", example: "1234567890 (Only for Bank)"),
                new OA\Property(property: "ifsc_code", type: "string", example: "SBIN0001234 (Only for Bank)"),
                new OA\Property(property: "network", type: "string", example: "TRC20 (Only for Crypto)"),
                new OA\Property(property: "wallet_address", type: "string", example: "Txyz123abc... (Only for Crypto)")
            ]
        )
    )]
    #[OA\Response(response: 200, description: "Account updated successfully")]
    #[OA\Response(response: 422, description: "Validation Error")]
    #[OA\Response(response: 401, description: "Unauthenticated")]
    public function bindStore(Request $request)
    {
        // API level strict validation (agar type bank hai toh baaki details required hain)
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:bank,crypto',
            'bank_name' => 'required_if:type,bank|string|max:100',
            'account_holder' => 'required_if:type,bank|string|max:100',
            'account_number' => 'required_if:type,bank|string|max:50',
            'ifsc_code' => 'required_if:type,bank|string|max:20',
            'network' => 'required_if:type,crypto|string|max:50',
            'wallet_address' => 'required_if:type,crypto|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        $this->accountService->bindAccount($request->user(), $request->all());

        return response()->json([
            'status' => true,
            'message' => strtoupper($request->type) . ' details updated successfully!'
        ], 200);
    }
}