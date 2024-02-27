<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyToGroupPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('group_permissions', function (Blueprint $table) {
            $table->foreign('group_id')->references('id')->on('groups')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('module_id')->references('id')->on('modules')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('group_permissions', function (Blueprint $table) {
            $table->dropForeign('group_permissions_group_id_foreign');
            $table->dropForeign('group_permissions_module_id_foreign');
            $table->dropForeign('group_permissions_user_id_foreign');
        });
    }
}
