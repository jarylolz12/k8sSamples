<?php

use Illuminate\Database\Seeder;

class NoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::unprepared("
            INSERT INTO `notes` (`id`, `noteable_id`, `noteable_type`, `name`, `type`, `content`, `created_at`, `updated_at`) VALUES 
            (NULL, '1', '1', 'john doe', 'text', 'Testing notes 1', current_timestamp(), NULL),
            (NULL, '1', '1', 'john doe', 'text', 'Testing notes 2', current_timestamp(), NULL),
            (NULL, '1', '1', 'john doe', 'text', 'Testing notes 3', current_timestamp(), NULL),
            (NULL, '1', '1', 'john doe', 'text', 'Testing notes 4', current_timestamp(), NULL),
            (NULL, '1', '1', 'john doe', 'text', 'Testing notes 5', current_timestamp(), NULL); 
        ");
    }
}
