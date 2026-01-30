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

            if ($request->has('status') && $request->status) {
                $query->where('status', $request->status);
            }

            if ($request->has('search') && $request->search) {
                $searchTerm = '%' . $request->search . '%';
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('title', 'like', $searchTerm)
                        ->orWhere('description', 'like', $searchTerm);
                });
            }

            if ($request->has('deadline_from') && $request->deadline_from) {
                $query->where('deadline', '>=', $request->deadline_from);
            }
            
            if ($request->has('deadline_to') && $request->deadline_to) {
                $query->where('deadline', '<=', $request->deadline_to);
            }
            
            if ($request->has('deadline_range') && $request->deadline_range) {
                $today = now()->format('Y-m-d');
                
                switch ($request->deadline_range) {
                    case 'today':
                        $query->where('deadline', $today);
                        break;
                    case 'week':
                        $query->whereBetween('deadline', [
                            now()->startOfWeek()->format('Y-m-d'),
                            now()->endOfWeek()->format('Y-m-d')
                        ]);
                        break;
                    case 'month':
                        $query->whereBetween('deadline', [
                            now()->startOfMonth()->format('Y-m-d'),
                            now()->endOfMonth()->format('Y-m-d')
                        ]);
                        break;
                    case 'overdue':
                        $query->where('deadline', '<', $today);
                        break;
                }
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
                'deadline' => 'required|string',
            ]);

            $task = Task::create([
                'user_id' => auth('api')->id(),
                'title' => $data['title'],
                'description' => $data['description'],
                'status' => $data['status'],
                'deadline' => $data['deadline'],
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
                'deadline' => 'required|string',
            ]);

            $task = Task::where('task_id', $data['task_id'])
                ->where('user_id', auth('api')->id())
                ->firstOrFail();

            $task->update([
                'title' => $data['title'],
                'description' => $data['description'],
                'status' => $data['status'],
                'deadline' => $data['deadline'],
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