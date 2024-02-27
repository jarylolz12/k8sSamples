<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForgotPasswordFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->string('forgot_password_verification_code',500);
            $table->date('forgot_password_verification_code_created_at')->nullable();
            $table->boolean('is_forgot_password_requested');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->dropString('forgot_password_verification_code');
            $table->dropDate('forgot_password_verification_code_created_at');
            $table->dropBoolean('is_forgot_password_requested');
        });
    }
}
