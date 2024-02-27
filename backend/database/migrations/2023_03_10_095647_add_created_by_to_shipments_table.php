<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCreatedByToShipmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shipments', function (Blueprint $table) {
            if(!Schema::hasColumn('shipments', 'created_by'))
            {
                $table->bigInteger('created_by')->unsigned()->nullable()->after('is_mt_past_lfd_not_released');
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
            if(Schema::hasColumn('shipments', 'created_by'))
            {
                $table->dropColumn('created_by');
            }
        });
    }
}
