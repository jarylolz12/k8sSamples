<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentTogglesToInvoicesTable extends Migration
{
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->boolean('allow_credit_card_payment')->default(1)->after('qbo_customer_memo');
            $table->boolean('allow_online_ach_payment')->default(1)->after('allow_credit_card_payment');
        });
    }

    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('allow_credit_card_payment');
            $table->dropColumn('allow_online_ach_payment');
        });
    }
}
