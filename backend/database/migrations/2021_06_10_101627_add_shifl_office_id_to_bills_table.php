<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShiflOfficeIdToBillsTable extends Migration
{
    public function up()
    {
        Schema::table('bills', function (Blueprint $table) {
            $table->bigInteger('shifl_office_from_id')->unsigned()->after('shipment_id');
            $table->bigInteger('shifl_office_to_id')->unsigned()->after('shifl_office_from_id');
            $table->string('qbo_company')->nullable()->after('qbo_country');
        });
    }

    public function down()
    {
        Schema::table('bills', function (Blueprint $table) {
            $table->dropColumn('shifl_office_from_id');
            $table->dropColumn('shifl_office_to_id');
            $table->dropColumn('qbo_company');
        });
    }
}
