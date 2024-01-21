<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableProductInvoices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_invoices', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger("product_id");
            $table->foreign("product_id")->references("id")->on("products");

            $table->bigInteger("total_price");
            $table->integer("total_qty");

            $table->unsignedBigInteger("invoice_id");
            $table->foreign("invoice_id")->references("id")->on("invoices");
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_invoices');
    }
}
