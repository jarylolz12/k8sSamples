<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddManagerGroupTemplateAndPermissionsData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $id = \DB::table('group_templates')->insertGetId(
            ['name' => 'Manager', 'description' => 'Manage operation but not edit']
        );
        $modules = \DB::table('modules')->get();
        foreach ($modules as $module) {
            $idSub = \DB::table('group_permissions_templates')->insertGetId(
                ['group_template_id' => $id, 'module_id' => $module->id, 'is_view' => 1, 'is_add' => 1, 'is_update' => 0, 'is_delete' => 1]
            );
        }
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
