<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
     {
        if (is_null($request->name)) {
                return response()->json([
                    "errors"=> ["code"=> "ERROR-2",
                    "title"=>  "Unprocessable Entity",
                    ]]  , 422);
        }elseif (is_null($request->price)) {
                return response()->json([
                    "errors"=> ["code"=> "ERROR-3",
                    "title"=>  "Unprocessable Entity",
                    ]]  , 422);
        }elseif (!(is_numeric($request->price))) {
                return response()->json([
                   "errors"=> ["code"=> "ERROR-4",
                   "title"=>  "Unprocessable Entity",
                   ]]  , 422);
        }elseif (($request->price)<=0) {
                return response()->json([
                    "errors"=> ["code"=> "ERROR-5",
                    "title"=>  "Unprocessable Entity",
                    ]]  , 422);
        }else {
            // Create a new product
            $product = Product::create($request->all());

            // Return a response with a product json
            // representation and a 201 status code
            return response()->json($product,201);
        }

     }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        // Return a response with a product json
        // representation and a 200 status code   
        return $product->all();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function showItem(Product $product, $id)
    {
        try {
            $producto = Product::findOrFail($id);
            return $producto;
        } catch (ModelNotFoundException $exception) {
            $type = explode("\\", get_class($exception));
            $type = $type[count($type)-1];
            return response()->json([
                "errors"=> ["code"=> "ERROR-2",
                "title"=>  "Not Found",
                "type" => $type
                ]]  , 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!(is_numeric($request->price))) {
                return response()->json([
                "errors"=> ["code"=> "ERROR-1",
                "title"=>  "Unprocessable Entity",
                ]]  , 422);
        }elseif (($request->price)<=0) {
                return response()->json([
                    "errors"=> ["code"=> "ERROR-5",
                    "title"=>  "Unprocessable Entity",
                    ]]  , 422);
        }else{
            $product = Product::findorfail($id);
            $product->name=$request->name;
            $product->price=$request->price;
            $product->save();
            // Return a response with a product json
            // representation and a 201 status code   
            return response()->json($product,200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product, $id)
    {
        // Return a response with a product json
        // representation and a 200 status code   
        if(!find($id)){
            return response()->json([
                "errors"=> ["code"=> "ERROR-2",
                "title"=>  "Not Found",
                ]]  , 404);
        }
        else{
            Product::findOrFail($id);
            return $product->destroy($id);
        }
    }
}
