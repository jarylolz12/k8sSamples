<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

class InsertIncotermNameToIncotermsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('incoterms')->insert([
            ['name' => 'DDU', 'description' => 'DDU Incoterm, which is short for “delivered duty unpaid,” is an international commerce term (incoterm) which means that the seller will deliver the goods as soon as they are made available at an agreed-upon location in the country to which they are imported.', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'DDP', 'description' => 'Under the Delivered Duty Paid (DDP) Incoterm rules, the seller assumes all responsibilities and costs for delivering the goods to the named place of destination. The seller must pay both export and import formalities, fees, duties and taxes.', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s')],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('incoterms', function (Blueprint $table) {
            //
        });
    }
}
