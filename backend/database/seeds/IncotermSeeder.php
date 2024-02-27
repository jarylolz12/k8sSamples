<?php

use Illuminate\Database\Seeder;

class IncotermSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::unprepared("
        INSERT INTO `incoterms` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Exworks', NULL, '2020-02-27 23:03:39', '2020-02-27 23:03:39'),
(2, 'FOB', NULL, '2020-02-27 23:03:43', '2020-02-27 23:03:43'),
(3, 'CIF', NULL, '2020-11-11 06:58:35', '2020-11-11 06:58:35'),
(4, 'FCA', NULL, '2021-05-09 06:38:49', '2021-05-09 06:38:49')
        ");
    }
}
