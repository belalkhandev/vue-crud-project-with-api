<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $user = $this->guard()->user();

        $categoires =  Category::orderBy('name', 'ASC')->get();

        if ($categoires->isNotEmpty()) {
            return response()->json([
                'status' => true,
                'data' => $categoires
            ]);
        }

        return response()->json([
            'status' => false,
            'data' => null,
            'message' => 'No data found'
        ]);
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
        ];

        //validation check
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        $user = $this->guard()->user();
        //check unique
        $exists = Category::where('name', $request->input('name'))
                        ->where('user_id', $request->user()->id)
                        ->first();

        if ($exists) {
            return response()->json([
                'status' => false,
                'errors' => ['name' => 'Category already taken']
            ]);
        }

        try {
            // registered new user
            $category = new Category();
            $category->user_id = $user->id;
            $category->name = $request->input('name');

            if ($category->save()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Category stored successfully',
                    'data' => $category,
                ]);

            }

            return response()->json([
                'status' => false,
                'message' => 'Failed to store category',
            ]);

        }catch(\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong, '. $e->getMessage()
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'required',
        ];

        //validation check
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        $user = $this->guard()->user();
        //check unique
        $exists = Category::where('name', $request->input('name'))
                        ->where('user_id', $request->user()->id)
                        ->where('id', '!=', $id)
                        ->first();

        if ($exists) {
            return response()->json([
                'status' => false,
                'errors' => ['name' => 'Category already taken']
            ]);
        }

        try {
            // registered new user
            $category = Category::find($id);
            $category->name = $request->input('name');

            if ($category->save()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Category updated successfully',
                    'data' => $category,
                ]);

            }

            return response()->json([
                'status' => false,
                'message' => 'Failed to update category',
            ]);

        }catch(\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong, '. $e->getMessage()
            ]);
        }
    }

    public function destroy(Category $category, $id)
    {
        try {
            // registered new user
            $category = $category->find($id);

            if ($category->delete()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Category deleted successfully',
                ]);

            }

            return response()->json([
                'status' => false,
                'message' => 'Failed to delete category',
            ]);

        }catch(\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong, '. $e->getMessage()
            ]);
        }
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    public function guard()
    {
        return Auth::guard('api');
    }
}
