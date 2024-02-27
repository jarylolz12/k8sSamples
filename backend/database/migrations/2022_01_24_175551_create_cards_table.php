<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->string('type')->nullable();
            $table->string('source')->nullable();
            $table->integer('expiration_month')->nullable();
            $table->integer('expiration_year')->nullable();
            $table->integer('card_id')->nullable();
            $table->string('number_masked')->nullable();
            $table->string('number_hashed')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('card_card_id')->nullable();
            $table->string('payment_token')->nullable();
            $table->integer('customer_admin_id')->nullable();
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
        Schema::dropIfExists('cards');
    }
}
