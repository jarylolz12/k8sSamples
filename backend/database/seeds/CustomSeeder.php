<?php

use Illuminate\Database\Seeder;

class CustomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::unprepared("
        INSERT INTO `customs_brokers` (`id`, `name`, `emails`, `customer_id`, `created_at`, `updated_at`) VALUES 
            (NULL, 'John Doe1', '[\"johndoe12@gmail . com\", \"johndoe2@gmail . com\"]', '2', NULL, NULL),
            (NULL, 'John Doe2', '[\"johndoe13@gmail . com\", \"johndoe3@gmail . com\"]', '22', NULL, NULL),
            (NULL, 'John Doe3', '[\"johndoe14@gmail . com\", \"johndoe4@gmail . com\"]', '34', NULL, NULL),
            (NULL, 'John Doe4', '[\"johndoe55@gmail . com\", \"johndoe5@gmail . com\"]', '1', NULL, NULL),
            (NULL, 'John Doe5', '[\"johndoe66@gmail . com\", \"johndoe6@gmail . com\"]', '6', NULL, NULL);
        ");
    }
}
