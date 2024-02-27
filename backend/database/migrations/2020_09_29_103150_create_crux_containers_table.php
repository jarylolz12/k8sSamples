<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCruxContainersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crux_containers', function (Blueprint $table) {
            $table->id();
            $table->string('container_number');
            $table->string('event_type')->nullable();
            $table->json('container')->nullable();
            $table->json('vessel')->nullable();
            $table->json('terminal')->nullable();
            $table->json('events')->nullable();
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
        Schema::dropIfExists('crux_containers');
    }
}
