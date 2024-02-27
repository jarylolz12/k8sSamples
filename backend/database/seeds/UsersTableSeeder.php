<?php

use Illuminate\Database\Seeder;
use App\User;

use App\Customer;
use App\AccountManager;
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Customer and user seeder
        DB::table('users')->insert([
            'name' => 'Anthony Abadicio',
            'email' => 'anthony@shifl.com',
            'password' => bcrypt('password'),
        ]);
        DB::table('users')->insert([
            'name' => 'Kenneth',
            'email' => 'kenjos75@gmail.com',
            'password' => bcrypt('password'),
        ]);
        DB::table('users')->insert([
            'name' => 'Shabsie',
            'email' => 'shabsie@shifl.com',
            'password' => bcrypt('password'),
        ]);
        DB::table('users')->insert([
            'name' => 'Cyrus',
            'email' => 'cyrusrome22@gmail.com',
            'password' => bcrypt('password'),
        ]);
         factory(App\User::class, 455)->create();

//        factory(App\Customer::class, 20)->create();
//        $customers = Customer::all();
//        $users = User::all();
//        foreach($users as $user) {
//            foreach($customers as $customer) {
//                $user->customersApi()->attach($customer->id);
//
//                //customer has manager
//                 DB::table('customers_has_managers')->insert([
//                    'customer_id' => $customer->id,
//                    'user_id' => rand(1, 5),
//                ]);
//            }
//        }
//


//        DB::insert("INSERT INTO `users` (`id`, `name`, `email`, `password`, `remember_token`, `created_at`, `updated_at`, `forgot_password_verification_code`, `forgot_password_verification_code_created_at`, `is_forgot_password_requested`, `report_recipients`, `phone`, `shifl_office_id`, `has_access_to_central_app`, `has_access_to_trucking_app`, `default_customer_id`) VALUES
//(1, 'Anthony Abadicio1', 'anthony1@shifl.com', '$2y$10\$tAizMhIZ8bzR6FhfwS9NxudnG4wYqqAEIpZHX9ODEG5...', NULL, '2020-01-07 04:23:43', '2022-04-28 06:02:47', '', NULL, 0, NULL, NULL, NULL, 1, 1, NULL)");
    }
}
