<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEtdEtaCustomNotesFieldsToShipmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->date('etd')->nullable()->after('shifl_ref');
            $table->date('eta')->nullable()->after('etd');
            $table->longText('custom_notes')->nullable()->after('eta');
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
            $table->dropColumn('etd');
            $table->dropColumn('eta');
            $table->dropColumn('custom_notes');
        });
    }
}
