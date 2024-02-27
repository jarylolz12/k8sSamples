<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateShipmentDeliveryInfoTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('shipment_delivery_info', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->boolean('shifl_customs')->nullable();
			$table->boolean('shifl_trucking')->nullable();
			$table->string('delivery_address')->nullable();
			$table->string('broker')->nullable();
			$table->string('trucker')->nullable();
			$table->bigInteger('shipment_id')->unsigned()->nullable()->index('shipment_delivery_info_shipment_id_foreign');
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
		Schema::drop('shipment_delivery_info');
	}

}
