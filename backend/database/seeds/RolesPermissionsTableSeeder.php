<?php

use Illuminate\Database\Seeder;

class RolesPermissionsTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $createAccountManagerRole = \Spatie\Permission\Models\Role::create([
            'name' => 'Account Manager',
            'guard_name' => 'web'
        ]);

        $createSuperAdminRole = \Spatie\Permission\Models\Role::create([
            'name' => 'Super Admin',
            'guard_name' => 'web'
        ]);

        $createSaleRepresentativeRole = \Spatie\Permission\Models\Role::create([
            'name' => 'Sales Representative',
            'guard_name' => 'web'
        ]);

        if (isset($createAccountManagerRole->id) && isset($createSuperAdminRole->id)) {

            $createPermission = \Spatie\Permission\Models\Permission::create([
                'name' => 'can_view_dashboard'
            ]);

            if (isset($createPermission->id)) {
                DB::table('role_has_permissions')->insert([
                    [
                        'role_id' => $createAccountManagerRole->id,
                        'permission_id' => $createPermission->id
                    ],
                    [
                        'role_id' => $createSuperAdminRole->id,
                        'permission_id' => $createPermission->id
                    ]
                ]);
            }
        }
    }
}
