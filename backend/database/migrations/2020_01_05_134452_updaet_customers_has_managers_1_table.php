<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdaetCustomersHasManagers1Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('customers_has_managers', function (Blueprint $table) {
           $table->unsignedInteger('user_id')->change();
           $table->unsignedInteger('customer_id')->change();
          // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
          // $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
           //
        });
         
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
