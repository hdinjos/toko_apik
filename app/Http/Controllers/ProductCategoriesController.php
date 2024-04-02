<?php

namespace App\Http\Controllers;

use App\Models\ProductCategories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class ProductCategoriesController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $productCat = ProductCategories::all();
    return response()->json([
      "success" => "true",
      "data" => $productCat
    ], 200);
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
    var_dump($request->all());

    $validator = Validator::make($request->all(), [
      "name" => "required|string|between:2,50"
    ]);
    if ($validator->fails()) {
      return response()->json([
        "success" => false,
        "message" => $validator->errors()
      ], 422);
    }

    ProductCategories::create($validator->validate());

    return response()->json([
      "success" => true,
      "message" => "create product category success"
    ], 201);
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\ProductCategories  $productCategories
   * @return \Illuminate\Http\Response
   */
  public function show($productCategoryId)

  {
    if (empty($productCategoryId)) {
      return response()->json([
        "success" => false,
        "message" => "product category not found"
      ], 404);
    }

    $productCat = ProductCategories::find((int)$productCategoryId);
    if ($productCat == NULL) {
      return response()->json([
        "success" => false,
        "message" => "product category not found"
      ], 404);
    } else {
      return response()->json([
        "message" => true,
        "data" => $productCat
      ], 200);
    }

    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Models\ProductCategories  $productCategories
   * @return \Illuminate\Http\Response
   */
  public function edit(ProductCategories $productCategories)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\ProductCategories  $productCategories
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $productCategoryId)
  {
    //
    if (empty($productCategoryId)) {
      return response()->json([
        "success" => false,
        "message" => "product category not found"
      ], 404);
    }

    $validator = Validator::make($request->all(), [
      "name" => "required|string|between:2,50"
    ]);

    if ($validator->fails()) {
      return response()->json([
        "success" => false,
        "message" => $validator->errors()
      ], 422);
    }

    $productCat = ProductCategories::find((int)$productCategoryId);
    if ($productCat == NULL) {
      return response()->json([
        "success" => false,
        "message" => "product category not found"
      ], 404);
    } else {
      $productCat->update($validator->validate());
      return response()->json([
        "success" => false,
        "message" => "product category update success"
      ], 200);
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\ProductCategories  $productCategories
   * @return \Illuminate\Http\Response
   */
  public function destroy($productCategoryId)
  {
    //
    if (empty($productCategoryId)) {
      return response()->json([
        "success" => false,
        "message" => "product category not found"
      ], 404);
    }

    $productCat = ProductCategories::find((int)$productCategoryId);
    if ($productCat == NULL) {
      return response()->json([
        "success" => false,
        "message" => "product category not found"
      ], 404);
    } else {
      $productCat->delete();
      return response()->json([
        "success" => true,
        "message" => "delete product category success"
      ], 200);
    }
  }
}
