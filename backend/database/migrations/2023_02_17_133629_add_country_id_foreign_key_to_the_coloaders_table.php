<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCountryIdForeignKeyToTheColoadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coloaders', function (Blueprint $table) {
            //
            $table->foreignId('country_id')->nullable()->constrained('countries');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('coloaders', function (Blueprint $table) {
            //
            $table->dropForeign(['country_id']);
            $table->dropColumn('country_id');
        });
    }
}
