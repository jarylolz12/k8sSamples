<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGstinAndRealmidColumsToInvoicesTable extends Migration
{
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->bigInteger('qbo_company_realmid')->nullable()->after('qbo_company');
            $table->string('qbo_customer_gstin')->nullable();
        });
    }

    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('qbo_company_realmid');
            $table->dropColumn('qbo_customer_gstin');
        });
    }
}
