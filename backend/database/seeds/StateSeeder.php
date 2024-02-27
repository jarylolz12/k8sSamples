<?php

use Illuminate\Database\Seeder;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::unprepared("INSERT INTO `states` (`id`, `name`, `country_id`) VALUES 
              (NULL, 'Alabama', '2'),
              (NULL, 'Alaska', '2'),
              (NULL, 'Arizona', '2'),
              (NULL, 'Arkansas', '2'),
              (NULL, 'California', '2'),
              (NULL, 'Colorado', '2'),
              (NULL, 'Connecticut', '2'),
              (NULL, 'Delaware', '2'),
              (NULL, 'Florida', '2'),
              (NULL, 'Georgia', '2'),
              (NULL, 'Hawaii', '2'),
              (NULL, 'Idaho', '2'),
              (NULL, 'Illinois', '2'),
              (NULL, 'Anqing', '1'),
              (NULL, 'Bengbu', '1'),
              (NULL, 'Hefei', '1'),
              (NULL, 'Huainan', '1'),
              (NULL, 'Huangshan', '1'),
              (NULL, 'Ma’anshan', '1'),
              (NULL, 'Shexian', '1'),
              (NULL, 'Tongcheng', '1'); 
        ");
    }
}
