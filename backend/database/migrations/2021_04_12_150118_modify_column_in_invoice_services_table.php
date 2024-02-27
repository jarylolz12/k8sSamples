<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyColumnInInvoiceServicesTable extends Migration
{

    public function up()
    {
        Schema::table('invoice_services', function (Blueprint $table) {
            $table->decimal('quantity',9,2)->nullable()->change();
            $table->decimal('rate',9,2)->nullable()->change();
            $table->decimal('amount',9,2)->nullable()->after('rate');
        });
    }

    public function down()
    {
        Schema::table('invoice_services', function (Blueprint $table) {
            $table->integer('quantity')->nullable()->change();
            $table->integer('rate')->nullable()->change();
            $table->dropColumn('amount');
        });
    }
}
