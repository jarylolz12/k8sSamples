<?php

use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::unprepared("
              INSERT INTO `warehouses` (`id`, `type_id`, `name`, `phone`, `address`, `country_id`, `state_id`, `city_id`, `zip_code`, `deleted_at`, `created_at`, `updated_at`) VALUES 
              (NULL, '1', 'test warehouse', '13013780256', 'Hu Bei Sheng Wu Han Li Gong Da Xue Xi Yuan Dong Pei 501shi', '1', '2', '3', '13013780256', NULL, NULL, NULL),
              (NULL, '1', 'test warehouse', '13052810979', 'Tai Ping Qiao Da Jie 16hao Bei Feng C1guo Jia Kai Fa Yin Xing', '1', '2', '3', '13013780256', NULL, NULL, NULL),
              (NULL, '1', 'test warehouse', '13082529893', 'San Huan Nan Fu Dao 28hao', '1', '2', '3', '13013780256', NULL, NULL, NULL), 
              (NULL, '1', 'test warehouse', '13013886805', 'Feng Yuan Lu 128hao Jin Sheng Da Xia 5lou 502shi', '1', '2', '3', '13013780256', NULL, NULL, NULL), 
              (NULL, '1', 'test warehouse', '13083523923', 'Jiang Ling Lu 88hao 2hao Lou 2lou Wan Lun Zi Xing Che Zong Zhuang', '1', '2', '3', '13013780256', NULL, NULL, NULL), 
              (NULL, '1', 'test warehouse', '13057421845', 'Dong Guan Shi Gao Zhen Gao Bu Yi Yuan Ji Zhen Ke', '1', '2', '3', '13013780256', NULL, NULL, NULL);
         ");
        DB::unprepared("
            INSERT INTO `warehouse_types` (`id`, `name`, `deleted_at`, `created_at`, `updated_at`, `description`) VALUES 
            (NULL, 'warehouse type 1', NULL, NULL, NULL, 'Warehouse description 1'),
            (NULL, 'warehouse type 2', NULL, NULL, NULL, 'Warehouse description 2'),
            (NULL, 'warehouse type 3', NULL, NULL, NULL, 'Warehouse description 3'),
            (NULL, 'warehouse type 4', NULL, NULL, NULL, 'Warehouse description 4'),
            (NULL, 'warehouse type 5', NULL, NULL, NULL, 'Warehouse description 5'),
            (NULL, 'warehouse type 6', NULL, NULL, NULL, 'Warehouse description 6');     
        ");

    }
}
