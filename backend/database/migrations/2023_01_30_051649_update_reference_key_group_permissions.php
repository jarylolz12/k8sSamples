<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateReferenceKeyGroupPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('modules', function (Blueprint $table) {
            $table->dropForeign('modules_parent_id_foreign');
        });

        Schema::table('modules', function (Blueprint $table) {
            $table->foreign('parent_id')->references('id')->on('modules')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });

        Schema::table('groups', function (Blueprint $table) {
            $table->dropForeign('groups_company_id_foreign');
        });

        Schema::table('groups', function (Blueprint $table) {
            $table->foreign('company_id')->references('id')->on('customers')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });

        Schema::table('group_permissions', function (Blueprint $table) {
            $table->dropForeign('group_permissions_group_id_foreign');
            $table->dropForeign('group_permissions_module_id_foreign');
            $table->dropForeign('group_permissions_user_id_foreign');
        });
        Schema::table('group_permissions', function (Blueprint $table) {
            $table->foreign('group_id')->references('id')->on('groups')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('module_id')->references('id')->on('modules')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
        Schema::table('customer_admins', function (Blueprint $table) {
            $table->dropForeign('customer_admins_group_id_foreign');
        });
        Schema::table('customer_admins', function (Blueprint $table) {
            $table->foreign('group_id')->references('id')->on('groups')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
        Schema::table('group_permissions_templates', function (Blueprint $table) {
            $table->dropForeign('group_permissions_templates_module_id_foreign');
        });
        Schema::table('group_permissions_templates', function (Blueprint $table) {
            $table->foreign('module_id')->references('id')->on('modules')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
