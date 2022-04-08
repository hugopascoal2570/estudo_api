<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateCategory;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    protected $category, $totalPage = 10;

    public function __construct(Category $model)
    {
        $this->category = $model;
    }


    public function index(Request $request)
    {
        $categories = $this->category->getResults($request->name);

        return response()->json($categories, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUpdateCategory $request)
    {
        $category = $this->category->create($request->all());

        return response()->json($category, 201);
    }

    public function show($id)
    {
        if (!$category = $this->category->find($id))
            return response()->json(['error' => 'Not Found'], 404);

        return response()->json($category);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }


    public function update(StoreUpdateCategory $request, $id)
    {
        if (!$category = $this->category->find($id))
            return response()->json(['error' => 'Not Found'], 404);
        $category->update($request->all());

        return response()->json($category);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!$category = $this->category->find($id))
            return response()->json(['error' => 'Not Found'], 404);

        $category->delete();
        return response()->json(['success' => true], 204);
    }

    public function products($id)
    {
        if (!$category = $this->category->find($id))
            return response()->json(['error' => 'Not Found'], 404);

        $products = $category->products()->paginate($this->totalPage);

        return response()->json([
            'category' => $category,
            'products' => $products
        ]);
    }
}
