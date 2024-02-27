<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsForGstToInvoicesTable extends Migration
{
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('qbo_country')->nullable()->after('qbo_customer_memo');
            $table->longText('qbo_tax_detail')->nullable()->after('qbo_country');
            $table->decimal('total_tax',9,2)->nullable()->after('shipment_id');
        });
    }


    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('qbo_country');
            $table->dropColumn('qbo_tax_detail');
            $table->dropColumn('total_tax');
        });
    }
}
