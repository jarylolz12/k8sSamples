<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToShipmentSchedulesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('shipment_schedules', function(Blueprint $table)
		{
			$table->foreign('shipment_id')->references('id')->on('shipments')->onUpdate('NO ACTION')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('shipment_schedules', function(Blueprint $table)
		{
			$table->dropForeign('shipment_schedules_shipment_id_foreign');
		});
	}

}
