<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('product_id');
            $table->unsignedInteger('business_id');
            $table->unsignedInteger('user_id');
            $table->string('product_name');
            $table->float('product_cost');
            $table->float('product_price');
            $table->string('product_reorder_level');

            $table->boolean('isAvailable')->default(1);
            $table->boolean('isDeleted')->default(0);

            $table->foreign('business_id')->references('business_id')->on('business')->onDelete('cascade');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
