<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        //
        $userId = auth()->user()->id;
        Cart::create([
            "product_id" => 6,
            "user_id" => $userId,
            "total_qty" => 1
        ]);
        return response()->json([
            "success" => true,
        ]);
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
