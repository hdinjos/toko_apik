<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\ProductResource;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $page = $request->get("page");
        $limit = $request->get("limit");

        $skip = ($limit * $page) - $limit;
        $products = Product::skip($skip)->take($limit)->get();
        $total = Product::count();
        return response()->json(
            [
                "success" => true,
                "data" => $products,
                "page" => (int)$page,
                "total_data" => $total,
                "total_page" => ceil($total / $limit),
            ]
        );
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'image' => 'required|image|mimes:png,jpg,jpeg,svg|max:2048',
            'price' => 'required|integer',
            'qty' => 'required|integer',
            "description" => 'required|string',
            'category_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    "success" => false,
                    "message" => $validator->errors()
                ],
                422
            );
        }

        $img = $request->file("image");
        $imgName = $img->hashName();
        $img->storeAs('public/products', $imgName);

        $product = Product::create(
            array_merge($validator->validate(), ["image" => $imgName])
        );
        return new ProductResource(true, "data created successfull", $product);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($productId)
    {
        if (!empty($productId)) {
            $product = Product::find((int)$productId);
            if ($product) {
                return response()->json(
                    [
                        "success" => true,
                        "data" => $product
                    ]
                );
            } else {
                return response()->json(
                    [
                        "success" => false,
                        "message" => "product not found"
                    ],
                    404
                );
            }
        } else {
            return response()->json(
                [
                    "success" => false,
                    "message" => "product not found"
                ],
                404
            );
        }
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $productId)
    {

        if (empty($productId)) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "product not found"
                ],
                404
            );
        }

        $product = Product::find((int)$productId);
        if ($product == NULL) {
            return response()->json([
                "success" => false,
                "message" => "product not found"
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'image' => 'image|mimes:png,jpg,jpeg,svg|max:2048',
            'price' => 'required|integer',
            'qty' => 'required|integer',
            "description" => 'required|string',
            'category_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    "success" => false,
                    "message" => $validator->errors()
                ],
                422
            );
        }
        if ($request->hasFile("image")) {
            $img = $request->file("image");
            $imgName = $img->hashName();

            if (Storage::exists($product->image_location)) {
                Storage::delete($product->image_location);
                $img->storeAs("public/products", $imgName);
            } else {
                $img->storeAs("public/products", $imgName);
            }
            $product->update(array_merge($validator->validate(), ["image" => $imgName]));

            return new ProductResource(true, "data updated successfull", "");
        } else {
            $product->update(array_diff($validator->validate(), ["image"]));
            return new ProductResource(true, "data updated successfull", "");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($productId)
    {
        if (!empty($productId)) {
            $product = Product::find((int)$productId);
            if ($product) {
                $product->delete();
            } else {
                return response()->json(
                    [
                        "success" => false,
                        "message" => "product not found"
                    ],
                    404
                );
            }
            if (Storage::exists($product->image_location)) {
                Storage::delete($product->image_location);
            } else {
                return response()->json(
                    [
                        "success" => false,
                        "message" => "image not found"
                    ],
                    404
                );
            }
            return new ProductResource(true, "data delete successfull", "");
        } else {
            return response()->json(
                [
                    "success" => false,
                    "message" => "product not found"
                ],
                404
            );
        }
    }
}
