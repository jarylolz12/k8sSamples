<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShipmentNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipment_notes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('subject', '255');
            $table->text('description');
            $table->tinyInteger('privacy')->comment('0 = Public, 1 = Private');
            $table->tinyInteger('uploaded_from')->comment('0 = backend, 1 = frontend');
            $table->unsignedBigInteger('shipment_id')->index();
            $table->foreign('shipment_id')->references('id')->on('shipments')->onDelete('cascade');
            $table->unsignedInteger('created_by')->index();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('shipment_notes');
    }
}
