<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string("name",100)->nullable(true)->change();
            $table->string("birth",15)->nullable(true)->change();
            $table->longText("address")->nullable(true)->change();
            $table->string("phone", 15)->nullable(true)->change();
            $table->unsignedBigInteger("role_id")->default(2)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
