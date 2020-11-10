<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupplierTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier', function (Blueprint $table) {
            $table->increments('supplier_id');
            $table->unsignedInteger('business_id');
            $table->unsignedInteger('user_id');
            $table->string('supplier_company_name');
            $table->string('supplier_vat_no');
            $table->string('supplier_email');
            $table->string('supplier_phone_no');
            $table->string('supplier_address');
            $table->string('supplier_country');
            $table->string('supplier_city');
            $table->string('supplier_state');
            $table->string('supplier_postal_code');

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
        Schema::dropIfExists('supplier');
    }
}
