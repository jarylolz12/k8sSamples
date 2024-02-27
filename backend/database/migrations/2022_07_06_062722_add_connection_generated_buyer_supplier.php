<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddConnectionGeneratedBuyerSupplier extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->tinyInteger('connection_generated')->nullable();
        });

        Schema::table('buyer', function (Blueprint $table) {
            $table->tinyInteger('connection_generated')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropColumn('connection_generated');
        });

        Schema::table('buyer', function (Blueprint $table) {
            $table->dropColumn('connection_generated');
        });
    }
}
