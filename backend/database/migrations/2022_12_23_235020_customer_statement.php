<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CustomerStatement extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_statements',function(Blueprint $table){
            $table->id();
            $table->string('statement_id',200)->index();
            $table->unsignedBigInteger('company_id')->index();
            $table->string('currency',30)->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->longText('json')->nullable();
            $table->string('status',25)->index()->default('draft');
            $table->tinyInteger('is_deleted')->default(0);
            $table->string('type',55)->nullable()->index();
            $table->double('amount', 15, 4)->default(0);
            $table->double('amount_due', 15, 4)->default(0);
            $table->double('opening_balance', 15, 4)->default(0);
            $table->double('total_paid_amount', 15, 4)->default(0);
            $table->double('closing_balance', 15, 4)->default(0);
            $table->unsignedBigInteger('sent_count')->default(0);
            $table->softDeletes($column = 'deleted_at', $precision = 0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_statements');
    }
}
