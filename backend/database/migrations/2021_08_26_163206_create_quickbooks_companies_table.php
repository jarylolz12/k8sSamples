<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuickbooksCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quickbooks_companies', function (Blueprint $table) {
            $table->id();
            $table->string('qb_user_id')->nullable();
            $table->bigInteger('company_realm_id')->unsigned()->default(0);
            $table->string('company_name')->nullable();
            $table->longText('client_id')->nullable();
            $table->longText('client_secret')->nullable();
            $table->longText('webhook_verifier')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quickbooks_companies');
    }
}
