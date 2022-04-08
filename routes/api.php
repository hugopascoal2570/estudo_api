<?php

use App\Http\Controllers\Api\v1\CategoryController;
use App\Http\Controllers\Api\v1\ProductController;
use Illuminate\Support\Facades\Route;


Route::prefix('/v1')->group(function () {

    Route::get('/categories/{id}/products', [CategoryController::class, 'products']);

    Route::apiResource('/products', ProductController::class);
    Route::apiResource('/categories', CategoryController::class);

    //
    //return $request->user();
});
