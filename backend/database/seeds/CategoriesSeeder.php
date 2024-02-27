        <?php

use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        DB::unprepared("INSERT INTO `categories` (`id`, `name`, `deleted_at`, `created_at`, `updated_at`, `description`) VALUES 
                        (NULL, 'test1', NULL, NULL, NULL, 'Testing Category'),
                        (NULL, 'test2', NULL, NULL, NULL, 'Testing Category');
        ");
    }
}
