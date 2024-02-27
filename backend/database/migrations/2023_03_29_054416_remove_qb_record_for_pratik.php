<?php

use Illuminate\Database\Migrations\Migration;

class RemoveQbRecordForPratik extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(env('QUICKBOOKS_API_URL') === 'Development'){
            \DB::table('quickbooks_tokens')->where('user_id', 506)->delete();
        }
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
