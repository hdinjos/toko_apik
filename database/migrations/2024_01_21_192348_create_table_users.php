<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableUsers extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('users', function (Blueprint $table) {
            
            $table->id();
            $table->string("name",100);
            $table->string("email",50)->unique();
            $table->string("birth",15);
            $table->longText("address");
            $table->string("phone", 15);

            $table->unsignedBigInteger("role_id");
            $table->foreign('role_id')->references('id')->on('roles');
            
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->longText('password');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
