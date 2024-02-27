<?php

use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::unprepared("INSERT INTO `cities` (`id`, `name`, `state_id`) VALUES 
          (NULL, 'Huntsville', 1),
          (NULL, 'Montgomery', 1),
          (NULL, 'Birmingham', 1),
          (NULL, 'Anchorage', 2),
          (NULL, 'Fairbanks', 2),
          (NULL, 'Juneau', 2),
          (NULL, 'Phoenix', 3),
          (NULL, 'Tucson', 3),
          (NULL, 'Mesa', 3); 
        ");
    }
}
