<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Schema\Blueprint;

class IndexRates extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('index_rates')->truncate();
        
        if( !Schema::hasColumn('index_rates','shipment_id' ) ){
            Schema::table('index_rates', function (Blueprint $table){
                $table->bigInteger('shipment_id')->nullable();
            });
        }
        

        DB::unprepared("INSERT INTO `index_rates` (`id`, `data_date`, `location_from`, `location_to`, `terminal`, `amount`, `container`, `created_at`, `updated_at`, `shipment_id`, `is_custom`) VALUES
(857, '2022-03-10', 'Shifl China', 'Shifl USA', 'NY/NJ', 10000.00, '40\'HQ', '2022-04-29 10:53:19', '2022-04-29 10:53:19', NULL, 1),
(856, '2022-03-10', 'Shifl China', 'Shifl USA', 'LOS ANGELES', 8200.00, '40\'HQ', '2022-04-29 10:49:53', '2022-04-29 10:49:53', NULL, 1),
(855, '2022-03-01', 'Shifl China', 'Shifl USA', 'NY/NJ', 12500.00, '40\'HQ', '2022-04-29 10:46:04', '2022-04-29 10:46:04', NULL, 1),
(854, '2022-03-01', 'Shifl China', 'Shifl USA', 'LOS ANGELES', 9500.00, '40\'HQ', '2022-04-29 10:41:02', '2022-04-29 10:41:02', NULL, 1),
(782, '2020-08-01', 'Shifl China', 'Shifl USA', 'NY/NJ', 3250.00, '40\'HQ', '2022-03-29 21:39:24', '2022-03-29 21:39:24', NULL, 1),
(786, '2019-08-01', 'Shifl China', 'Shifl USA', 'Los Angeles', 1495.00, '40\'HQ', '2022-03-31 19:08:45', '2022-03-31 19:08:45', NULL, 1),
(787, '2019-08-01', 'Shifl China', 'Shifl USA', 'NY/NJ', 2690.00, '40\'HQ', '2022-03-31 19:09:16', '2022-03-31 19:09:16', NULL, 1),
(788, '2019-09-01', 'Shifl China', 'Shifl USA', 'Los Angeles', 1610.00, '40\'HQ', '2022-03-31 19:09:50', '2022-03-31 19:09:50', NULL, 1),
(789, '2019-09-01', 'Shifl China', 'Shifl USA', 'NY/NJ', 2469.00, '40\'HQ', '2022-03-31 19:10:33', '2022-03-31 19:10:33', NULL, 1),
(790, '2019-10-01', 'Shifl China', 'Shifl USA', 'Los Angeles', 1225.00, '40\'HQ', '2022-03-31 19:11:56', '2022-03-31 19:11:56', NULL, 1),
(791, '2019-10-01', 'Shifl China', 'Shifl USA', 'NY/NJ', 2250.00, '40\'HQ', '2022-03-31 19:12:33', '2022-03-31 19:12:33', NULL, 1),
(792, '2019-11-01', 'Shifl China', 'Shifl USA', 'Los Angeles', 1540.00, '40\'HQ', '2022-03-31 19:13:14', '2022-03-31 19:13:14', NULL, 1),
(793, '2019-11-01', 'Shifl China', 'Shifl USA', 'NY/NJ', 2320.00, '40\'HQ', '2022-03-31 19:13:38', '2022-03-31 19:13:38', NULL, 1),
(794, '2019-12-01', 'Shifl China', 'Shifl USA', 'Los Angeles', 1150.00, '40\'HQ', '2022-03-31 19:14:10', '2022-03-31 19:14:10', NULL, 1),
(795, '2019-12-01', 'Shifl China', 'Shifl USA', 'NY/NJ', 2200.00, '40\'HQ', '2022-03-31 19:14:31', '2022-03-31 19:14:31', NULL, 1),
(796, '2020-01-01', 'Shifl China', 'Shifl USA', 'Los Angeles', 1670.00, '40\'HQ', '2022-03-31 19:17:40', '2022-03-31 19:17:40', NULL, 1),
(797, '2020-01-01', 'Shifl China', 'Shifl USA', 'NY/NJ', 2700.00, '40\'HQ', '2022-03-31 19:18:02', '2022-03-31 19:18:02', NULL, 1),
(798, '2020-02-01', 'Shifl China', 'Shifl USA', 'Los Angeles', 1520.00, '40\'HQ', '2022-03-31 19:21:48', '2022-03-31 19:21:48', NULL, 1),
(799, '2020-02-01', 'Shifl China', 'Shifl USA', 'NY/NJ', 2625.00, '40\'HQ', '2022-03-31 19:22:11', '2022-03-31 19:22:11', NULL, 1),
(800, '2020-03-01', 'Shifl China', 'Shifl USA', 'Los Angeles', 1350.00, '40\'HQ', '2022-03-31 19:22:42', '2022-03-31 19:22:42', NULL, 1),
(801, '2020-03-01', 'Shifl China', 'Shifl USA', 'NY/NJ', 2850.00, '40\'HQ', '2022-03-31 19:23:13', '2022-03-31 19:23:13', NULL, 1),
(802, '2020-04-01', 'Shifl China', 'Shifl USA', 'Los Angeles', 1370.00, '40\'HQ', '2022-03-31 19:24:58', '2022-03-31 19:24:58', NULL, 1),
(803, '2020-04-01', 'Shifl China', 'Shifl USA', 'NY/NJ', 2675.00, '40\'HQ', '2022-03-31 19:25:25', '2022-03-31 19:25:25', NULL, 1),
(804, '2020-05-01', 'Shifl China', 'Shifl USA', 'Los Angeles', 1500.00, '40\'HQ', '2022-03-31 19:25:46', '2022-03-31 19:25:46', NULL, 1),
(805, '2020-05-01', 'Shifl China', 'Shifl USA', 'NY/NJ', 2700.00, '40\'HQ', '2022-03-31 19:26:08', '2022-03-31 19:26:08', NULL, 1),
(806, '2020-06-01', 'Shifl China', 'Shifl USA', 'Los Angeles', 1700.00, '40\'HQ', '2022-03-31 19:27:02', '2022-03-31 19:27:02', NULL, 1),
(807, '2020-06-01', 'Shifl China', 'Shifl USA', 'NY/NJ', 2570.00, '40\'HQ', '2022-03-31 19:27:28', '2022-03-31 19:27:28', NULL, 1),
(808, '2020-07-01', 'Shifl China', 'Shifl USA', 'Los Angeles', 2700.00, '40\'HQ', '2022-03-31 19:27:53', '2022-03-31 19:27:53', NULL, 1),
(809, '2020-07-01', 'Shifl China', 'Shifl USA', 'NY/NJ', 3400.00, '40\'HQ', '2022-03-31 19:28:12', '2022-03-31 19:28:12', NULL, 1),
(810, '2020-08-01', 'Shifl China', 'Shifl USA', 'Los Angeles', 3250.00, '40\'HQ', '2022-03-31 19:28:39', '2022-03-31 19:28:39', NULL, 1),
(811, '2020-08-01', 'Shifl China', 'Shifl USA', 'NY/NJ', 3925.00, '40\'HQ', '2022-03-31 19:29:00', '2022-04-01 03:29:10', NULL, 1),
(812, '2020-09-01', 'Shifl China', 'Shifl USA', 'Los Angeles', 3700.00, '40\'HQ', '2022-03-31 19:30:30', '2022-03-31 19:30:30', NULL, 1),
(813, '2020-09-01', 'Shifl China', 'Shifl USA', 'NY/NJ', 4250.00, '40\'HQ', '2022-03-31 19:30:59', '2022-03-31 19:30:59', NULL, 1),
(814, '2020-10-01', 'Shifl China', 'Shifl USA', 'Los Angeles', 3950.00, '40\'HQ', '2022-03-31 19:31:27', '2022-03-31 19:31:27', NULL, 1),
(815, '2020-10-01', 'Shifl China', 'Shifl USA', 'NY/NJ', 4820.00, '40\'HQ', '2022-03-31 19:31:44', '2022-03-31 19:31:44', NULL, 1),
(816, '2020-11-01', 'Shifl China', 'Shifl USA', 'Los Angeles', 4025.00, '40\'HQ', '2022-03-31 19:33:31', '2022-03-31 19:33:31', NULL, 1),
(817, '2020-11-01', 'Shifl China', 'Shifl USA', 'NY/NJ', 4950.00, '40\'HQ', '2022-03-31 19:33:48', '2022-03-31 19:33:48', NULL, 1),
(818, '2020-12-01', 'Shifl China', 'Shifl USA', 'Los Angeles', 4225.00, '40\'HQ', '2022-03-31 19:39:40', '2022-03-31 19:39:40', NULL, 1),
(819, '2020-12-01', 'Shifl China', 'Shifl USA', 'NY/NJ', 5200.00, '40\'HQ', '2022-03-31 19:39:51', '2022-03-31 19:39:51', NULL, 1),
(820, '2021-01-01', 'Shifl China', 'Shifl USA', 'Los Angeles', 4640.00, '40\'HQ', '2022-03-31 19:43:38', '2022-03-31 19:43:38', NULL, 1),
(821, '2021-01-01', 'Shifl China', 'Shifl USA', 'NY/NJ', 6450.00, '40\'HQ', '2022-03-31 19:43:53', '2022-03-31 19:43:53', NULL, 1),
(822, '2021-02-01', 'Shifl China', 'Shifl USA', 'Los Angeles', 5600.00, '40\'HQ', '2022-03-31 19:44:22', '2022-03-31 19:44:22', NULL, 1),
(823, '2021-02-01', 'Shifl China', 'Shifl USA', 'NY/NJ', 7400.00, '40\'HQ', '2022-03-31 19:51:22', '2022-03-31 19:51:22', NULL, 1),
(824, '2021-03-01', 'Shifl China', 'Shifl USA', 'Los Angeles', 4800.00, '40\'HQ', '2022-03-31 19:51:46', '2022-03-31 19:51:46', NULL, 1),
(825, '2021-03-01', 'Shifl China', 'Shifl USA', 'NY/NJ', 5300.00, '40\'HQ', '2022-03-31 19:52:18', '2022-03-31 19:52:18', NULL, 1),
(826, '2021-04-01', 'Shifl China', 'Shifl USA', 'Los Angeles', 6100.00, '40\'HQ', '2022-03-31 19:52:51', '2022-03-31 19:52:51', NULL, 1),
(827, '2021-04-01', 'Shifl China', 'Shifl USA', 'NY/NJ', 8900.00, '40\'HQ', '2022-03-31 19:53:19', '2022-03-31 19:53:19', NULL, 1),
(828, '2021-05-01', 'Shifl China', 'Shifl USA', 'Los Angeles', 7600.00, '40\'HQ', '2022-03-31 19:53:42', '2022-03-31 19:53:42', NULL, 1),
(829, '2021-05-01', 'Shifl China', 'Shifl USA', 'NY/NJ', 12500.00, '40\'HQ', '2022-03-31 19:53:56', '2022-03-31 19:53:56', NULL, 1),
(830, '2021-06-01', 'Shifl China', 'Shifl USA', 'Los Angeles', 9000.00, '40\'HQ', '2022-03-31 19:54:32', '2022-03-31 19:54:32', NULL, 1),
(831, '2021-06-01', 'Shifl China', 'Shifl USA', 'NY/NJ', 14000.00, '40\'HQ', '2022-03-31 19:55:22', '2022-03-31 19:55:22', NULL, 1),
(832, '2021-07-01', 'Shifl China', 'Shifl USA', 'Los Angeles', 13500.00, '40\'HQ', '2022-03-31 19:56:03', '2022-03-31 19:56:03', NULL, 1),
(833, '2021-07-01', 'Shifl China', 'Shifl USA', 'NY/NJ', 15000.00, '40\'HQ', '2022-03-31 19:56:28', '2022-03-31 19:56:28', NULL, 1),
(834, '2021-08-01', 'Shifl China', 'Shifl USA', 'Los Angeles', 15500.00, '40\'HQ', '2022-03-31 19:56:57', '2022-03-31 19:56:57', NULL, 1),
(835, '2021-08-01', 'Shifl China', 'Shifl USA', 'NY/NJ', 17500.00, '40\'HQ', '2022-03-31 19:57:27', '2022-03-31 19:57:27', NULL, 1),
(836, '2021-09-01', 'Shifl China', 'Shifl USA', 'Los Angeles', 17500.00, '40\'HQ', '2022-03-31 19:58:53', '2022-03-31 19:58:53', NULL, 1),
(837, '2021-09-01', 'Shifl China', 'Shifl USA', 'NY/NJ', 19500.00, '40\'HQ', '2022-03-31 19:59:06', '2022-03-31 19:59:06', NULL, 1),
(838, '2021-10-01', 'Shifl China', 'Shifl USA', 'Los Angeles', 8500.00, '40\'HQ', '2022-03-31 19:59:31', '2022-03-31 19:59:31', NULL, 1),
(839, '2021-10-01', 'Shifl China', 'Shifl USA', 'NY/NJ', 14000.00, '40\'HQ', '2022-03-31 19:59:56', '2022-03-31 19:59:56', NULL, 1),
(840, '2021-11-01', 'Shifl China', 'Shifl USA', 'Los Angeles', 8300.00, '40\'HQ', '2022-03-31 20:00:27', '2022-03-31 20:00:27', NULL, 1),
(841, '2021-11-01', 'Shifl China', 'Shifl USA', 'NY/NJ', 13800.00, '40\'HQ', '2022-03-31 20:00:53', '2022-03-31 20:00:53', NULL, 1),
(842, '2021-11-15', 'Shifl China', 'Shifl USA', 'Los Angeles', 10000.00, '40\'HQ', '2022-03-31 20:02:24', '2022-03-31 20:02:24', NULL, 1),
(843, '2021-11-15', 'Shifl China', 'Shifl USA', 'NY/NJ', 14500.00, '40\'HQ', '2022-03-31 20:02:47', '2022-03-31 20:02:47', NULL, 1),
(844, '2021-12-01', 'Shifl China', 'Shifl USA', 'Los Angeles', 11500.00, '40\'HQ', '2022-03-31 20:03:24', '2022-03-31 20:03:24', NULL, 1),
(845, '2021-12-01', 'Shifl China', 'Shifl USA', 'NY/NJ', 16000.00, '40\'HQ', '2022-03-31 20:04:00', '2022-03-31 20:04:00', NULL, 1),
(846, '2021-12-15', 'Shifl China', 'Shifl USA', 'Los Angeles', 13000.00, '40\'HQ', '2022-03-31 20:04:37', '2022-03-31 20:04:37', NULL, 1),
(847, '2021-12-15', 'Shifl China', 'Shifl USA', 'NY/NJ', 17000.00, '40\'HQ', '2022-03-31 20:05:09', '2022-03-31 20:05:09', NULL, 1),
(848, '2022-01-01', 'Shifl China', 'Shifl USA', 'Los Angeles', 17000.00, '40\'HQ', '2022-03-31 20:05:51', '2022-03-31 20:05:51', NULL, 1),
(849, '2022-01-01', 'Shifl China', 'Shifl USA', 'NY/NJ', 20000.00, '40\'HQ', '2022-03-31 20:06:37', '2022-03-31 20:06:37', NULL, 1),
(850, '2022-01-15', 'Shifl China', 'Shifl USA', 'Los Angeles', 15000.00, '40\'HQ', '2022-03-31 20:07:04', '2022-03-31 20:07:04', NULL, 1),
(851, '2022-01-15', 'Shifl China', 'Shifl USA', 'NY/NJ', 16000.00, '40\'HQ', '2022-03-31 20:07:31', '2022-03-31 20:07:31', NULL, 1),
(852, '2022-02-15', 'Shifl China', 'Shifl USA', 'LOS ANGELES', 10000.00, '40\'HQ', '2022-03-31 20:08:00', '2022-04-29 10:34:10', NULL, 1),
(853, '2022-02-15', 'Shifl China', 'Shifl USA', 'NY/NJ', 13000.00, '40\'HQ', '2022-03-31 20:08:34', '2022-05-04 07:20:47', NULL, 1),
(858, '2022-04-15', 'Shifl China', 'Shifl USA', 'LOS ANGELES', 7500.00, '40\'HQ', '2022-04-29 10:54:56', '2022-04-29 10:54:56', NULL, 1),
(859, '2022-04-15', 'Shifl China', 'Shifl USA', 'NY/NJ', 10000.00, '40\'HQ', '2022-04-29 10:55:56', '2022-05-04 15:13:25', NULL, 1);");
    }
}
