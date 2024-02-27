<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateImportNamesTable extends Migration
{
    public function dropColumnIfExists($tbl,$column)
    {
        if (Schema::hasColumn($tbl, $column)) {
            Schema::table($tbl, function (Blueprint $table) use ($column) {
                $table->dropColumn($column);
            });
        }
    }
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('import_names', function (Blueprint $table) {
            $this->dropColumnIfExists('import_names','image');
            $this->dropColumnIfExists('import_names','country');
            $this->dropColumnIfExists('import_names','state');
            $this->dropColumnIfExists('import_names','city');
            $this->dropColumnIfExists('import_names','zipcode');
            $this->dropColumnIfExists('import_names','email');


            $table->string('image')->nullable();
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('zipcode')->nullable();
            $table->string('email')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('import_names', function (Blueprint $table) {
            $table->dropColumn('image');
            $table->dropColumn('country');
            $table->dropColumn('state');
            $table->dropColumn('city');
            $table->dropColumn('zipcode');
            $table->dropColumn('email');
        });
    }
}
