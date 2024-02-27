<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddManualPoSoDataToShipmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shipments', function (Blueprint $table) {
            if(!Schema::hasColumn('shipments', 'add_manual_po_so_data')) ;
            {
                $table->text('add_manual_po_so_data')->nullable()->after('lfd_notes');
            }
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
            if(Schema::hasColumn('shipments', 'add_manual_po_so_data')) ;
            {
                $table->dropColumn("add_manual_po_so_data");
            }
        });
    }
}
