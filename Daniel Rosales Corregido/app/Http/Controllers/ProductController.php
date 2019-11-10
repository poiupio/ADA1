<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Resources\ProductCollection;
use Illuminate\Database\QueryException;
use App\Http\Resources\Product as ProductResource;
use \Exception;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$datos['products'] = Product::paginate(20);
        //return view('index', $datos);
         //return view('createProd');
        //
    }
    
        /**
     * Display all resources.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function showAll(Product $product)
    {
        //return $product->all();
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        //return view('createProd');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try 
        {
            $nombre = $request->data["attributes"]["name"];
            $precio = $request->data["attributes"]["price"];

            if(!is_numeric($precio) || ($precio <= 0)){
                $error = 'Price error';
                throw new Exception($error);
            }

            // Create a new product
            $product = new ProductResource(Product::create(["name" => $nombre, "price" =>$precio]), 201);
            // Return a response with a product json
            // representation and a 201 status code  
            return $product;

        } 
        catch(Exception $exception) {
            $type = explode("\\", get_class($exception));
            $type = $type[count($type)-1];
            return response()->json([
                "errors"=> ["code"=> "ERROR-1",
                "title"=>  "Unprocessable Entity",
                "type" => $type
                ]
            ]  , 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try 
        {
            return new ProductResource(Product::findOrFail($id), 200);
        } 
        catch (ModelNotFoundException $exception) 
        {
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
    public function edit($id)
    {
        //
        //$producto = Product::findOrFail($id);
        //return view('editProd', compact('producto'));
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
        try 
        {
            $update = Product::findOrFail($id);
            $nombre = $request->data["attributes"]["name"];
            $precio = $request->data["attributes"]["price"];
            if(!is_numeric($precio) || ($precio <= 0)){
                $error = 'Price error';
                throw new Exception($error);
            }

            $update->name = $nombre;
            $update->price = $precio;
            $update->save();
            return new ProductResource(Product::findOrFail($id), 200);
        } 
        catch(Exception $exception)
        {
            $type = explode("\\", get_class($exception));
            $type = $type[count($type)-1];
            return response()->json([
                "errors"=> ["code"=> "ERROR-1",
                "title"=>  "Unprocessable Entity",
                "type" => $type
                ]]  , 422);
        } 
        catch (ModelNotFoundException $exception) 
        {
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
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try 
        {
            $request = Product::findOrFail($id);
            $delete = Product::destroy($id);
            return response()->json("Element deleted", 200);
        } 
        catch (Exception $exception) 
        {
            $type = explode("\\", get_class($exception));
            $type = $type[count($type)-1];
            return response()->json([
                "errors"=> ["code"=> "ERROR-2",
                "title"=>  "Not Found",
                "type" => $type
                ]]  , 404);
        }
    }
}
