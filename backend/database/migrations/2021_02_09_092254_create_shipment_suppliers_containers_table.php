<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShipmentSuppliersContainersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipment_suppliers_containers', function (Blueprint $table) {
            $table->id();
            $table->string('unique_id',3000)->nullable();
            $table->integer('container_id')->nullable();
            $table->string('weight')->nullable();
            $table->string('size')->nullable();
            $table->string('cartons')->nullable();
            $table->string('supplier_unique_id',3000)->nullable();
            $table->bigInteger('shipment_suppliers_id')->unsigned()->nullable()->index('shipment_suppliers_containers_shipment_suppliers_id_foreign');
            $table->foreign('shipment_suppliers_id')->references('id')->on('shipment_suppliers')->onDelete('CASCADE');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shipment_suppliers_containers');
    }
}
