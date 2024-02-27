<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddManagersListsToShiflOfficesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shifl_offices', function (Blueprint $table) {
            //
            $table->json('managers_lists')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shifl_offices', function (Blueprint $table) {
            //
            $table->dropColumn('managers_lists');
        });
    }
}
