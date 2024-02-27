<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToInvoiceServicesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('invoice_services', function(Blueprint $table)
		{
			$table->foreign('invoice_id')->references('id')->on('invoices')->onUpdate('NO ACTION')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('invoice_services', function(Blueprint $table)
		{
			$table->dropForeign('invoice_services_invoice_id_foreign');
		});
	}

}
