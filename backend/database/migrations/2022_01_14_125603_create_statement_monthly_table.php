<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatementMonthlyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statement_monthly', function (Blueprint $table) {
            $table->id();
            $table->string('statement_no');
            $table->string('customer_name');
            $table->string('process_port');
            $table->string('daily_statement_no');
            $table->date('statement_date');
            $table->date('pres_date');
            $table->decimal('amount',9,2);
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('statement_monthly');
    }
}
