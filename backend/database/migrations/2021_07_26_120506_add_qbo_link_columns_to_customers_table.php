<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQboLinkColumnsToCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->longText('qbo_customer')->nullable();
            // $table->text('qbo_link_company')->nullable();
            // $table->bigInteger('qbo_link_company_realmid')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('qbo_customer');
            // $table->dropColumn('qbo_link_company');
            // $table->dropColumn('qbo_link_company_realmid');
        });
    }
}
