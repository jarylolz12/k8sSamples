<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExchangeRateInfoToInvoicesTable extends Migration
{

    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->longText('exchange_rate_info')->nullable()->after('exchange_rate');
        });
    }

    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('exchange_rate_info');
        });
    }
}
