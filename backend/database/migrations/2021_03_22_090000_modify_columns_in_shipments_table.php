<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyColumnsInShipmentsTable extends Migration
{

    public function up()
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->mediumText('suppliers_group')->nullable()->change();
            $table->mediumText('schedules_group')->nullable()->change();
            $table->mediumText('containers_group')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->string("suppliers_group", 4000)->nullable()->change();
            $table->string("containers_group", 4000)->nullable()->change();
            $table->string('schedules_group',4000)->change();
        });
    }
}
