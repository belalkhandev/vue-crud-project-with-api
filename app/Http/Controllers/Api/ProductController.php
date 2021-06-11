<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\FileUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $user = $this->guard()->user();

        $products =  Product::get();

        if ($products->isNotEmpty()) {
            return response()->json([
                'status' => true,
                'data' => $products
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
            'category' => 'required',
            'image' => 'mimes:jpg,jpeg,png,gif,svg'
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
                        ->where('user_id', $request->user()->id)
                        ->first();

        if ($exists) {
            return response()->json([
                'status' => false,
                'errors' => ['name' => 'Already this product stored']
            ]);
        }

        try {
            // registered new user
            $product = new Product();
            $product->user_id = $user->id;
            $product->name = $request->input('name');
            $product->category_id = $request->input('category');
            $product->sub_category_id = $request->input('sub_category') ? $request->input('sub_category') : null;
            $path = null;

            //upload images
            if ($request->hasFile('image')) {
                $path = FileUpload::upload($request, 'image', 'products');
            }

            $product->image = $path;

            if ($product->save()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Product stored successfully',
                    'data' => $product,
                ]);

            }

            return response()->json([
                'status' => false,
                'message' => 'Failed to store product',
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
            'category' => 'required',
            'image' => 'mimes:jpg,jpeg,png,gif,svg'
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
                        ->where('user_id', $request->user()->id)
                        ->where('id', '!=', $id)
                        ->first();

        if ($exists) {
            return response()->json([
                'status' => false,
                'errors' => ['name' => 'Already taken this product']
            ]);
        }

        try {
            // registered new user
            $product = Product::find($id);
            $product->user_id = $user->id;
            $product->name = $request->input('name');
            $product->category_id = $request->input('category');
            $product->sub_category_id = $request->input('sub_category') ? $request->input('sub_category') : null;

            //upload images
            if ($request->hasFile('image')) {
                //delete old photo
                $old_image = $product->image;
                $path = FileUpload::upload($request, 'image', 'products');
                $product->image = $path;

                if ($old_image) {
                    unlink($old_image);
                }
            }

            if ($product->save()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Product updated successfully',
                    'data' => $product,
                ]);

            }

            return response()->json([
                'status' => false,
                'message' => 'Failed to update product',
            ]);

        }catch(\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong, '. $e->getMessage()
            ]);
        }
    }

    public function destroy(Product $product, $id)
    {
        try {
            // registered new user
            $product = $product->find($id);
            $image = $product->iamge;

            if ($product->delete()) {
                if ($image) {
                    unlink($image);
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Product deleted successfully',
                ]);

            }

            return response()->json([
                'status' => false,
                'message' => 'Failed to delete product',
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
