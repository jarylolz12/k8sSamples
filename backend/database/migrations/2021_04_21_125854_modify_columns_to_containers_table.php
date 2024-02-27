<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyColumnsToContainersTable extends Migration
{

    public function up()
    {
        Schema::table('containers', function (Blueprint $table) {
            $table->decimal('cbm',9,2)->nullable()->change();
            $table->decimal('kg',9,2)->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('containers', function (Blueprint $table) {
            $table->integer('cbm')->nullable()->change();
            $table->integer('kg')->nullable()->change();
        });
    }
}
