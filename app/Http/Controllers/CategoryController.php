<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->user = auth()->guard('api')->user();
    }

    public function getAll()
    {
        try {

            $categories = Category::where('user_id', $this->user->id)->get();
            return response()->json([
                'success' => true,
                'data' => $categories,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to fetch categories',
            ], 500);
        }
    }

    public function getDetail($id)
    {
        try {
            
            $category = Category::where('user_id', $this->user->id)->findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $category,
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to fetch category',
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'color' => 'required',
                'icon' => 'required',
                'user_id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $category = Category::create([
                'name' => $request->name,
                'color' => $request->color,
                'icon' => $request->icon,
                'user_id' => $this->user->id,
            ]);

            return response()->json([
                'success' => true,
                'data' => $category,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to create category',
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'color' => 'required',
                'icon' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $category = Category::where('user_id', $this->user->id)->findOrFail($id);
            $category->update([
                'name' => $request->name,
                'color' => $request->color,
                'icon' => $request->icon,
                'isEditable' => true,
            ]);
            return response()->json([
                'success' => true,
                'data' => $category,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to update category',
            ], 500);
        }
    }

    public function delete($id)
    {
        try {
            $category = Category::where('user_id', $this->user->id)->findOrFail($id);
            $category->delete();
            return response()->json([
                'success' => true,
                'data' => $category,
                'message' => 'Category deleted successfully',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to delete category',
            ], 500);
        }
    }
}
