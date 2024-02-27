<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAddressToShiflOfficesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shifl_offices', function (Blueprint $table) {
            if(!Schema::hasColumn('shifl_offices', 'address')) ;
            {
                $table->text('address')->after('name')->nullable();
            }
            if(!Schema::hasColumn('shifl_offices', 'phone_number')) ;
            {
                $table->string('phone_number')->after('address')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shifl_offices', function (Blueprint $table) {
            $table->dropColumn('address');
            $table->dropColumn('phone_number');
        });
    }
}
