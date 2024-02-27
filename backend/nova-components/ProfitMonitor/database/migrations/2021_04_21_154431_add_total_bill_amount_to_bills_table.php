<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTotalBillAmountToBillsTable extends Migration
{

    public function up()
    {
        Schema::table('bills', function (Blueprint $table) {
            $table->decimal('total_amount',9,2)->nullable()->after('qbo_memo');
        });
    }


    public function down()
    {
        Schema::table('bills', function (Blueprint $table) {
            $table->dropColumn('total_amount');
        });
    }
}
