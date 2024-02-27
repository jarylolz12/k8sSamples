<?php

use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::unprepared("
            INSERT INTO `payment_methods` (`id`, `default_customer_id`, `customer_admin_id`, `name`, `routing`, `account`, `token`, `is_default`, `created_at`, `updated_at`) VALUES 
            (NULL, '1', '1', 'Testing1', 'testing route', 'testing account', '1dcxz', '0', NULL, NULL),
            (NULL, '10', '4', 'John Doe', 'testing route', 'testing account', '1dcxz', '0', NULL, NULL),
            (NULL, '15', '5', 'Mark', 'testing route', 'testing account', '1dcxz', '0', NULL, NULL),
            (NULL, '5', '7', 'James', 'testing route', 'testing account', '1dcxz', '0', NULL, NULL),
            (NULL, '16', '8', 'Nikki', 'testing route', 'testing account', '1dcxz', '0', NULL, NULL),
            (NULL, '7', '9', 'Anthony', 'testing route', 'testing account', '1dcxz', '0', NULL, NULL),
            (NULL, '25', '10', 'Mike', 'testing route', 'testing account', '1dcxz', '0', NULL, NULL);
        ");
    }
}
