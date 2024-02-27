<?php

use Illuminate\Database\Seeder;

use Illuminate\Database\Schema\Blueprint;
class CardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::insert("INSERT INTO `cards` (`type`, `expiration_month`, `expiration_year`, `number_masked`, `number_hashed`, `first_name`, `last_name`,   `created_at`, `updated_at`, `is_default`) VALUES
                ('VISA', 8, 2026, '415417******3090', '9574BDA1CE4826F1559563D6067C1E02E19738FB9B858CFF4AECF830881E9448', 'Anthony', 'Abadicio', '2022-03-04 13:32:56', '2022-03-04 13:32:56', 1),
                ('AMERICAN_EXPRESS', 12, 2025,  '372729******1001', 'FCC723E9D7B19ED1DE4B3DBCFAFE67CC5540537378409CC58E8062C5FA79BCAB', 'Shabsie','Levy', '2022-04-05 04:29:46', '2022-04-12 17:22:31', 1)
        ");

    }
}
