<?php

use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run th   e database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::unprepared("
            INSERT INTO `items` (`id`, `item_num`, `customer_id`, `description`, `classification_code`, `duty_rate`, `created_at`, `updated_at`, `name`, `category_id`, `image`, `deleted_at`) VALUES 
            (NULL, '12354', '1', 'Testing item 1', '12432', '9', NULL, NULL, 'John Doe', '1', '', NULL),
            (NULL, '12353', '2', 'Testing item 2', '12432', '9', NULL, NULL, 'John Doe', '2', '', NULL),
            (NULL, '12344', '4', 'Testing item 3', '12432', '9', NULL, NULL, 'John Doe', '1', '', NULL),
            (NULL, '12564', '6', 'Testing item 4', '12432', '9', NULL, NULL, 'John Doe', '1', '', NULL),
            (NULL, '12214', '7', 'Testing item 5', '12432', '9', NULL, NULL, 'John Doe', '2', '', NULL),
            (NULL, '13254', '5', 'Testing item 6', '12432', '9', NULL, NULL, 'John Doe', '2', '', NULL),
            (NULL, '12374', '9', 'Testing item 7', '12432', '9', NULL, NULL, 'John Doe', '1', '', NULL);
        ");

        DB::unprepared("
          INSERT INTO `item_suppliers` (`id`, `item_id`, `supplier_id`, `created_at`, `updated_at`) VALUES 
          (NULL, '1', '9', NULL, NULL),
          (NULL, '2', '11', NULL, NULL),
          (NULL, '4', '47', NULL, NULL),
          (NULL, '3', '57', NULL, NULL),
          (NULL, '7', '59', NULL, NULL),
          (NULL, '5', '64', NULL, NULL),
          (NULL, '1', '65', NULL, NULL),
          (NULL, '3', '77', NULL, NULL),
          (NULL, '6', '89', NULL, NULL);
        ");
    }
}
