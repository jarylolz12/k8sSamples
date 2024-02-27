<?php

use Illuminate\Database\Seeder;

class PurchaseOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::unprepared(" 
INSERT INTO `purchase_orders` (`id`, `customer_id`, `po_num`, `supplier_id`, `po_items`, `created_at`, `updated_at`) VALUES
(1, 48, '1415909', 11, '[]', '2022-01-14 07:04:33', '2022-01-14 07:04:33'),
(4, 48, '1415910', 11, '[]', '2022-01-14 07:05:49', '2022-01-14 07:05:49'),
(6, 48, '1415949', 11, '[]', '2022-01-14 08:09:41', '2022-01-14 08:09:41'),
(7, 194, '111000007', 2429, '[]', '2022-03-09 16:19:51', '2022-03-09 16:19:51'),
(9, 447, '111000009', 1836, '[]', '2022-04-08 13:51:18', '2022-04-08 13:51:18')
        ");
    }
}
