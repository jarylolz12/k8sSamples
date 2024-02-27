<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCustomerIdToImportNamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('import_names', function (Blueprint $table) {
            $table->bigInteger('customer_id')
                ->unsigned()
                ->index('import_names_customer_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('import_names', function (Blueprint $table) {
            $table->dropColumn('customer_id');
        });
    }
}
