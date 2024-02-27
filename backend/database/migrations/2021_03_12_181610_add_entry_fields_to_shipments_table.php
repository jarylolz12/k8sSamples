<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEntryFieldsToShipmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->boolean('entry_netchb_submitted')->default(0);
            $table->string('entry_netchb_no')->nullable();
            $table->date('entry_netchb_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->dropColumn('entry_netchb_submitted');
            $table->dropColumn('entry_netchb_no');
            $table->dropColumn('entry_netchb_date');
        });
    }
}
