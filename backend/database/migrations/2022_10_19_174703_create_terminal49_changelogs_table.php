<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTerminal49ChangelogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('terminal49_changelogs', function (Blueprint $table) {
            $table->id();
            $table->string('mbl_num');
            $table->string('container_num');
            $table->enum('field_name',['available_for_pickup','pickup_lfd','holds_at_pod_terminal','fees_at_pod_terminal']);
            $table->boolean('boolval')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('terminal49_changelogs');
    }
}
