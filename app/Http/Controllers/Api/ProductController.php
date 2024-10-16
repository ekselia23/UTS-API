<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //get all character with relasi on table characters, levels and users
        $product = Product::with(['categories',]) ->latest()->paginate(5);


        //response
        $response = [
            'message'   => 'List all category',
            'data'      => $product,
        ];


        return response()->json($response, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //validasi data
        $validator = Validator::make($request->all(),[
            'category_id' => 'required',
            'product' => 'required|min:2|unique:products',
            'description' => 'required',
            'price' => 'required|integer',
            'stock' => 'required|integer',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);


        //jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid field',
                'errors' => $validator->errors()
            ],422);
        }

        //upload image character to storage
        $image = $request->file('image');
        $image->storeAs('public/posts', $image->hashName());

        //insert character to database
        $product = Product::create([
            'category_id' => $request->category_id,
            'product' => $request->product,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'image' => $image->hashName(),
        ]);


        //response
        $response = [
            'success'   => 'Add Product success',
            'data'      => $product,
        ];


        return response()->json($response, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //find Gameplay by ID
        $product = Product::with(['categories'])->find($id);


        //response
        $response = [
            'success'   => 'Detail Product',
            'data'      => $product,
        ];


        return response()->json($response, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required',
            'product'     => 'required|unique:products|min:2',
            'description' => 'required',
            'price'       => 'required|integer',
            'stock'       => 'required|integer',
        ]);


        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }


        //find categoty by ID
        $product = Product::find($id);


        $product->update([
            'product' => $request->product,

        ]);


        //response
        $response = [
            'status' => 'success',
            'massage'   => 'Update product success',
            'data'      => $product,
        ];


        return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //find gameplay by ID
        $product = Product::find($id);


        if (isset($product)) {


            //delete post
            $product->delete();


            $response = [
                'success'   => 'Delete product Success',
            ];
            return response()->json($response, 200);


        } else {
            //jika data gameplay tidak ditemukan
            $response = [
                'success'   => 'Data product Not Found',
            ];


            return response()->json($response, 404);
        }

    }

}
