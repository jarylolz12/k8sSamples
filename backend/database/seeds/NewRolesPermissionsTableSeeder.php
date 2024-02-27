<?php

use Illuminate\Database\Seeder;

class NewRolesPermissionsTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $createCustomerAdminRole = \Spatie\Permission\Models\Role::create([
          'name' => 'Customer Admin',
          'guard_name' => 'web'
      ]);
      /*
        $createSaleAgentRole = \Spatie\Permission\Models\Role::create([
            'name' => 'Sales Representative',
            'guard_name' => 'web'
        ]);*/

        /*
        if(isset($createSaleAgentRole->id)) {

            $createPermission = \Spatie\Permission\Models\Permission::create([
                'name' => 'can_view_dashboard'
            ]);

            if(isset($createPermission->id)) {
                DB::table('role_has_permissions')->insert([[
                    'role_id' => $createSaleAgentRole->id,
                    'permission_id' => $createPermission->id
                ]]);
            }

        }*/

    }
}
