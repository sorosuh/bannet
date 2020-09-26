<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Product extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('designed');
            $table->string('brand');
            $table->string('country');
            $table->integer('width');
            $table->integer('diameter');
            $table->integer('height');
            $table->integer('tire_height');
            $table->string('color');
            $table->integer('weight');
            $table->integer('speed');
            $table->tinyInteger('front_using');
            $table->tinyInteger('back_using');
            $table->tinyInteger('tubeless');
            $table->tinyInteger('type')->default(1);
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
        Schema::dropIfExists('product');
    }
}
