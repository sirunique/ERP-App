<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business', function (Blueprint $table) {
            $table->increments('business_id');
            $table->string('business_name');
            $table->string('business_email')->unique();
            $table->string('business_phone');
            $table->string('business_address');
            $table->string('business_country');
            $table->string('business_timezone')->nullable();
            $table->string('business_default_language')->nullable();
            $table->string('business_currency_symbol')->nullable();

            $table->boolean('isActive')->default(1);
            $table->boolean('isDeleted')->default(0);

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
        Schema::dropIfExists('business');
    }
}
