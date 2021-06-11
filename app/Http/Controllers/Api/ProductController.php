<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $user = $this->guard()->user();

        $categoires =  Product::orderBy('name', 'ASC')->get();

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
            'parent_category' => 'required'
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
        $exists = Product::where('name', $request->input('name'))
                        ->where('category_id', $request->input('parent_category'))
                        ->where('user_id', $request->user()->id)
                        ->first();

        if ($exists) {
            return response()->json([
                'status' => false,
                'errors' => ['name' => 'Already taken']
            ]);
        }

        try {
            // registered new user
            $category = new Product();
            $category->user_id = $user->id;
            $category->name = $request->input('name');
            $category->category_id = $request->input('parent_category');

            if ($category->save()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Sub category stored successfully',
                    'data' => $category,
                ]);

            }

            return response()->json([
                'status' => false,
                'message' => 'Failed to store sub category',
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
            'parent_category' => 'required'
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
        $exists = Product::where('name', $request->input('name'))
                        ->where('category_id', $request->input('parent_category'))
                        ->where('user_id', $request->user()->id)
                        ->where('id', '!=', $id)
                        ->first();

        if ($exists) {
            return response()->json([
                'status' => false,
                'errors' => ['name' => 'Already taken']
            ]);
        }

        try {
            // registered new user
            $category = Product::find($id);
            $category->name = $request->input('name');
            $category->category_id = $request->input('parent_category');

            if ($category->save()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Sub category updated successfully',
                    'data' => $category,
                ]);

            }

            return response()->json([
                'status' => false,
                'message' => 'Failed to update sub-category',
            ]);

        }catch(\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong, '. $e->getMessage()
            ]);
        }
    }

    public function destroy(Product $category, $id)
    {
        try {
            // registered new user
            $category = $category->find($id);

            if ($category->delete()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Sub category deleted successfully',
                ]);

            }

            return response()->json([
                'status' => false,
                'message' => 'Failed to delete sub ategory',
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
