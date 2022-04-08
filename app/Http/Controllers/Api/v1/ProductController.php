<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private $product;
    private $totalPage = 10;

    public function __construct(Product $model)
    {
        $this->product = $model;
    }
    public function index(Request $request)
    {
        $products = $this->product->getResults($request->all(), $this->totalPage);

        return response()->json($products, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUpdateProduct $request)
    {
        $data = $request->all();

        if ($request->image) {
            $data['image'] = $request->image->store('products');
        }
        $product = $this->product->create($data);


        return response()->json($product, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!$product = $this->product->with('category')->find($id))
            return response()->json(['error' => 'Not Found'], 404);

        return response()->json($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreUpdateProduct $request, $id)
    {
        if (!$product = $this->product->find($id))
            return response()->json(['error' => 'Not Found'], 404);

        $data = $request->all();

        if ($request->image) {
            if (Storage::exists($product->image)) {
                Storage::delete($product->image);
            }
            $data['image'] = $request->image->store('products');
        }

        $product->update($data);

        return response()->json($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!$product = $this->product->find($id))
            return response()->json(['error' => 'Not Found'], 404);

        if ($product->image) {
            if (Storage::exists($product->image)) {
                Storage::delete($product->image);
            }
        }
        $product->delete();
        return response()->json(['success' => true], 204);
    }
}
