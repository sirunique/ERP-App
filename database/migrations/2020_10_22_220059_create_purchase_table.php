<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase', function (Blueprint $table) {
            $table->increments('purchase_id');
            $table->unsignedInteger('business_id');
            $table->unsignedInteger('supplier_id');
            $table->unsignedInteger('user_id');
            $table->string('purchase_no');
            $table->string('purchase_document');
            $table->string('purchase_note');

            $table->float('purchase_grand_total');
            $table->float('purchase_amount_paid');
            $table->float('purchase_balance');
            $table->string('purchase_status');
            $table->string('purchase_payment_status');

            $table->boolean('isAvailable')->default(1);
            $table->boolean('isDeleted')->default(0);

            $table->foreign('business_id')->references('business_id')->on('business')->onDelete('cascade');
            $table->foreign('supplier_id')->references('supplier_id')->on('supplier')->onDelete('cascade');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('purchase_details', function (Blueprint $table) {
            $table->increments('purchase_details_id');
            $table->unsignedInteger('business_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('purchase_id');
            $table->unsignedInteger('purchase_no');

            $table->unsignedInteger('product_id');
            $table->string('quantity');

            $table->boolean('isAvailable')->default(1);
            $table->boolean('isDeleted')->default(0);

            $table->foreign('business_id')->references('business_id')->on('business')->onDelete('cascade');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('purchase_id')->references('purchase_id')->on('purchase')->onDelete('cascade');
            $table->foreign('product_id')->references('product_id')->on('products')->onDelete('cascade');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('purchase_payment', function (Blueprint $table) {
            $table->increments('purchase_payment_id');
            $table->unsignedInteger('business_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('purchase_id');
            $table->unsignedInteger('purchase_payment_no');

            $table->float('purchase_payment_amount');
            $table->float('purchase_payment_amount_to_pay');
            $table->float('purchase_payment_balance');
            $table->string('purchase_payment_method');
            $table->string('purchase_payment_note');
            $table->string('purchase_payment_mm')->nullable();
            $table->string('purchase_payment_yy')->nullable();
            $table->string('purchase_payment_cvc')->nullable();
            $table->string('purchase_payment_cheque_no')->nullable();

            $table->boolean('isAvailable')->default(1);
            $table->boolean('isDeleted')->default(0);

            $table->foreign('business_id')->references('business_id')->on('business')->onDelete('cascade');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('purchase_id')->references('purchase_id')->on('purchase')->onDelete('cascade');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('purchase_return', function (Blueprint $table) {
            $table->increments('purchase_return_id');
            $table->unsignedInteger('business_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('purchase_id');
            $table->unsignedInteger('purchase_no');

            $table->string('purchase_return_note');

            $table->boolean('isAvailable')->default(1);
            $table->boolean('isDeleted')->default(0);

            $table->foreign('business_id')->references('business_id')->on('business')->onDelete('cascade');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('purchase_id')->references('purchase_id')->on('purchase')->onDelete('cascade');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('purchase_return_details', function (Blueprint $table) {
            $table->increments('purchase_return_details_id');
            $table->unsignedInteger('business_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('purchase_return_id');
            $table->unsignedInteger('purchase_return_no');

            $table->unsignedInteger('product_id');
            $table->string('quantity');

            $table->boolean('isAvailable')->default(1);
            $table->boolean('isDeleted')->default(0);

            $table->foreign('business_id')->references('business_id')->on('business')->onDelete('cascade');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('purchase_return_id')->references('purchase_return_id')->on('purchase_return')->onDelete('cascade');
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
        Schema::dropIfExists('purchase');
        Schema::dropIfExists('purchase_details');
        Schema::dropIfExists('purchase_payment');
        Schema::dropIfExists('purchase_return');
        Schema::dropIfExists('purchase_return_details');
    }
}
