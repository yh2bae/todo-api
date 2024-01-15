<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->user = auth()->guard('api')->user();
    }

    public function getAll()
    {
        try {
            $tasks = Task::where('user_id', $this->user->id)->get();

            return response()->json([
                'success' => true,
                'data' => $tasks,
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to fetch tasks',
            ], 500);
        }
    }

    public function getAllByCategory($category_id)
    {
        try {
            $tasks = Task::where('user_id', $this->user->id)->where('category_id', $category_id)->get();

            return response()->json([
                'success' => true,
                'data' => $tasks,
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to fetch tasks',
            ], 500);
        }
    }

    public function getAllCompleted()
    {
        try {
            $tasks = Task::where('user_id', $this->user->id)->where('isCompleted', true)->get();

            return response()->json([
                'success' => true,
                'data' => $tasks,
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to fetch tasks',
            ], 500);
        }
    }

    public function getTaskForToday()
    {
        try {
            $tasks = Task::where('user_id', $this->user->id)->where('date', date('Y-m-d'))->get();

            return response()->json([
                'success' => true,
                'data' => $tasks,
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to fetch tasks',
            ], 500);
        }
    }

    public function getDetail($id)
    {
        try {
            $task = Task::where('user_id', $this->user->id)->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $task,
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to fetch task',
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'category_id' => 'required',
                'user_id' => 'required',
                'date' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unable to create task',
                    'errors' => $validator->errors(),
                ], 400);
            }

            $task = Task::create([
                'name' => $request->name,
                'category_id' => $request->category_id,
                'user_id' => $this->user->id,
                'date' => $request->date,
            ]);

            return response()->json([
                'success' => true,
                'data' => $task,
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to create task',
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $task = Task::where('user_id', $this->user->id)->findOrFail($id);

            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'category_id' => 'required',
                'date' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unable to update task',
                    'errors' => $validator->errors(),
                ], 400);
            }

            $task->update([
                'name' => $request->name,
                'category_id' => $request->category_id,
                'date' => $request->date,
            ]);

            return response()->json([
                'success' => true,
                'data' => $task,
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to update task',
            ], 500);
        }
    }

    public function delete($id)
    {
        try {
            $task = Task::where('user_id', $this->user->id)->findOrFail($id);
            $task->delete();

            return response()->json([
                'success' => true,
                'message' => 'Task deleted successfully',
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to delete task',
            ], 500);
        }
    }
}
