<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQboTaxCodeToInvoiceServicesTable extends Migration
{
    public function up()
    {
        Schema::table('invoice_services', function (Blueprint $table) {
            $table->longtext('qbo_tax_code')->nullable()->after('qbo_service_name');
        });
    }

 
    public function down()
    {
        Schema::table('invoice_services', function (Blueprint $table) {
            $table->dropColumn('qbo_tax_code');
        });
    }
}
