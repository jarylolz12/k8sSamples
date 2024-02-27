<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateShipmentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('shipments', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->string('po_num')->nullable();
			$table->string('mbl_num')->nullable();
			$table->string('term')->nullable();
			$table->bigInteger('customer_id')->unsigned()->nullable()->index('shipments_customer_id_foreign');
			$table->string('shipment_status')->nullable();
			$table->string('shifl_ref')->nullable();
			$table->integer('total_cbm')->nullable();
			$table->integer('total_kg')->nullable();
			$table->integer('total_cartons')->nullable();
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
		Schema::drop('shipments');
	}

}
