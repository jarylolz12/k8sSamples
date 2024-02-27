<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQboCustomerMemoToInvoicesTable extends Migration
{

    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->longText('qbo_customer_memo')->nullable();
        });
    }

    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('qbo_customer_memo');
        });
    }
}
