<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance', function (Blueprint $table) {
            $table->increments('attendance_id');
            $table->unsignedInteger('business_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('employee_id');

            $table->string('attendance_check_in');
            $table->string('attendance_check_out');
            $table->string('attendance_status');
            $table->string('attendance_date');
            $table->string('attendance_note');

            $table->boolean('isDeleted')->default(0);

            $table->foreign('business_id')->references('business_id')->on('business')->onDelete('cascade');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('employee_id')->references('employee_id')->on('employee')->onDelete('cascade');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('attendance_settings', function (Blueprint $table) {
            $table->increments('attendance_settings_id');
            $table->unsignedInteger('business_id');
            $table->unsignedInteger('user_id');

            $table->string('attendance_settings_check_in');
            $table->string('attendance_settings_check_out');

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
        Schema::dropIfExists('attendance_settings');
    }
}
