<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyColumnsInShipmentSuppliersTable extends Migration
{

    public function up()
    {
        Schema::table('shipment_suppliers', function (Blueprint $table) {
            $table->mediumText('product_description')->nullable()->change();
            $table->mediumText('marks')->nullable()->change();
            $table->text('bol_shipper_address')->nullable()->change();
            $table->string('bol_shipper_phone_number')->nullable()->change();
            $table->text('bol_consignee_address')->nullable()->change();
            $table->string('bol_consignee_phone_number')->nullable()->change();
            $table->text('bol_notify_party')->nullable()->change();
            $table->text('bol_notify_address')->nullable()->change();
            $table->string('bol_notify_phone_number')->nullable()->change();
        });
    }


    public function down()
    {
        Schema::table('shipment_suppliers', function (Blueprint $table) {
            $table->string('product_description')->change()->nullable();
            $table->string('marks',100)->nullable()->change();
            $table->string('bol_shipper_address')->nullable()->change();
            $table->string('bol_shipper_phone_number')->nullable()->change();
            $table->string('bol_consignee_address')->nullable()->change();
            $table->string('bol_consignee_phone_number',100)->nullable()->change();
            $table->string('bol_notify_party')->nullable()->change();
            $table->string('bol_notify_address')->nullable()->change();
            $table->string('bol_notify_phone_number',100)->nullable()->change();
        });
    }
}
