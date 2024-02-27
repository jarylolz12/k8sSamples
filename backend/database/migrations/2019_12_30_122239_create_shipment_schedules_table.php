<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateShipmentSchedulesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('shipment_schedules', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->string('carrier_name')->nullable();
			$table->string('mode')->nullable();
			$table->string('location_from')->nullable();
			$table->string('location_to')->nullable();
			$table->date('etd_date')->nullable();
			$table->date('eta_date')->nullable();
			$table->bigInteger('shipment_id')->unsigned()->nullable()->index('shipment_schedules_shipment_id_foreign');
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
		Schema::drop('shipment_schedules');
	}

}
