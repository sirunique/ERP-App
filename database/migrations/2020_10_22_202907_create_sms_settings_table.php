<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmsSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_settings', function (Blueprint $table) {
            $table->increments('sms_setting_id');
            $table->unsignedInteger('business_id');
            $table->unsignedInteger('user_id');
            $table->string('sms_twilio_account_sid');
            $table->string('sms_twilio_auth_token');
            $table->string('sms_twilio_number');

            $table->boolean('isDefault')->default(0);
            $table->boolean('isDeleted')->default(0);

            $table->foreign('business_id')->references('business_id')->on('business')->onDelete('cascade');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('sms', function (Blueprint $table) {
            $table->increments('sms_id');
            $table->unsignedInteger('business_id');
            $table->unsignedInteger('sms_no');
            $table->string('sms_number');
            $table->string('messages');

            $table->boolean('isAvailable')->default(1);
            $table->boolean('isActive')->default(1);
            $table->boolean('isDeleted')->default(0);

            $table->foreign('business_id')->references('business_id')->on('business')->onDelete('cascade');

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
        Schema::dropIfExists('sms_settings');
    }
}
