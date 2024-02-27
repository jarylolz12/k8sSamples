<?php

use Illuminate\Database\Seeder;

class SuperAdminTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('users')->insert([
            'name' => 'Super Admin',
            'email' => 'superadmin@gmail.com',
            'password' => bcrypt('password'),
        ]);

        $checkCreatedAdminUser = DB::table('users')->where('email','superadmin@gmail.com')->first();
        $checkSuperAdminRole = \Spatie\Permission\Models\Role::where('name','Super Admin')->first();
        if(isset($checkSuperAdminRole->id)) {

            DB::table('model_has_roles')->insert([
                [
                    'model_type' => 'App\Admin',
                    'model_id' => $checkCreatedAdminUser->id,
                    'role_id'  => $checkSuperAdminRole->id
                ],
                [
                    'model_type' => 'App\User',
                    'model_id' => $checkCreatedAdminUser->id,
                    'role_id'  => $checkSuperAdminRole->id
                ],
            ]);




        }


    }
}