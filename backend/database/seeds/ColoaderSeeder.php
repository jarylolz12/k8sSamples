        <?php

use Illuminate\Database\Seeder;

class ColoaderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::unprepared("
        
        
INSERT INTO `coloaders` (`id`, `name`, `email`, `emails`, `number`, `address`, `website`, `notes_box`, `created_at`, `updated_at`) VALUES
(1, '衍六上海', NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-23 03:01:34', '2022-03-23 03:01:34'),
(2, '厦门衍六', NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-23 03:24:28', '2022-03-23 03:24:28'),
(3, '深圳易脉', NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-23 08:00:04', '2022-03-24 14:11:30'),
(4, '宁波视野', NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-23 08:24:12', '2022-03-23 08:24:12'),
(5, '青岛衍六', NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-24 02:54:37', '2022-03-24 02:54:37'),
(6, '上海世纪元', NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-24 14:10:29', '2022-03-24 14:10:29'),
(7, '宁波新咏航', NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-24 14:12:02', '2022-03-24 14:12:02'),
(8, '上海新咏航', NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-24 14:12:19', '2022-03-24 14:12:19'),
(9, '深圳东南', NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-24 14:13:02', '2022-03-24 14:13:02'),
(10, '上海万嘉', NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-24 14:13:15', '2022-03-24 14:13:15'),
(11, '宁波腓尼基', NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-24 14:13:47', '2022-03-24 14:13:47'),
(12, '上海格祺', NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-24 14:14:00', '2022-03-24 14:14:00'),
(13, '厦门东南', NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-24 14:14:27', '2022-03-24 14:14:27'),
(14, '上海海和', NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-24 14:15:03', '2022-03-24 14:15:03'),
(15, '宁波欧讯立德', NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-24 14:15:16', '2022-03-24 14:15:16'),
(16, '宁波美商纵横', NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-24 14:15:47', '2022-03-24 14:15:47'),
(17, '上海克纳', NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-24 14:16:05', '2022-03-24 14:16:05'),
(18, '青岛晨星', NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-24 14:16:36', '2022-03-24 14:16:36'),
(19, '欧华天津', NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-24 14:16:47', '2022-03-24 14:16:47'),
(20, '欧华青岛', NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-24 14:17:16', '2022-03-24 14:17:16'),
(21, '青岛昌博', NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-24 14:17:26', '2022-03-24 14:17:26'),
(22, '深圳中启', NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-24 14:17:45', '2022-03-24 14:17:45'),
(23, '宁波衍六', NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-24 14:18:05', '2022-03-24 14:18:05'),
(24, '乐仕星', NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-24 14:18:45', '2022-03-24 14:18:45'),
(25, '上海聿飞', NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-24 14:19:04', '2022-03-24 14:19:04'),
(26, '上海盾豪', NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-24 14:19:38', '2022-03-24 14:19:38'),
(27, '深圳维佳', NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-24 14:21:23', '2022-03-24 14:21:23'),
(28, '厦门维佳', NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-24 14:21:32', '2022-03-24 14:21:32'),
(29, '上海旭达', NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-24 14:22:14', '2022-03-24 14:22:14'),
(30, '华光源海', NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-24 14:22:29', '2022-03-24 14:22:29'),
(31, '上海赋昶', NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-24 14:23:29', '2022-03-24 14:23:29'),
(32, '上海稷高', NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-24 14:23:57', '2022-03-24 14:23:57'),
(33, '深圳行八方', NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-24 14:24:15', '2022-03-24 14:24:15'),
(34, '上海晟澜', NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-24 14:24:50', '2022-03-24 14:24:50'),
(35, '江苏锦程', NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-24 14:25:00', '2022-03-24 14:25:00'),
(36, '上海雨虹', NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-24 14:25:32', '2022-03-24 14:25:32'),
(37, '上海捷航', NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-24 14:25:44', '2022-03-24 14:25:44'),
(38, '上海美航', NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-24 14:25:56', '2022-03-24 14:25:56'),
(39, '上海中艺', NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-24 14:26:42', '2022-03-24 14:26:42'),
(40, '上海中艺', NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-24 14:27:15', '2022-03-24 14:27:15'),
(41, '运去哪', NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-24 14:28:04', '2022-03-24 14:28:04'),
(42, '衍六台湾', NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-24 14:28:37', '2022-03-24 14:28:37'),
(43, '北京金开宇', NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-24 14:29:33', '2022-03-24 14:29:33'),
(44, '上海汇力  Shanghai Huili', NULL, '[]', NULL, NULL, NULL, NULL, '2022-03-24 14:30:52', '2022-04-05 03:33:28'),
(45, 'Pegasus Global Logisics Co., Ltd', 'mike.pham@vn.pegasuslog.com', NULL, '+84 9447468680', 'Success 1 building, 3 Le Thanh Tong street, Ngo Quyen district, Haiphong, Vietnam', NULL, 'Providing various type of booking to US and Intra Asia countries', '2022-03-27 14:16:12', '2022-03-27 14:16:12'),
(46, '青岛欧华', NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-29 02:41:33', '2022-03-29 02:41:33')

        ");
    }
}
