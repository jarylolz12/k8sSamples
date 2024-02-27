<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_permissions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('group_id')->unsigned()->nullable();
            $table->integer('user_id')->unsigned()->nullable();
            $table->bigInteger('module_id')->unsigned()->nullable();
            $table->boolean('is_view')->default(0);
            $table->boolean('is_add')->default(0);
            $table->boolean('is_update')->default(0);
            $table->boolean('is_delete')->default(0);
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->default(DB::raw('NULL ON UPDATE CURRENT_TIMESTAMP'))->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group_permissions');
    }
}
