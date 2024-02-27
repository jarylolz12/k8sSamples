<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateShipmentSuppliersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('shipment_suppliers', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->string('po_num')->nullable();
			$table->string('commodity')->nullable();
			$table->date('cargo_ready_date')->nullable();
			$table->string('bl_num')->nullable();
			$table->string('bl_status')->nullable();
			$table->string('ams_num')->nullable();
			$table->string('total_cartons')->nullable();
			$table->string('total_size')->nullable();
			$table->string('total_weight')->nullable();
			$table->bigInteger('shipment_id')->unsigned()->nullable()->index('shipment_suppliers_shipment_id_foreign');
			$table->bigInteger('supplier_id')->unsigned()->nullable()->index('shipment_suppliers_supplier_id_foreign');
			$table->string('supplier_name')->nullable();
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
		Schema::drop('shipment_suppliers');
	}

}
