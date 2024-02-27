<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToBillsTable extends Migration
{
    public function up()
    {
        Schema::table('bills', function (Blueprint $table) {
            $table->string('qbo_term_name')->nullable()->after('qbo_bill_num');
            $table->longText('qbo_memo')->nullable()->after('qbo_mailing_address');
            $table->dropColumn('qbo_line');
        });
    }

    public function down()
    {
        Schema::table('bills', function (Blueprint $table) {
            $table->dropColumn('qbo_term_name');
            $table->dropColumn('qbo_memo');
        });
    }
}
