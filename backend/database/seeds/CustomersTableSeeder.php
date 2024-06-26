<?php

use Illuminate\Database\Seeder;

class CustomersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        DB::table('customers')->delete();
//
//        $customers = array(
//            array('company_name' => "Test customer 1", 'address' => 'Test address', 'phone' => '121212', 'admin_user_id' => null, 'managers' => 'test@gmail.com', 'sale_persons' => 'test'),
//            array('company_name' => "Test customer 2", 'address' => 'Test address', 'phone' => '121212', 'admin_user_id' => null, 'managers' => 'test@gmail.com', 'sale_persons' => 'test'),
//        );
//
//        DB::table('customers')->insert($customers);
//



        factory(App\Customer::class, 676)->create();

DB::unprepared("
INSERT INTO `customers_has_managers` (`customer_id`, `user_id`) VALUES
(16, 4),
(15, 4),
(14, 4),
(8, 4),
(12, 4),
(11, 4),
(10, 4),
(9, 4),
(7, 4),
(6, 4),
(5, 4),
(4, 4),
(3, 4),
(1, 4),
(17, 5),
(18, 6),
(19, 4),
(20, 4),
(21, 4),
(22, 5),
(23, 5),
(24, 5),
(25, 5),
(26, 5),
(27, 5),
(28, 5),
(29, 5),
(30, 5),
(31, 6),
(32, 6),
(33, 6),
(35, 6),
(36, 6),
(37, 6),
(38, 6),
(2, 7),
(39, 5),
(40, 5),
(41, 7),
(42, 5),
(43, 7),
(44, 6),
(45, 5),
(46, 7),
(47, 5),
(48, 5),
(49, 5),
(50, 5),
(51, 5),
(52, 5),
(53, 5),
(54, 5),
(55, 5),
(56, 5),
(57, 5),
(59, 5),
(58, 5),
(60, 5),
(61, 5),
(62, 5),
(63, 5),
(64, 5),
(34, 6),
(65, 5),
(66, 6),
(13, 7),
(69, 5),
(68, 5),
(67, 5),
(70, 5),
(71, 5),
(72, 6)
");


DB::unprepared("

INSERT INTO `customer_admins` (`id`, `customer_id`, `user_id`) VALUES
(1, 25, 11),
(4, 57, 12),
(5, 32, 13),
(7, 18, 14),
(8, 36, 15),
(9, 22, 16),
(10, 85, 17),
(11, 78, 18),
(13, 70, 19),
(14, 33, 20),
(15, 33, 11),
(16, 24, 21),
(17, 7, 22),
(18, 55, 23),
(19, 2, 24),
(20, 71, 25),
(21, 103, 27),
(22, 73, 13),
(23, 15, 28),
(24, 54, 29),
(25, 130, 30),
(26, 124, 31),
(27, 25, 32),
(28, 28, 33),
(29, 21, 34),
(30, 113, 35),
(31, 4, 36),
(32, 133, 37),
(33, 16, 38),
(34, 97, 38),
(35, 99, 42),
(36, 17, 42),
(37, 35, 42),
(38, 149, 15),
(39, 13, 43),
(40, 30, 44),
(41, 67, 44),
(42, 1, 46),
(43, 125, 47),
(44, 57, 49),
(45, 44, 50),
(46, 87, 50),
(47, 145, 51),
(48, 115, 52),
(49, 68, 52),
(50, 118, 53),
(51, 9, 54),
(52, 65, 55),
(53, 56, 57),
(54, 75, 58),
(55, 150, 59),
(56, 69, 60),
(57, 95, 60),
(58, 84, 58),
(59, 15, 61),
(60, 185, 58),
(61, 182, 62),
(62, 41, 63),
(63, 48, 64),
(64, 167, 65),
(65, 167, 11),
(66, 78, 66),
(67, 38, 67),
(68, 191, 68),
(69, 92, 69),
(70, 111, 70),
(71, 3, 71),
(72, 107, 71),
(73, 98, 71),
(74, 196, 72),
(75, 50, 73),
(76, 197, 74),
(77, 195, 75),
(78, 203, 76),
(79, 77, 77),
(80, 200, 78),
(81, 12, 79),
(82, 55, 80),
(83, 210, 81),
(84, 214, 82),
(85, 23, 83),
(86, 60, 84),
(87, 27, 85),
(88, 223, 87),
(89, 189, 89),
(90, 222, 91),
(91, 229, 91),
(92, 63, 92),
(93, 196, 11),
(94, 196, 12),
(95, 56, 11),
(96, 178, 93),
(97, 246, 96),
(98, 169, 97),
(99, 264, 98),
(100, 224, 100),
(101, 35, 100),
(102, 194, 101),
(103, 29, 102),
(104, 77, 13),
(105, 235, 103),
(106, 219, 104),
(107, 292, 105),
(108, 263, 106),
(109, 291, 91),
(110, 296, 91),
(111, 241, 91),
(112, 243, 91),
(113, 244, 91),
(114, 238, 91),
(115, 240, 91),
(116, 253, 91),
(117, 79, 108),
(118, 299, 109),
(119, 168, 111),
(120, 303, 91),
(121, 290, 112),
(122, 188, 113),
(123, 234, 114),
(124, 308, 115),
(125, 224, 42),
(126, 307, 117),
(127, 318, 118),
(129, 324, 122),
(130, 289, 60),
(131, 323, 55),
(132, 35, 55),
(133, 36, 55),
(134, 224, 55),
(135, 207, 55),
(136, 74, 123),
(138, 257, 124),
(139, 93, 124),
(140, 58, 124),
(141, 298, 91),
(142, 348, 126),
(143, 331, 126),
(144, 327, 127),
(145, 314, 128),
(146, 281, 130),
(147, 335, 131),
(148, 247, 91),
(149, 18, 133),
(150, 35, 134),
(151, 224, 134),
(153, 148, 136),
(154, 120, 137),
(156, 25, 138),
(157, 332, 139),
(158, 8, 139),
(159, 313, 139),
(160, 332, 140),
(161, 8, 140),
(162, 313, 140),
(163, 351, 141),
(164, 5, 142),
(165, 374, 114),
(166, 117, 144),
(167, 343, 144),
(168, 27, 145),
(169, 129, 146),
(170, 273, 147),
(171, 379, 148),
(172, 379, 151),
(173, 219, 152),
(174, 89, 152),
(175, 305, 152),
(176, 48, 152),
(177, 55, 152),
(178, 324, 152),
(179, 78, 152),
(180, 32, 152),
(181, 348, 152),
(182, 331, 152),
(183, 214, 152),
(184, 388, 153),
(185, 376, 154),
(186, 368, 155),
(187, 390, 156),
(188, 386, 158),
(189, 368, 152),
(191, 380, 173),
(192, 350, 174),
(194, 176, 176),
(195, 386, 152),
(196, 307, 152),
(197, 389, 152),
(198, 362, 73),
(199, 224, 152),
(200, 65, 152),
(201, 17, 152),
(202, 412, 178),
(203, 391, 152),
(204, 268, 180),
(205, 389, 181),
(206, 10, 182),
(207, 12, 152),
(208, 403, 13),
(209, 406, 13),
(210, 405, 13),
(211, 398, 13),
(212, 181, 183),
(213, 195, 152),
(214, 73, 152),
(215, 405, 152),
(216, 403, 152),
(217, 398, 152),
(218, 279, 184),
(219, 35, 185),
(220, 224, 185),
(221, 342, 91),
(222, 82, 21),
(223, 41, 152),
(224, 431, 186),
(225, 420, 187),
(226, 430, 188),
(227, 340, 189),
(228, 276, 18),
(229, 35, 152),
(230, 393, 194),
(231, 387, 194),
(232, 447, 190),
(233, 427, 195),
(234, 448, 197),
(235, 33, 152),
(236, 169, 152),
(238, 462, 20),
(239, 463, 91),
(240, 187, 123),
(241, 454, 203),
(242, 216, 204),
(243, 399, 205),
(245, 223, 207),
(246, 237, 207),
(247, 237, 152),
(248, 223, 152),
(249, 454, 219),
(250, 364, 220),
(251, 458, 221),
(252, 461, 222),
(253, 474, 223),
(254, 482, 224),
(255, 60, 152),
(256, 307, 226),
(257, 364, 152),
(258, 301, 228),
(259, 214, 229),
(260, 493, 230),
(261, 214, 231),
(262, 57, 152),
(263, 497, 232),
(264, 108, 233),
(265, 500, 234),
(266, 419, 235),
(267, 491, 236),
(268, 509, 237),
(269, 400, 13),
(270, 481, 239),
(271, 77, 229),
(272, 400, 229),
(274, 513, 242),
(275, 524, 244),
(276, 62, 223),
(277, 489, 246),
(278, 494, 247),
(279, 528, 248),
(285, 532, 249),
(286, 498, 250),
(287, 210, 152),
(293, 491, 253),
(294, 384, 72),
(299, 540, 254),
(300, 518, 255),
(302, 533, 256),
(303, 530, 257),
(304, 503, 258),
(305, 522, 259),
(306, 489, 152),
(307, 462, 152),
(308, 541, 260),
(309, 362, 152),
(310, 491, 152),
(311, 343, 152),
(312, 538, 261),
(313, 542, 263),
(314, 512, 264),
(315, 510, 265),
(316, 543, 266),
(318, 526, 91),
(319, 498, 152),
(321, 498, 229),
(328, 508, 268),
(329, 545, 269),
(330, 547, 13),
(331, 550, 271),
(332, 551, 272),
(333, 553, 273),
(334, 555, 271),
(335, 555, 22),
(336, 544, 13),
(337, 541, 276),
(338, 548, 277),
(339, 559, 278),
(340, 552, 254),
(341, 562, 279),
(342, 564, 280),
(343, 451, 280),
(344, 160, 282),
(345, 550, 152),
(346, 555, 152),
(347, 568, 283),
(348, 569, 284),
(349, 571, 286),
(350, 570, 287),
(352, 560, 288),
(354, 329, 289),
(355, 561, 290),
(357, 374, 152),
(358, 558, 293),
(360, 28, 11),
(361, 289, 11),
(363, 547, 294),
(364, 575, 295),
(368, 576, 297),
(369, 577, 298),
(370, 577, 299),
(372, 578, 300),
(374, 312, 135),
(375, 547, 301),
(376, 547, 302),
(377, 547, 303),
(378, 547, 304),
(379, 547, 305),
(380, 547, 306),
(381, 547, 307),
(382, 547, 308),
(383, 547, 309),
(385, 547, 311),
(386, 547, 312),
(387, 547, 313),
(388, 547, 314),
(389, 547, 315),
(390, 547, 316),
(391, 547, 317),
(392, 547, 318),
(393, 474, 152),
(399, 502, 320),
(401, 504, 152),
(402, 534, 323),
(403, 537, 324),
(404, 583, 326),
(405, 301, 327),
(406, 582, 328),
(407, 60, 329),
(408, 282, 329),
(409, 67, 329),
(410, 92, 329),
(411, 32, 329),
(412, 27, 329),
(413, 281, 329),
(414, 301, 329),
(416, 550, 190),
(417, 269, 332),
(418, 174, 333),
(419, 97, 152),
(420, 584, 334),
(421, 539, 335),
(422, 588, 336),
(423, 587, 337),
(424, 267, 339),
(427, 501, 341),
(428, 432, 342),
(429, 593, 343),
(430, 547, 190),
(431, 547, 344),
(432, 547, 152),
(433, 496, 204),
(434, 590, 345),
(436, 599, 350),
(437, 601, 352),
(438, 422, 152),
(439, 422, 29),
(442, 448, 353),
(443, 604, 242),
(453, 369, 355),
(454, 572, 344),
(460, 608, 30),
(461, 580, 358),
(462, 110, 360),
(463, 609, 361),
(464, 603, 362),
(476, 610, 363),
(477, 600, 364),
(478, 554, 71),
(480, 595, 366),
(481, 49, 366),
(487, 182, 368),
(488, 572, 301),
(489, 572, 302),
(490, 572, 303),
(491, 572, 304),
(492, 572, 305),
(493, 572, 306),
(494, 572, 307),
(495, 572, 308),
(496, 572, 309),
(497, 572, 315),
(498, 572, 311),
(499, 572, 312),
(500, 572, 313),
(501, 572, 314),
(502, 572, 294),
(503, 572, 316),
(504, 572, 317),
(505, 572, 318),
(506, 216, 358),
(507, 274, 369),
(508, 596, 371),
(509, 615, 372),
(510, 611, 109),
(511, 614, 260),
(524, 612, 386),
(525, 613, 392),
(526, 586, 271),
(530, 614, 398),
(531, 541, 398),
(532, 602, 13),
(533, 622, 13),
(536, 624, 401),
(537, 591, 403),
(538, 626, 404),
(539, 38, 405),
(540, 232, 406),
(542, 631, 228),
(543, 595, 226),
(545, 618, 407),
(546, 595, 152),
(547, 49, 152),
(548, 547, 408),
(550, 632, 410),
(551, 633, 411),
(552, 547, 412),
(553, 547, 413),
(554, 572, 413),
(555, 572, 412),
(556, 547, 414),
(557, 572, 414),
(558, 547, 415),
(559, 572, 415),
(562, 67, 152),
(563, 194, 152),
(564, 635, 418),
(567, 332, 421),
(568, 501, 421),
(575, 634, 424),
(576, 636, 425),
(579, 568, 426),
(583, 609, 427),
(584, 632, 427),
(585, 609, 428),
(586, 632, 428),
(587, 632, 361),
(589, 642, 429),
(601, 324, 433),
(602, 324, 432),
(603, 324, 431),
(604, 324, 430),
(605, 538, 434),
(643, 53, 436),
(647, 447, 240),
(648, 332, 240),
(653, 645, 438),
(654, 647, 439),
(655, 538, 440),
(657, 648, 384),
(666, 641, 242),
(669, 65, 240),
(676, 637, 422),
(680, 617, 429),
(681, 648, 422),
(688, 550, 429),
(689, 447, 429),
(690, 447, 365),
(691, 657, 365),
(692, 591, 240),
(693, 648, 240),
(694, 637, 240),
(695, 627, 240),
(696, 657, 240),
(697, 658, 254),
(700, 568, 240),
(702, 497, 429),
(706, 447, 422),
(708, 637, 190),
(709, 568, 429),
(710, 627, 422),
(711, 650, 422),
(712, 525, 447),
(713, 525, 454),
(714, 525, 453),
(715, 525, 452),
(716, 654, 448),
(717, 194, 240),
(718, 655, 449),
(719, 653, 447),
(720, 667, 456),
(723, 637, 423),
(724, 627, 423),
(725, 648, 429)
");

DB::unprepared(" 
INSERT INTO `customer_supplier` (`id`, `customer_id`, `supplier_id`) VALUES(2, 18, 8),(3, 229, 72),(4, 342, 74),(5, 229, 49),(7, 365, 51),(8, 63, 52),(9, 78, 53),(10, 63, 54),(11, 145, 55),(12, 366, 56),(13, 363, 57),(14, 234, 58),(15, 312, 59),(16, 234, 60),(17, 145, 61),(18, 7, 62),(19, 7, 63),(20, 214, 64),(21, 368, 65),(22, 1, 84),(23, 1, 16),(24, 1, 9),(25, 1, 67),(26, 2, 33),(27, 2, 178),(28, 2, 111),(29, 2, 272),(30, 2, 68),(31, 2, 99),(32, 2, 89),(33, 2, 11),(34, 2, 46),(35, 2, 93),(36, 2, 71),(37, 2, 162),(38, 2, 161),(39, 2, 160),(40, 2, 51),(41, 2, 16),(42, 2, 27),(43, 2, 30),(44, 2, 32),(45, 2, 95),(46, 2, 36),(47, 2, 18),(48, 2, 148),(49, 3, 166),(50, 3, 225),(51, 3, 51),(52, 5, 77),(53, 5, 10),(54, 5, 99),(55, 5, 91),(56, 5, 57),(57, 5, 130),(58, 7, 99)");
    }
}
