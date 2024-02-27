<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldsToTemporaryQbTestDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('temporary_qb_test_data', function (Blueprint $table) {
            //
            $table->string('signature');
            $table->string('hash_payload');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('temporary_qb_test_data', function (Blueprint $table) {
            //
            $table->dropColumn('signature');
            $table->dropColumn('hash_payload');
        });
    }
}
