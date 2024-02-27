<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillPaylistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bill_paylists', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('bill_id')->unsigned()->nullable()->index('bill_paylists_bill_id_foreign');
            $table->decimal('amount',9,2)->nullable();
            $table->bigInteger('account_representative_id')->unsigned()->nullable()->index('bill_paylists_account_representative_id_foreign');
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
        Schema::dropIfExists('bill_paylists');
    }
}
