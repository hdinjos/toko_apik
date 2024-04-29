<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Invoice;
use App\Models\ProductInvoice;
use App\Models\Product;
use Illuminate\Support\Carbon;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            "message" => "oke"
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
    static function invGen($checkNum)
    {
        if ($checkNum == NULL) {
            return "INV1/" . date('d/m/Y');
        } else {
            $numInv = $checkNum->invoice_number;
            $takeNum1 = explode("/", $numInv);
            $takeNum2 = explode("INV", $takeNum1[0])[1];
            return "INV" . $takeNum2 + 1 . "/" . date("d/m/Y");
        }
    }

    public function store()
    {
        //transaction no implements
        $userId = auth()->user()->id;
        $carts = Cart::join("products", "carts.product_id", "=", "products.id")
            ->where("user_id", "=", $userId)->get();
        if ($carts->isEmpty()) {
            return response()->json([
                "success" => false,
                "message" => "Not Found"
            ], 404);
        }

        $checkNum = Invoice::latest()->first();
        $invNum = $this->invGen($checkNum);
        $buyingDate = Carbon::now();

        $invId = Invoice::create([
            'invoice_number' => $invNum,
            'buying_date' => $buyingDate,
            'user_id' => $userId,
        ])->id;


        foreach ($carts as $c) {
            ProductInvoice::create([
                'product_id' => $c->product_id,
                'total_price' => $c->price * $c->total_qty,
                'total_qty' => $c->total_qty,
                'invoice_id' => $invId,
                'unit_price' => $c->price
            ]);
            Product::where("id", "=", $c->product_id)
                ->update(["qty" => $c->total_qty]);
        }

        Cart::where("user_id", "=", $userId)->delete();

        return response()->json([
            "success" => true,
            "message" => "Checkout successfull"
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
    public function update()
    {
        //
        $userId = auth()->user()->id;
        $query = Invoice::where("user_id", "=", $userId);
        $inv = $query->get();
        if ($inv->isEmpty()) {
            return response()->json([
                "success" => false,
                "message" => "Not Found"
            ], 404);
        }

        $query->update([
            "status" => "PAID",
            "paying_date" => Carbon::now()
        ]);

        return response()->json([
            "success" => true,
            "message" => "Items is paid"
        ]);
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
