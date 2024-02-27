<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendors', function (Blueprint $table) {
            //
            $table->string('suffix')->nullable();
            $table->string('title')->nullable();
            $table->string('family_name')->nullable();
            $table->string('tax_identifier')->nullable();
            $table->string('account_number')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('line3')->nullable();
            $table->string('line2')->nullable();
            $table->string('line1')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country_subdivision_code')->nullable();
            $table->string('given_name')->nullable();
            $table->string('print_on_check_name')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendors', function (Blueprint $table) {
            //
            $table->dropColumn('suffix');
            $table->dropColumn('title');
            $table->dropColumn('family_name');
            $table->dropColumn('tax_identifier');
            $table->dropColumn('account_number');
            $table->dropColumn('city');
            $table->dropColumn('country');
            $table->dropColumn('city');
            $table->dropColumn('line3');
            $table->dropColumn('line2');
            $table->dropColumn('line1');
            $table->dropColumn('postal_code');
            $table->dropColumn('country_subdivision_code');
            $table->dropColumn('given_name');
            $table->dropColumn('print_on_check_name');
        });
    }
}
