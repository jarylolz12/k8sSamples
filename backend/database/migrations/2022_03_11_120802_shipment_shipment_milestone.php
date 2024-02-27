<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ShipmentShipmentMilestone extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    //     Schema::create('shipment_shipment_milestones',function(Blueprint $table){
    //         $table->bigIncrements('id');
    //         $table->foreignId('shipment_id')->constrained('shipments')->onDelete('cascade');
    //         $table->bigInteger('milestone_id')->index();
    //         $table->timestamp('created_at')->useCurrent();
    //         $table->timestamp('updated_at')->default(DB::raw('NULL ON UPDATE CURRENT_TIMESTAMP'))->nullable();
    //     });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('shipment_shipment_milestones', function (Blueprint $table) {
        //     $table->dropForeign('shipment_shipment_milestones_shipment_id_foreign');
        // });

        // Schema::dropIfExists('shipment_shipment_milestones');
    }
}
