<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeOfficeOriginToFromIdNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shipments', function (Blueprint $table) {
            //
            $table->bigInteger("shifl_office_origin_from_id")->nullable()->change();
            $table->bigInteger("shifl_office_origin_to_id")->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shipments', function (Blueprint $table) {
            //
            $table->bigInteger("shifl_office_origin_from_id")->nullable(false)->change();
            $table->bigInteger("shifl_office_origin_to_id")->nullable(false)->change();
        });
    }
}
