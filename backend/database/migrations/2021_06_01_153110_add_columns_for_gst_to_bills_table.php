<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsForGstToBillsTable extends Migration
{

    public function up()
    {
        Schema::table('bills', function (Blueprint $table) {
            $table->string('qbo_country')->nullable()->after('qbo_memo');
            $table->longText('qbo_tax_detail')->nullable()->after('qbo_country');
            $table->decimal('total_tax',9,2)->nullable()->after('qbo_tax_detail');
        });
    }

    public function down()
    {
        Schema::table('bills', function (Blueprint $table) {
            $table->dropColumn('qbo_country');
            $table->dropColumn('qbo_tax_detail');
            $table->dropColumn('total_tax');
        });
    }
}
