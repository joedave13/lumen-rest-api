<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $products = Product::all();

        return response()->json([
            'message' => 'Product retrieved.',
            'products' => $products
        ]);
    }
    
    public function create(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'price' => 'required|integer',
            'color' => 'required|string',
            'condition' => 'required|string|in:Baru,Lama',
            'description' => 'nullable|string'
        ]);

        $data = $request->all();
        $product = Product::create($data);

        return response()->json([
            'message' => 'Product created.',
            'product' => $product
        ], 201);
    }

    public function show($id)
    {
        $product = Product::find($id);

        if ($product) {
            return response()->json([
                'message' => 'Product detail retrieved',
                'product' => $product
            ]);
        }
        else {
            return response()->json([
                'message' => 'Product not found.'
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'string',
            'price' => 'integer',
            'color' => 'string',
            'condition' => 'string|in:Baru,Lama',
            'description' => 'string'
        ]);

        $data = $request->all();
        $product = Product::find($id);

        if ($product) {
            $product->update($data);

            return response()->json([
                'message' => 'Product updated.',
                'product' => $product
            ]);
        }
        else {
            return response()->json([
                'message' => 'Product not found.'
            ], 404);
        }
    }

    public function destroy($id)
    {
        $product = Product::find($id);

        if ($product) {
            $product->delete();

            return response()->json([
                'message' => 'Product deleted.'
            ]);
        }
        else {
            return response()->json([
                'message' => 'Product not found.'
            ], 404);
        }
    }
}