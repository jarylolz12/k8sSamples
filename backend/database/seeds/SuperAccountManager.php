
<?php

use Illuminate\Database\Seeder;

class superAccountManager extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $emails = ['Chana@shifl.com'];

        foreach($emails as $e ){
            $user = DB::table('users')->whereFirst('email',$e);

            if( !$user ){
                continue;
            }

            $role = \Spatie\Permission\Models\Role::firstOrCreate(
                [ 'name' => 'Super Account Manager' ]
            );

            if($role) {

                if( !DB::table('model_has_roles')->where('model_type','App\Admin')->where('model_id',$user->id)->where('role_id',$role->id)->exists() ){
                    DB::table('model_has_roles')->insert([
                        [
                            'model_type' => 'App\Admin',
                            'model_id' => $user->id,
                            'role_id'  => $role->id
                        ]                        
                    ]);
                }

                if( !DB::table('model_has_roles')->where('model_type','App\User')->where('model_id',$user->id)->where('role_id',$role->id)->exists() ){
                    DB::table('model_has_roles')->insert([
                        [
                            'model_type' => 'App\User',
                            'model_id' => $user->id,
                            'role_id'  => $role->id
                        ]
                    ]);
                }

            }
        }
    }
}
