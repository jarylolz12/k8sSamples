<?php

use Illuminate\Database\Seeder;

class DocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        DB::unprepared("
            INSERT INTO `documents` (`id`, `name`, `type`, `path`, `shipment_id`, `created_at`, `updated_at`, `supplier_ids`) VALUES
            (1, '4eb983a5f9b0880e521c3cadb6b6e10e (5).pdf', 'Commercial Invoice', 'shipment/documents-customer/145f0d8e5a35b6631e15929b2fcca1ab.pdf', 13574, '2022-04-20 17:47:53', '2022-04-20 17:47:53', '[\"1782\"]'),
            (3, 'image (17).png', 'Other', 'shipment/documents-customer/f95c121e2d8bb81df8379d1f9057cd62.png', 13574, '2022-04-20 19:58:11', '2022-04-20 19:58:11', '[\"1833\"]'),
            (4, 'image (18).png', 'Other', 'shipment/documents-customer/db6258277323d39e5a02a586e6fd205c.png', 12438, '2022-04-20 20:03:55', '2022-04-20 20:03:55', '[\"1831\"]'),
            (5, 'modi', 'commercial invoice', 'shipment/documents-customer/1b2e65c6f0bc1bbb20f6413bb02864a0.txt', 2, '2022-04-26 07:24:54', '2022-04-26 07:24:55', NULL),
            (6, 'a', 'commercial invoice', 'shipment/documents-customer/399c20ecd8c9db58e351dcb47a34975b.webp', 2, '2022-04-27 04:48:06', '2022-04-27 04:48:06', NULL)
        ");


            DB::unprepared("
                  INSERT INTO `documents_suppliers` (`id`, `supplier_id`, `document_id`, `created_at`, `updated_at`) VALUES 
                  (NULL, '2', '1', NULL, NULL),
                  (NULL, '3', '3', NULL, NULL),
                  (NULL, '4', '4', NULL, NULL),
                  (NULL, '5', '5', NULL, NULL),
                  (NULL, '6', '6', NULL, NULL),
                  (NULL, '7', '1', NULL, NULL),
                  (NULL, '8', '3', NULL, NULL);
            ");

            DB::unprepared("
                INSERT INTO `import_names` (`id`, `import_name`, `ein`, `created_at`, `updated_at`, `customer_id`, `status`) VALUES 
                (NULL, 'John Doe', 'test1', NULL, NULL, '1', '0'),
                (NULL, 'Dummy Name', 'test2', NULL, NULL, '2', '0');
            ");



    }
}
