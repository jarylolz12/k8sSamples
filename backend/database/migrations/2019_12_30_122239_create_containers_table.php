<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateContainersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('containers', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->string('container_num')->nullable();
			$table->string('type')->nullable();
			$table->string('seal_num')->nullable();
			$table->integer('cartons')->nullable();
			$table->integer('cbm')->nullable();
			$table->integer('kg')->nullable();
			$table->bigInteger('shipment_id')->unsigned()->nullable()->index('containers_shipment_id_foreign');
			$table->string('chargeable_weight')->nullable();
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
		Schema::drop('containers');
	}

}
