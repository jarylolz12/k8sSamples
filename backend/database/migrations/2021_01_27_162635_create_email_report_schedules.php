<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailReportSchedules extends Migration
{

    public function up()
    {
        Schema::create('email_report_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_admin_id')->nullable();
            $table->text('frequency')->nullable();
            $table->tinyInteger('day')->nullable();
            $table->time('time')->nullable();
            $table->boolean('active')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('email_report_schedules');
    }
}
