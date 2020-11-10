<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permission_module', function (Blueprint $table) {
            $table->increments('permission_module_id');
            $table->string('permission_module_name');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('permission_role', function (Blueprint $table) {
            $table->increments('permission_role_id');
            $table->unsignedInteger('business_id');
            $table->unsignedInteger('role_id');
            $table->unsignedInteger('permission_module_id');

            $table->boolean('view')->default(1);
            $table->boolean('add')->default(1);
            $table->boolean('edit')->default(1);
            $table->boolean('delete')->default(1);

            $table->boolean('isAvailable')->default(1);
            $table->boolean('isDeleted')->default(0);

            $table->foreign('business_id')->references('business_id')->on('business')->onDelete('cascade');
            $table->foreign('role_id')->references('role_id')->on('roles')->onDelete('cascade');
            $table->foreign('permission_module_id')->references('permission_module_id')->on('permission_module')->onDelete('cascade');

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
        Schema::dropIfExists('permission_module');
        Schema::dropIfExists('permission_role');
    }
}
