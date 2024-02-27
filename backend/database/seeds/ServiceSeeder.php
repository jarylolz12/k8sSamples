<?php

use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::unprepared("
INSERT INTO `services` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Freight (port to port)', '2021-03-14 19:57:50', '2021-06-25 13:36:09'),
(2, 'ISF / Customs Clearance', '2021-04-13 13:11:14', '2022-01-18 17:05:28'),
(3, 'Exwork charge', '2021-04-13 13:12:03', '2021-04-13 13:12:03'),
(4, 'Destination charge for warehouse', '2021-04-14 06:20:06', '2021-04-14 06:20:06'),
(5, 'Trucking difference charge', '2021-05-17 08:40:40', '2021-05-17 08:40:40'),
(6, 'Waiting fee', '2021-08-06 08:53:10', '2022-01-18 17:06:19'),
(7, 'Accounting Charges', '2021-08-30 08:16:32', '2022-01-18 17:05:58'),
(8, 'Additional charge', '2021-10-13 09:07:07', '2021-10-13 09:07:07'),
(9, 'Pick up and delivery charge', '2021-10-19 07:32:40', '2021-10-19 07:32:40'),
(10, 'On board fee', '2021-11-01 03:22:52', '2021-11-01 03:22:52'),
(11, 'Extra charge', '2021-11-01 03:23:20', '2021-11-01 03:23:20'),
(12, 'Telex Release Charge', '2021-12-06 03:34:56', '2022-01-18 17:06:37'),
(13, 'Destination Trucking', '2021-12-16 19:02:42', '2021-12-16 19:02:42'),
(14, 'DO Fee', '2021-12-29 05:55:59', '2022-01-18 17:06:10'),
(15, 'Dead Freight', '2022-01-12 01:29:44', '2022-01-12 01:29:44'),
(16, 'CHASSIS CHARGE', '2022-02-07 01:34:59', '2022-02-07 01:34:59'),
(17, 'FACILITY CHARGE FOR MSC', '2022-02-07 01:36:05', '2022-02-07 01:36:20'),
(18, 'ISPS', '2022-02-07 01:36:39', '2022-02-07 01:36:39'),
(19, 'warfage', '2022-02-07 01:38:15', '2022-02-07 01:38:15'),
(20, 'O/F PAID TO COLOADER DIRECTLY', '2022-02-07 13:13:47', '2022-02-07 13:13:47')
");
    }
}
