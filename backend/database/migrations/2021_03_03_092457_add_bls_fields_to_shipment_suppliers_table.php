<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBlsFieldsToShipmentSuppliersTable extends Migration
{
    public function up()
    {
        Schema::table('shipment_suppliers', function (Blueprint $table) {
            $table->string('marks',100)->nullable();
            $table->string('bol_shipper_address')->nullable();
            $table->string('bol_shipper_phone_number',100)->nullable();
            $table->string('bol_consignee_address')->nullable();
            $table->string('bol_consignee_phone_number',100)->nullable();
            $table->string('bol_notify_party')->nullable();
            $table->string('bol_notify_address')->nullable();
            $table->string('bol_notify_phone_number',100)->nullable();
        });
    }

    public function down()
    {
        Schema::table('shipment_suppliers', function (Blueprint $table) {
            $table->dropColumn('marks');
            $table->dropColumn('bol_shipper_address');
            $table->dropColumn('bol_shipper_phone_number');
            $table->dropColumn('bol_consignee_address');
            $table->dropColumn('bol_consignee_phone_number');
            $table->dropColumn('bol_notify_party');
            $table->dropColumn('bol_notify_address');
            $table->dropColumn('bol_notify_phone_number');
        });
    }
}
