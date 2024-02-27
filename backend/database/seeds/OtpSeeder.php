<?php

use Illuminate\Database\Seeder;

class OtpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::unprepared("
            INSERT INTO `otps` (`id`, `identifier`, `token`, `validity`, `expired`, `no_times_generated`, `no_times_attempted`, `generated_at`, `created_at`, `updated_at`) VALUES
            (114, 'shabsie@shifl.com', '842474', 30, 0, 1, 1, '2022-04-15 15:50:34', '2022-04-15 15:50:17', '2022-04-15 15:50:34')
        ");
    }
}
