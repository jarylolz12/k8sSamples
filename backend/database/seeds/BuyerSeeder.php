<?php

use Illuminate\Database\Seeder;

class BuyerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //


        DB::table('buyer')->insert([
            [
                'company_name'=>'Duvav Trading',
                'address'=>'51190 Bernadine Glens Suite 726 Camrenfurt, NE 42195',
                'phone' =>'9738557737',
                'emails'=>'[\"chana@shifl.com\"]'
            ],
            [
                'company_name'=> 'Adirra LLC',
                'address'=>'436 Urban Shore Timothyborough, NE 92276',
                'phone'=>'9738557737',
                'emails'=>'[\"anthony@shifl.com\"]'
            ],

        ]);


        DB::unprepared("INSERT INTO `customer_buyer` (`id`, `customer_id`, `buyer_id`) VALUES
            (NULL, 1, 1),
            (NULL, 2, 1),
            (NULL, 3, 1),
            (NULL, 4, 1),
            (NULL, 5, 1),
            (NULL, 6, 1),
            (NULL, 14, 2),
            (NULL, 15, 2),
            (NULL, 16, 2),
            (NULL, 17, 2),
            (NULL, 18, 2);
        ");

        DB::unprepared("
            INSERT INTO `invitation_buyer` (`id`, `default_customer_id`, `buyer_id`, `email`, `status`, `created_at`, `updated_at`) VALUES 
            (NULL, '2', '1', 'test1 email', 0, NULL, NULL),
            (NULL, '3', '2', 'test2 email', 0, NULL, NULL),
            (NULL, '1', '2', 'test3 email', 0, NULL, NULL),
            (NULL, '4', '1', 'test4 email', 0, NULL, NULL),
            (NULL, '5', '1', 'test5 email', 0, NULL, NULL);
        ");

    }
}
