<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToInvoicesTable extends Migration
{

    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->integer('qbo_term_id')->nullable();
            $table->string('qbo_term_name')->nullable()->after('qbo_term_id');
            $table->integer('qbo_term_days')->nullable()->after('qbo_term_name');
            $table->decimal('total_amount',9,2)->nullable()->after('shipment_id');
        });
    }

    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('qbo_term_id');
            $table->dropColumn('qbo_term_name');
            $table->dropColumn('qbo_term_days');
            $table->dropColumn('total_amount');
        });
    }
}
