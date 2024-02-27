<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToInvoiceServicesTable extends Migration
{

    public function up()
    {
        Schema::table('invoice_services', function (Blueprint $table) {
            $table->string('qbo_service_name')->nullable()->after('rate');
        });
    }


    public function down()
    {
        Schema::table('invoice_services', function (Blueprint $table) {
            $table->dropColumn('qbo_service_name');
        });
    }
}
