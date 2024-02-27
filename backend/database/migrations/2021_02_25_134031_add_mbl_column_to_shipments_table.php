<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMblColumnToShipmentsTable extends Migration
{

    public function up()
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->string('mbl_shipper',100)->nullable();
            $table->string('mbl_shipper_address')->nullable();
            $table->string('mbl_shipper_phone_number',100)->nullable();
            $table->string('mbl_consignee')->nullable();
            $table->string('mbl_consignee_address')->nullable();
            $table->string('mbl_consignee_phone_number',100)->nullable();
            $table->string('mbl_notify',100)->nullable();
            $table->string('mbl_notify_address')->nullable();
            $table->string('mbl_notify_phone_number',100)->nullable();
        });
    }

    public function down()
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->dropColumn('mbl_shipper');
            $table->dropColumn('mbl_shipper_address');
            $table->dropColumn('mbl_shipper_phone_number');
            $table->dropColumn('mbl_consignee');
            $table->dropColumn('mbl_consignee_address');
            $table->dropColumn('mbl_consignee_phone_number');
            $table->dropColumn('mbl_notify');
            $table->dropColumn('mbl_notify_address');
            $table->dropColumn('mbl_notify_phone_number');
        });
    }
}
