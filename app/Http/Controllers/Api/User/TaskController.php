<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Services\UserTaskService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use OpenApi\Attributes as OA;

class TaskController extends Controller
{
    protected $taskService;

    public function __construct(UserTaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    #[OA\Get(path: "/api/user/tasks", summary: "Get Daily Task Status", security: [["sanctum" => []]], tags: ["User Tasks"])]
    #[OA\Response(response: 200, description: "Task data fetched successfully")]
    #[OA\Response(response: 400, description: "No active level found")]
    #[OA\Response(response: 401, description: "Unauthenticated")]
    public function index(Request $request)
    {
        $data = $this->taskService->getTaskData($request->user());

        if (!$data['status']) {
            return response()->json([
                'status' => false,
                'message' => $data['message']
            ], 400);
        }

        return response()->json([
            'status' => true,
            'message' => 'Task data loaded',
            'data' => [
                'level_name' => $data['level']->name,
                'task_rate' => $data['level']->rate,
                'daily_limit' => $data['level']->daily_limit,
                'completed_today' => $data['todayTasksCompleted'],
                'random_image' => $data['randomImage']
            ]
        ], 200);
    }

    #[OA\Post(path: "/api/user/tasks/submit", summary: "Submit a task and earn", security: [["sanctum" => []]], tags: ["User Tasks"])]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ["rating"],
            properties: [
                new OA\Property(property: "rating", type: "integer", description: "Star rating between 1 to 5", example: 5)
            ]
        )
    )]
    #[OA\Response(response: 200, description: "Task completed successfully")]
    #[OA\Response(response: 400, description: "Limit reached or error")]
    #[OA\Response(response: 422, description: "Validation Error")]
    #[OA\Response(response: 401, description: "Unauthenticated")]
    public function submit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Validation Error', 'errors' => $validator->errors()], 422);
        }

        $result = $this->taskService->submitTask($request->user(), $request->all());

        if (!$result['status']) {
            return response()->json(['status' => false, 'message' => $result['message']], 400);
        }

        return response()->json(['status' => true, 'message' => $result['message']], 200);
    }
}