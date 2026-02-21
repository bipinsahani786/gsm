<?php

namespace App\Http\Controllers\Backend\Users\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\UserTaskService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserTaskController extends Controller
{
    protected $taskService;

    public function __construct(UserTaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function index()
    {
        $data = $this->taskService->getTaskData(Auth::user());

        if (!$data['status']) {
            return redirect()->route('user.dashboard')->with('error', $data['message']);
        }

        return view('backend.users.pages.tasks', $data);
    }

    public function submit(Request $request)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5'
        ]);

        $result = $this->taskService->submitTask(Auth::user(), $request->all());

        if (!$result['status']) {
            return back()->with('error', $result['message']);
        }

        return back()->with('success', $result['message']);
    }
}