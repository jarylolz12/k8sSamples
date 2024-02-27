<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToQuickbooksCompaniesTable extends Migration
{

    public function up()
    {
        Schema::table('quickbooks_companies', function (Blueprint $table) {
            $table->string('country')->nullable()->after('company_name');
            $table->string('currency')->nullable()->after('country');
            $table->string('currency_code')->nullable()->after('currency');
        });
    }


    public function down()
    {
        Schema::table('quickbooks_companies', function (Blueprint $table) {
            $table->dropColumn("country");
            $table->dropColumn("currency");
            $table->dropColumn("currency_code");
        });
    }
}
