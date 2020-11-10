<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_types', function (Blueprint $table) {
            $table->increments('sub_type_id');
            $table->string('sub_title');
            $table->string('sub_description');
            $table->float('sub_amount')->nullable();

            $table->boolean('isAvailable')->default(1);
            $table->boolean('isDeleted')->default(0);

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('subscription_details', function (Blueprint $table) {
            $table->increments('sub_details_id');
            $table->unsignedInteger('business_id');
            $table->unsignedInteger('sub_type_id');

            $table->boolean('isActive')->default(1);
            $table->boolean('isDeleted')->default(0);

            $table->foreign('business_id')->references('business_id')->on('business')->onDelete('cascade');
            $table->foreign('sub_type_id')->references('sub_type_id')->on('subscription_types')->onDelete('cascade');

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
        Schema::dropIfExists('subscription');
        Schema::dropIfExists('subscription_details');
    }
}
