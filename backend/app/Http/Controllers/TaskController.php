<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class TaskController extends Controller
{
    public function getUserTasks(Request $request)
    {
        try {
            $query = Task::with('user:user_id,name')
                ->where('user_id', auth('api')->id());

            // Filter by status
            if ($request->has('status') && $request->status) {
                $query->where('status', $request->status);
            }

            // Search by title or description
            if ($request->has('search') && $request->search) {
                $searchTerm = '%' . $request->search . '%';
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('title', 'like', $searchTerm)
                        ->orWhere('description', 'like', $searchTerm);
                });
            }

            $tasks = $query->get();
            return response()->json($tasks);

        } catch (\Exception $e) {
            \Log::error('Get user tasks failed: ' . $e->getMessage());
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function createTask(Request $request)
    {
        try {
            $data = $request->validate([
                'title' => 'required|string',
                'description' => 'required|string',
                'status' => 'required|string',
                'dateline' => 'required|string',
            ]);

            $task = Task::create([
                'user_id' => auth('api')->id(),
                'title' => $data['title'],
                'description' => $data['description'],
                'status' => $data['status'],
                'dateline' => $data['dateline'],
                'created_by' => auth('api')->user()->name,
            ]);

            return response()->json($task, 201);

        } catch (\Exception $e) {
            \Log::error('Create task failed: ' . $e->getMessage());
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function editTask(Request $request)
    {
        try {
            $data = $request->validate([
                'task_id' => 'required|integer',
                'title' => 'required|string',
                'description' => 'required|string',
                'status' => 'required|string',
                'dateline' => 'required|string',
            ]);

            $task = Task::where('task_id', $data['task_id'])
                ->where('user_id', auth('api')->id())
                ->firstOrFail();

            $task->update([
                'title' => $data['title'],
                'description' => $data['description'],
                'status' => $data['status'],
                'dateline' => $data['dateline'],
            ]);

            return response()->json($task, 200);

        } catch (\Exception $e) {
            \Log::error('Edit task failed: ' . $e->getMessage());
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function deleteTask(Request $request)
    {
        try {
            $data = $request->validate([
                'task_id' => 'required|integer',
            ]);

            $task = Task::where('task_id', $data['task_id'])
                ->where('user_id', auth('api')->id())
                ->firstOrFail();

            $task->delete();

            return response()->json(['message' => 'Task deleted successfully'], 200);

        } catch (\Exception $e) {
            \Log::error('Delete task failed: ' . $e->getMessage());
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
}