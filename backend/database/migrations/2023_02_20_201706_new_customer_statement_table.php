<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NewCustomerStatementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('customer_statements');
        
        Schema::create('customer_statements',function(Blueprint $table){
            $table->id();
            $table->string('statement_id',200)->index();
            $table->longText('company')->nullable();
            $table->unsignedBigInteger('company_id')->index();
            $table->longText('customer')->nullable();
            $table->unsignedBigInteger('contact_id')->nullable();
            $table->string('currency',30)->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->longText('json')->nullable();
            $table->string('status',25)->index()->default('draft');
            $table->string('type',55)->nullable()->index();
            $table->double('amount', 15, 4)->default(0);
            $table->double('amount_due', 15, 4)->default(0);
            $table->double('opening_balance', 15, 4)->default(0);
            $table->double('total_paid_amount', 15, 4)->default(0);
            $table->double('closing_balance', 15, 4)->default(0);
            $table->unsignedBigInteger('sent_count')->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->tinyInteger('is_deleted')->default(0);
            $table->softDeletes($column = 'deleted_at', $precision = 0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });

        // $keys = collect($q)->map( fn($item) => $item->Key_name )->all();

        // if( in_array('customer_admins_group_id_foreign',$keys) ){
        //     Schema::table('customer_admins', function (Blueprint $table) {
        //         $table->dropForeign('customer_admins_group_id_foreign');
        //     });
        // }
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
