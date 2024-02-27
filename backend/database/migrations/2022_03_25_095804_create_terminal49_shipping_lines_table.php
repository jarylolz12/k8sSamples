<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTerminal49ShippingLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('terminal49_shipping_lines', function (Blueprint $table) {
            $table->id();
            $table->string("t49_uuid")->nullable();
            $table->string("type")->default("shipping_line");
            $table->string("scac")->unique();
            $table->string("name")->nullable();
            $table->string("short_name")->nullable();
            $table->boolean("bill_of_lading_tracking_support")->default(false);
            $table->boolean("booking_number_tracking_support")->default(false);
            $table->boolean("container_number_tracking_support")->default(false);
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
        Schema::dropIfExists('terminal49_shipping_lines');
    }
}
