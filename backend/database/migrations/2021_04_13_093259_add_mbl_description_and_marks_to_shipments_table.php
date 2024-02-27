<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMblDescriptionAndMarksToShipmentsTable extends Migration
{

    public function up()
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->text('mbl_description')->nullable()->after('mbl_notify_phone_number');
            $table->text('mbl_marks')->nullable()->after('mbl_description');
        });
    }

    public function down()
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->dropColumn('mbl_description');
            $table->dropColumn('mbl_marks');
        });
    }
}
