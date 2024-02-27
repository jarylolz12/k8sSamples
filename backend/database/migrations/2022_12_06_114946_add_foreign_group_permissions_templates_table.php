<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignGroupPermissionsTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('group_permissions_templates', function (Blueprint $table) {
            $table->foreign('group_template_id')->references('id')->on('group_templates')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('module_id')->references('id')->on('modules')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('group_permissions_templates', function (Blueprint $table) {
            $table->dropForeign('group_permissions_templates_group_template_id_foreign');
            $table->dropForeign('group_permissions_templates_group_module_id_foreign');
        });
    }
}
