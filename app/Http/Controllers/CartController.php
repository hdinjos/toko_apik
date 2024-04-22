<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Casts\Attribute;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $userId = auth()->user()->id;

        $carts = Cart::join("products", "carts.product_id", "=", "products.id")
            ->where("user_id", "=", $userId)
            ->get()
            ->toArray();

        if ($userId == NULL || $carts == NULL) {
            return response()->json([
                "success" => true,
                "data" => [
                    "orders" => [],
                    "totalItems" => 0,
                    "totalOrder" => 0,
                    "totalAmount" => 0
                ]
            ]);
        }

        $tQty = 0;
        $tPrice = 0;
        foreach ($carts as $c) {
            $qty = $c["total_qty"];
            $price = $c["price"];
            $totalPrice = $qty * $price;
            $tPrice += $totalPrice;
            $tQty += $qty;
        }

        $orders = array_map(function ($c) {
            return ([
                "product_id" => $c["product_id"],
                "product_name" => $c["name"],
                "img_url" => asset("/storage/products/" . $c["image"]),
                "qty" => $c["total_qty"],
                "price" => $c["price"],
                "image" => $c["image"]
            ]);
        }, $carts);

        return response()->json([
            "success" => true,
            "data" => [
                "orders" => $orders,
                "totalItems" => $tQty,
                "totalOrder" => count($carts),
                "totalAmount" => $tPrice
            ]
        ]);
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
            'product_id' => 'required|integer',
            'total_qty' => 'required|integer|min:1',
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

        $product = Product::find((int)$request->product_id);
        if ($product == NULL) {
            return response()->json([
                "success" => false,
                "message" => "Product is not found"
            ], 404);
        }
        if ($request->total_qty > $product->qty) {
            return response()->json([
                "success" => false,
                "message" => "Quantity exceeds limit"
            ], 422);
        }

        $userId = auth()->user()->id;
        $row = DB::table("carts")
            ->where("user_id", "=", $userId)
            ->where("product_id", "=", $request->product_id)
            ->get();

        if ($row->isEmpty()) {
            Cart::create(
                array_merge(
                    $validator->validate(),
                    ["user_id" => $userId]
                )
            );

            return $this->index();
        }

        Cart::where("user_id", "=", $userId)
            ->where("product_id", "=", $request->product_id)
            ->update(["total_qty" => $request->total_qty]);

        return $this->index();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
