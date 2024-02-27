<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWaitingListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('waiting_lists', function (Blueprint $table) {
            $table->id();
            $table->string('company_name')->nullable();
            $table->enum('business_type',['Shipper','Carrier','Cargo Terminal','Forwarder/Customs', 'Trucker/Broker', 'Warehouse', 'Other'])->nullable();
            $table->integer('approximate_annual_shipments')->default(0);
            $table->string('phone_number')->nullable();
            $table->string('email')->unique();
            $table->string('contact_person')->nullable();
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
        Schema::dropIfExists('waiting_lists');
    }
}
