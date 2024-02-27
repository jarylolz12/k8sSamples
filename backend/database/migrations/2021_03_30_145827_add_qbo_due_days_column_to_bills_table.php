<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQboDueDaysColumnToBillsTable extends Migration
{

    public function up()
    {
        Schema::table('bills', function (Blueprint $table) {
            $table->integer('qbo_term_days')->nullable()->after('qbo_term_name');
        });
    }

    public function down()
    {
        Schema::table('bills', function (Blueprint $table) {
            $table->dropColumn('qbo_term_days');
        });
    }
}
