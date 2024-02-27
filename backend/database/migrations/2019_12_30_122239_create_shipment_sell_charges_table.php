<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateShipmentSellChargesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('shipment_sell_charges', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->string('charge_type')->nullable();
			$table->integer('amount')->nullable();
			$table->string('per')->nullable();
			$table->bigInteger('shipment_id')->unsigned()->nullable()->index('shipment_sell_charges_shipment_id_foreign');
			$table->integer('amount_multiplier')->nullable();
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
		Schema::drop('shipment_sell_charges');
	}

}
