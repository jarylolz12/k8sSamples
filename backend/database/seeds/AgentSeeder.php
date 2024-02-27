<?php

use Illuminate\Database\Seeder;

class AgentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        DB::unprepared("INSERT INTO `agents` (`id`, `name`, `email`, `emails`, `number`, `address`, `website`, `notes_box`, `created_at`, `updated_at`) VALUES 
            (1, 'john doe', 'johndoe1@shifl.test', NULL, '026523218251', 'Davao', 'example.com', NULL, NULL, NULL),
            (2, 'test', 'test@shifl.test', NULL, '026523218251', 'Afgan', 'example.com', NULL, NULL, NULL);
        ");


    }
}
