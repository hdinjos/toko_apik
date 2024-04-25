<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableProductInvoices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("product_invoices", function (Blueprint $table) {
            $table->bigInteger("unit_price")->after("total_qty");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_invoices', function (Blueprint $table) {
            $table->dropColumn('unit_price');
        });
        //
    }
}
