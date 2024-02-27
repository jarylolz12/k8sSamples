<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCurrencyColumnsToInvoicesTable extends Migration
{

    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->decimal('home_total_amount',9,2)->nullable()->after('total_amount');
            $table->decimal('balance',9,2)->nullable()->after('home_total_amount');
            $table->decimal('home_balance',9,2)->nullable()->after('balance');
            $table->mediumText('currency_ref')->nullable()->after('home_balance');
            $table->mediumText('home_currency_ref')->nullable()->after('currency_ref');
            $table->decimal('exchange_rate',9,4)->nullable()->after('home_currency_ref');
            $table->text('global_tax_calculation')->nullable()->after('qbo_tax_detail');
            $table->integer('sync_token')->default(0)->after('qbo_customer_gstin');
        });
    }

    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('currency_ref');
            $table->dropColumn('home_currency_ref');
            $table->dropColumn('exchange_rate');
            $table->dropColumn('home_total_amount');
            $table->dropColumn('balance');
            $table->dropColumn('home_balance');
            $table->dropColumn('global_tax_calculation');
            $table->dropColumn('sync_token');
        });
    }
}
