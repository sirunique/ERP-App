<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMailSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mail_settings', function (Blueprint $table) {
            $table->increments('mail_setting_id');
            $table->unsignedInteger('business_id');
            $table->unsignedInteger('user_id');
            $table->string('mail_host');
            $table->string('mail_port');
            $table->string('mail_address');
            $table->string('mail_password');
            $table->string('mail_from_name');
            $table->string('mail_encryption');

            $table->boolean('isDefault')->default(0);
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
        Schema::dropIfExists('mail_settings');
    }
}
