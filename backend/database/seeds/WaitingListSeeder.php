<?php

use Illuminate\Database\Seeder;

class WaitingListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {



        DB::unprepared("
        
        INSERT INTO `waiting_lists` (`id`, `company_name`, `business_type`, `approximate_annual_shipments`, `phone_number`, `email`, `contact_person`, `created_at`, `updated_at`) VALUES
(15, 'ertyuio', 'Carrier', 45, '123456789', 'daxzdxb@gmail.com', 'cfsetewrdj', '2022-01-18 06:01:39', '2022-01-18 06:01:39'),
(16, 'wdfgh', 'Carrier', 5, '234567890', 'gxghdcd@gmail.com', 'sdfghjkl', '2022-01-18 06:04:30', '2022-01-18 06:04:30'),
(17, 'sdfghjk', 'Carrier', 3, '234567890', 'sdfghjytkl@gmail.com', 'tttudutd', '2022-01-18 06:08:23', '2022-01-18 06:08:23'),
(18, 'tretytryiw', 'Carrier', 2, '23456789', 'fsgKUEUD@gmail.com', 'dsddsjajdf', '2022-01-18 06:09:37', '2022-01-18 06:09:37'),
(19, 'afwawe', 'Carrier', 8, '2456789', 'erffd@gmail.com', 'jsnjbh', '2022-01-18 06:11:06', '2022-01-18 06:11:06'),
(20, 'afwawe', 'Carrier', 8, '2456789', 'erffde@gmail.com', 'jsnjbh', '2022-01-18 06:17:49', '2022-01-18 06:17:49'),
(21, 'dgcvgvc', 'Carrier', 5, '23456789', 'okk@gmail.com', 'oskopjso', '2022-01-18 06:19:36', '2022-01-18 06:19:36'),
(22, 'uohyit', 'Carrier', 8, '90576', 'ythokp@gmail.com', 'pokil', '2022-01-18 08:53:59', '2022-01-18 08:53:59'),
(23, 'kjhgfds', 'Carrier', 9, '09876543', 'eryyy@gmail.com', 'lkjhgf', '2022-01-18 09:38:13', '2022-01-18 09:38:13'),
(24, 'yuyiu', 'Cargo Terminal', 9, '03239808958', 'gjycct@gmail.com', '89u898', '2022-01-18 10:35:01', '2022-01-18 10:35:01'),
(25, 'qwertyuiiop', 'Forwarder/Customs', 2, '3456789', 'ddxxrrxdrx@gmail.com', 'opiu', '2022-01-18 10:40:14', '2022-01-18 10:40:14'),
(26, 'ygygyg', 'Forwarder/Customs', 60, '23456789', '23456789@gmail.com', '1ssstjhgffdxcv', '2022-01-18 10:43:16', '2022-01-18 10:43:16'),
(27, 'TR solution', 'Carrier', 22, '+92 322 4668246', 'usman@shilf.com', 'Usman sh', '2022-01-18 11:29:09', '2022-01-18 11:29:09'),
(28, 'TR sol test', 'Carrier', 4, '+86 21 2121 2445', 'test@gmail.com', 'Test user', '2022-01-18 22:11:24', '2022-01-18 22:11:24'),
(29, 'fgvvjcsv', 'Shipper', 90, '03239808958', 'gvcjdsvg@gmail.com', 'hglshj', '2022-01-19 05:39:15', '2022-01-19 05:39:15'),
(30, '456789', 'Cargo Terminal', 6, '456789678', 'pojoji@gmail.com', 'candy', '2022-01-19 05:40:15', '2022-01-19 05:40:15'),
(31, 'gfcgjcg', 'Cargo Terminal', 9, '+93 5865996565', 'fxdxzszsd@gmail.com', 'bxjshvh', '2022-01-19 05:44:15', '2022-01-19 05:44:15'),
(32, 'sazaszsa', 'Carrier', 98, '03239808958', 'fffffffff@gmail.com', 'hhhbhbhbhbh', '2022-01-19 11:41:33', '2022-01-19 11:41:33'),
(39, 'Anas', 'Shipper', 9090, '909090099090990', 'anas@gmail.com', 'Anass', '2022-01-20 12:35:33', '2022-01-20 12:35:33'),
(40, 'asdsad', 'Shipper', 3, '313131313456677', 'anads@gmail.com', 'ggad', '2022-01-20 12:59:17', '2022-01-20 12:59:17'),
(41, 'Anas COO', 'Carrier', 2, '214-81094', 'anasdad@gmail.com', 'asdgg', '2022-01-20 13:05:12', '2022-01-20 13:05:12'),
(42, 'Anas', 'Carrier', 909, '909009909099', 'anasss2@gmai.com', '12121', '2022-01-20 13:16:34', '2022-01-20 13:16:34'),
(43, 'Anas', 'Carrier', 90909, '+1 223-232-32323', 'asas@gmail.com', 'Anassss', '2022-01-20 13:31:11', '2022-01-20 13:31:11'),
(45, 'ttrrte', 'Carrier', 7, '+93 34 567 0989', 'cgggccfhfg@gmail.com', 'gfghfghfh', '2022-01-20 17:12:20', '2022-01-20 17:12:20'),
(46, 'tyuio', 'Shipper', 0, '+92323980895', 'gveyjgqvc@gmail.com', 'uhuhbvu', '2022-01-20 17:17:19', '2022-01-20 17:17:19'),
(47, 'teteytyq', 'Carrier', 8, '+93 32 398 0895', 'ihdididi@gmail.com', 'ytut', '2022-01-20 17:18:24', '2022-01-20 17:18:24'),
(48, 'tryuiop', 'Carrier', 77, '+93 34 567 0989', 'dcxfCX@gmail.com', 'gfvgfvgvg', '2022-01-20 17:27:13', '2022-01-20 17:27:13'),
(49, 'Panther Imports', 'Shipper', 5, '+1 845-587-1257', 'yw@pantherimports.com', 'Yechiel Weiss', '2022-01-20 18:26:41', '2022-01-20 18:26:41'),
(50, 'jkhskgf', 'Carrier', 9, '+92 323 9808958', 'example@gmail.com', 'yuioo', '2022-01-20 18:58:41', '2022-01-20 18:58:41'),
(51, 'wertyui', 'Forwarder/Customs', 9, '+92 323 9808958', 'exapmle@gmail.com', 'oprg', '2022-01-20 19:06:35', '2022-01-20 19:06:35'),
(52, 'CustomsPlus', 'Other', 24000, '+44 7501 224613', 'dominicm@customsplus.co.uk', 'Dominic McGough', '2022-01-20 20:30:53', '2022-01-20 20:30:53'),
(53, 'AZNWHOLESALE', 'Other', 50, '+1 347-419-1400', 'Joe@aznwholesale.com', 'Joseph Katz', '2022-01-20 23:13:26', '2022-01-20 23:13:26'),
(54, 'AM Trading LLC', 'Shipper', 40, '+1 718-594-4717', 'ari@amtradingllc.com', 'Ari Kaufman', '2022-01-21 01:32:24', '2022-01-21 01:32:24'),
(55, 'CargoTrans, Inc.', 'Forwarder/Customs', 15000, '+1 516-524-8185', 'Anthony@cargotransinc.com', 'Anthony De Filippis', '2022-01-21 10:02:29', '2022-01-21 10:02:29'),
(56, 'Sealink International Inc', 'Forwarder/Customs', 30000, '+1 214-377-1634', 'Sshroff@sea-link.com', 'Shaizad Shroff', '2022-01-21 18:51:20', '2022-01-21 18:51:20'),
(57, 'CoLoadX Corporation', 'Forwarder/Customs', 1000, '+1 408-316-7794', 'pminer@coloadx.com', 'Petere Miner', '2022-01-21 19:41:39', '2022-01-21 19:41:39'),
(58, 'Shipping Services Italia , INC', 'Forwarder/Customs', 10000, '+1 917-517-4321', 'maxb@fremuragroup.com', 'Max Bess', '2022-01-21 20:31:49', '2022-01-21 20:31:49'),
(59, 'Fombad Trust posts and telecommunications', 'Trucker/Broker', 20000, '+237 6 70 28 02 02', 'gfbamenda@gmail.com', 'Fombad musaga', '2022-01-22 00:51:40', '2022-01-22 00:51:40'),
(60, 'Gidov logistics Ltd', 'Forwarder/Customs', 150, '+44 7729 229830', 'igidov@gidovlogistics.co.uk', 'Ilian gidov', '2022-01-22 09:50:55', '2022-01-22 09:50:55'),
(61, 'Bashundhara Group', 'Other', 1000, '+880 1704-112425', 'mostafa.shakil@outlook.com', 'MOSTAFA SHAKIL', '2022-01-26 03:04:17', '2022-01-26 03:04:17'),
(62, 'Ma.Pr.I.Com S.p.A', 'Other', 500, '+39 345 248 2767', 'chicco@mapricom.com', 'Chicco Pozzoli', '2022-01-26 07:03:07', '2022-01-26 07:03:07'),
(66, 'Terminal49', 'Other', 100, '+1 408-203-7971', 'akshay@terminal49.com', 'Akshay Dodeja', '2022-02-18 23:00:11', '2022-02-18 23:00:11'),
(67, 'ertyuio1', 'Shipper', 45, '123456789', 'daxzdxbss@gmail.com', 'cfsetewrdj', '2022-04-28 02:39:51', '2022-04-28 02:39:51')

        
        
        
        ");
    }
}
