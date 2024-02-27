<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToItemSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_suppliers', function (Blueprint $table) {
            $table->foreign('item_id')->references('id')->on('items')->onUpdate('NO ACTION')->onDelete('CASCADE');
			$table->foreign('supplier_id')->references('id')->on('suppliers')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('item_suppliers', function (Blueprint $table) {
            $table->dropForeign('item_suppliers_item_id_foreign');
			$table->dropForeign('item_suppliers_supplier_id_foreign');
        });
    }
}
