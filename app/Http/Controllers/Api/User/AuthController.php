<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use OpenApi\Attributes as OA;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    #[OA\Post(path: "/api/user/register", summary: "Register a new user", tags: ["User Authentication"])]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ["name", "mobile", "password", "password_confirmation"],
            properties: [
                new OA\Property(property: "name", type: "string", example: "Bipin Sahani"),
                new OA\Property(property: "mobile", type: "string", example: "9876543210"),
                new OA\Property(property: "password", type: "string", format: "password", example: "secret123"),
                new OA\Property(property: "password_confirmation", type: "string", format: "password", example: "secret123"),
                new OA\Property(property: "rid", type: "string", example: "SP123456")
            ]
        )
    )]
    #[OA\Response(response: 201, description: "User registered successfully")]
    #[OA\Response(response: 422, description: "Validation Error")]
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'mobile' => 'required|numeric|unique:users',
            'password' => 'required|min:6|confirmed',
            'rid' => 'nullable|exists:users,uid',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Validation Error', 'errors' => $validator->errors()], 422);
        }

        $user = $this->authService->registerUser($request->all());
        // dd($user);
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['status' => true, 'message' => 'Registered successfully', 'data' => $user, 'token' => $token], 201);
    }

    #[OA\Post(path: "/api/user/login", summary: "Login to get access token", tags: ["User Authentication"])]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ["login_input", "password"],
            properties: [
                new OA\Property(property: "login_input", type: "string", example: "9876543210"),
                new OA\Property(property: "password", type: "string", format: "password", example: "secret123")
            ]
        )
    )]
    #[OA\Response(response: 200, description: "Login successful")]
    #[OA\Response(response: 401, description: "Invalid credentials")]
    #[OA\Response(response: 403, description: "Account is blocked")]
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login_input' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Validation Error', 'errors' => $validator->errors()], 422);
        }

        $user = $this->authService->verifyCredentials($request->login_input, $request->password);

        if (!$user) {
            return response()->json(['status' => false, 'message' => 'Invalid UID/Mobile or password'], 401);
        }

        // if ($user->status == 0) {
        //     return response()->json(['status' => false, 'message' => 'Your account has been blocked.'], 403);
        // }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['status' => true, 'message' => 'Login successful', 'data' => $user, 'token' => $token], 200);
    }

    #[OA\Get(path: "/api/user/profile", summary: "Get logged in user profile details", security: [["sanctum" => []]], tags: ["User Authentication"])]
    #[OA\Response(response: 200, description: "Profile fetched successfully")]
    #[OA\Response(response: 401, description: "Unauthenticated")]
    public function profile(Request $request)
    {
        $user = $request->user()->load(['wallet', 'level', 'position']);
        return response()->json(['status' => true, 'message' => 'Profile fetched successfully', 'data' => $user], 200);
    }

    #[OA\Post(path: "/api/user/logout", summary: "Logout and invalidate token", security: [["sanctum" => []]], tags: ["User Authentication"])]
    #[OA\Response(response: 200, description: "Logged out successfully")]
    public function logout(Request $request)
    {
        // dd($request->user());
        $request->user()->currentAccessToken()->delete();
        return response()->json(['status' => true, 'message' => 'Logged out successfully'], 200);
    }
}