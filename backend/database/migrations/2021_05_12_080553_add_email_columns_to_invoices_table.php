<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEmailColumnsToInvoicesTable extends Migration
{

    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->boolean('is_email_sent')->default(0)->after('qbo_customer_memo');
            $table->integer('email_sent_count')->default(0)->after('is_email_sent');
            $table->dateTime('email_sent_at')->nullable()->after('email_sent_count');
        });
    }

    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('is_email_sent');
            $table->dropColumn('email_sent_count');
            $table->dropColumn('email_sent_at');
        });
    }
}
