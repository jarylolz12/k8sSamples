<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('module_name', 255)->nullable();
            $table->string('module_description', 255)->nullable();
            $table->string('const_name', 255)->nullable();
            $table->bigInteger('parent_id')->unsigned()->nullable();
            $table->boolean('is_display_view')->default(0);
            $table->boolean('is_display_add')->default(0);
            $table->boolean('is_display_update')->default(0);
            $table->boolean('is_display_delete')->default(0);
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
        Schema::dropIfExists('modules');
    }
}
