<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CustomerStatementEmailRecipient extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_statement_email_recipients',function(Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('statement_id')->nullable();
            $table->unsignedBigInteger('table_id')->nullable();
            $table->string('name',150)->index()->nullable();
            $table->string('email',80)->index()->nullable();
            $table->string('table_name',45)->index()->nullable();
            $table->longText('json')->nullable();
            $table->tinyInteger('is_deleted')->default(0);
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
        Schema::dropIfExists('customer_statement_email_recipients');
    }
}
