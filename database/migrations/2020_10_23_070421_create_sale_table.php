<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale', function (Blueprint $table) {
            $table->increments('sale_id');
            $table->unsignedInteger('business_id');
            $table->unsignedInteger('customer_id');
            $table->unsignedInteger('user_id');
            $table->string('sale_no');
            $table->string('sale_document');
            $table->string('sale_note');

            $table->float('sale_grand_total');
            $table->float('sale_amount_paid');
            $table->float('sale_balance');
            $table->string('sale_status');
            $table->string('sale_payment_status');

            $table->boolean('isAvailable')->default(1);
            $table->boolean('isDeleted')->default(0);

            $table->foreign('business_id')->references('business_id')->on('business')->onDelete('cascade');
            $table->foreign('customer_id')->references('customer_id')->on('customer')->onDelete('cascade');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('sale_details', function (Blueprint $table) {
            $table->increments('sale_details_id');
            $table->unsignedInteger('business_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('sale_id');
            $table->unsignedInteger('sale_no');

            $table->unsignedInteger('product_id');
            $table->string('quantity');

            $table->boolean('isActive')->default(1);
            $table->boolean('isDeleted')->default(0);

            $table->foreign('business_id')->references('business_id')->on('business')->onDelete('cascade');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('sale_id')->references('sale_id')->on('sale')->onDelete('cascade');
            $table->foreign('product_id')->references('product_id')->on('products')->onDelete('cascade');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('sale_payment', function (Blueprint $table) {
            $table->increments('sale_payment_id');
            $table->unsignedInteger('business_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('sale_id');
            $table->unsignedInteger('sale_payment_no');

            $table->float('sale_payment_amount');
            $table->float('sale_payment_amount_to_pay');
            $table->float('sale_payment_balance');
            $table->string('sale_payment_method');
            $table->string('sale_payment_note');
            $table->string('sale_payment_mm')->nullable();
            $table->string('sale_payment_yy')->nullable();
            $table->string('sale_payment_cvc')->nullable();
            $table->string('sale_payment_cheque_no')->nullable();

            $table->boolean('isAvailable')->default(1);
            $table->boolean('isDeleted')->default(0);

            $table->foreign('business_id')->references('business_id')->on('business')->onDelete('cascade');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('sale_id')->references('sale_id')->on('sale')->onDelete('cascade');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('sale_return', function (Blueprint $table) {
            $table->increments('sale_return_id');
            $table->unsignedInteger('business_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('sale_id');
            $table->unsignedInteger('sale_no');

            $table->string('sale_return_note');

            $table->boolean('isAvailable')->default(1);
            $table->boolean('isDeleted')->default(0);

            $table->foreign('business_id')->references('business_id')->on('business')->onDelete('cascade');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('sale_id')->references('sale_id')->on('sale')->onDelete('cascade');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('sale_return_details', function (Blueprint $table) {
            $table->increments('sale_return_details_id');
            $table->unsignedInteger('business_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('sale_return_id');
            $table->unsignedInteger('sale_return_no');

            $table->unsignedInteger('product_id');
            $table->string('quantity');

            $table->boolean('isAvailable')->default(1);
            $table->boolean('isDeleted')->default(0);

            $table->foreign('business_id')->references('business_id')->on('business')->onDelete('cascade');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('sale_return_id')->references('sale_return_id')->on('sale_return')->onDelete('cascade');
            $table->foreign('product_id')->references('product_id')->on('products')->onDelete('cascade');

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
        Schema::dropIfExists('sale');
        Schema::dropIfExists('sale_details');
        Schema::dropIfExists('sale_payment');
        Schema::dropIfExists('sale_return');
        Schema::dropIfExists('sale_return_details');
    }
}
