<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQboTaxcodeToBillItemsTable extends Migration
{

    public function up()
    {
        Schema::table('bill_items', function (Blueprint $table) {
            $table->longtext('qbo_tax_code')->nullable()->after('qbo_amount');
        });
    }

    public function down()
    {
        Schema::table('bill_items', function (Blueprint $table) {
            $table->dropColumn('qbo_tax_code');
        });
    }
}
