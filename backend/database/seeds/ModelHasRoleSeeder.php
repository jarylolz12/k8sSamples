<?php

use Illuminate\Database\Seeder;

class ModelHasRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::unprepared("
        INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\AccountManager', 4),
(1, 'App\User', 4),
(1, 'App\User', 5),
(1, 'App\User', 6),
(1, 'App\AccountManager', 7),
(1, 'App\User', 7),
(1, 'App\User', 39),
(1, 'App\AccountManager', 40),
(1, 'App\User', 40),
(1, 'App\AccountManager', 41),
(1, 'App\User', 41),
(1, 'App\User', 45),
(1, 'App\User', 56),
(1, 'App\User', 88),
(1, 'App\User', 94),
(1, 'App\AccountManager', 95),
(1, 'App\User', 95),
(1, 'App\User', 99),
(1, 'App\User', 110),
(1, 'App\AccountManager', 116),
(1, 'App\User', 116),
(1, 'App\AccountManager', 119),
(1, 'App\User', 119),
(1, 'App\AccountManager', 120),
(1, 'App\User', 120),
(1, 'App\AccountManager', 125),
(1, 'App\User', 125),
(1, 'App\AccountManager', 132),
(1, 'App\User', 132),
(1, 'App\AccountManager', 143),
(1, 'App\User', 143),
(1, 'App\AccountManager', 149),
(1, 'App\User', 149),
(1, 'App\AccountManager', 150),
(1, 'App\User', 150),
(1, 'App\AccountManager', 177),
(1, 'App\User', 177),
(1, 'App\AccountManager', 191),
(1, 'App\User', 191),
(1, 'App\AccountManager', 192),
(1, 'App\User', 192),
(1, 'App\AccountManager', 193),
(1, 'App\User', 193),
(1, 'App\AccountManager', 196),
(1, 'App\User', 196),
(1, 'App\AccountManager', 198),
(1, 'App\User', 198),
(1, 'App\AccountManager', 199),
(1, 'App\User', 199),
(1, 'App\AccountManager', 200),
(1, 'App\User', 200),
(1, 'App\AccountManager', 201),
(1, 'App\User', 201),
(1, 'App\AccountManager', 208),
(1, 'App\User', 208),
(1, 'App\AccountManager', 209),
(1, 'App\User', 209),
(1, 'App\AccountManager', 210),
(1, 'App\User', 210),
(1, 'App\AccountManager', 211),
(1, 'App\User', 211),
(1, 'App\AccountManager', 212),
(1, 'App\User', 212),
(1, 'App\AccountManager', 213),
(1, 'App\User', 213),
(1, 'App\AccountManager', 214),
(1, 'App\User', 214),
(1, 'App\AccountManager', 215),
(1, 'App\User', 215),
(1, 'App\AccountManager', 216),
(1, 'App\User', 216),
(1, 'App\AccountManager', 217),
(1, 'App\User', 217),
(1, 'App\AccountManager', 218),
(1, 'App\User', 218),
(1, 'App\AccountManager', 238),
(1, 'App\User', 238),
(1, 'App\User', 243),
(1, 'App\AccountManager', 245),
(1, 'App\User', 245),
(1, 'App\AccountManager', 251),
(1, 'App\User', 251),
(1, 'App\AccountManager', 252),
(1, 'App\User', 252),
(1, 'App\AccountManager', 270),
(1, 'App\User', 270),
(1, 'App\AccountManager', 275),
(1, 'App\User', 275),
(1, 'App\AccountManager', 296),
(1, 'App\User', 296),
(1, 'App\AccountManager', 321),
(1, 'App\User', 321),
(1, 'App\AccountManager', 322),
(1, 'App\User', 322),
(1, 'App\User', 330),
(1, 'App\AccountManager', 340),
(1, 'App\User', 340),
(1, 'App\AccountManager', 346),
(1, 'App\User', 346),
(1, 'App\AccountManager', 347),
(1, 'App\User', 347),
(1, 'App\AccountManager', 351),
(1, 'App\User', 351),
(1, 'App\AccountManager', 356),
(1, 'App\User', 356),
(1, 'App\AccountManager', 357),
(1, 'App\User', 357),
(1, 'App\AccountManager', 375),
(1, 'App\User', 375),
(1, 'App\AccountManager', 376),
(1, 'App\User', 376),
(1, 'App\AccountManager', 377),
(1, 'App\User', 377),
(1, 'App\AccountManager', 378),
(1, 'App\User', 378),
(1, 'App\AccountManager', 379),
(1, 'App\User', 379),
(1, 'App\AccountManager', 380),
(1, 'App\User', 380),
(1, 'App\User', 382),
(1, 'App\User', 393),
(1, 'App\AccountManager', 395),
(1, 'App\User', 395),
(1, 'App\AccountManager', 397),
(1, 'App\User', 397),
(1, 'App\User', 399),
(1, 'App\AccountManager', 416),
(1, 'App\User', 416),
(1, 'App\AccountManager', 437),
(1, 'App\User', 437),
(1, 'App\AccountManager', 441),
(1, 'App\User', 441),
(1, 'App\AccountManager', 442),
(1, 'App\User', 442),
(1, 'App\AccountManager', 443),
(1, 'App\User', 443),
(1, 'App\AccountManager', 444),
(1, 'App\User', 444),
(1, 'App\AccountManager', 446),
(1, 'App\User', 446),
(1, 'App\AccountManager', 459),
(1, 'App\User', 459),
(2, 'App\User', 1),
(2, 'App\Admin', 2),
(2, 'App\User', 2),
(2, 'App\Admin', 3),
(2, 'App\User', 3),
(2, 'App\User', 8),
(2, 'App\User', 107),
(2, 'App\User', 129),
(2, 'App\Admin', 157),
(2, 'App\User', 157),
(2, 'App\Admin', 225),
(2, 'App\User', 225),
(2, 'App\Admin', 227),
(2, 'App\User', 227),
(2, 'App\Admin', 241),
(2, 'App\User', 241),
(2, 'App\Admin', 281),
(2, 'App\User', 281),
(2, 'App\Admin', 285),
(2, 'App\User', 285),
(2, 'App\Admin', 319),
(2, 'App\User', 319),
(2, 'App\Admin', 325),
(2, 'App\User', 325),
(2, 'App\Admin', 331),
(2, 'App\User', 331),
(2, 'App\Admin', 338),
(2, 'App\User', 338),
(2, 'App\User', 359),
(2, 'App\User', 370),
(2, 'App\Admin', 381),
(2, 'App\User', 381),
(2, 'App\Admin', 383),
(2, 'App\User', 383),
(2, 'App\User', 385),
(2, 'App\Admin', 400),
(2, 'App\User', 400),
(2, 'App\Admin', 402),
(2, 'App\User', 402),
(2, 'App\Admin', 417),
(2, 'App\User', 417),
(2, 'App\Admin', 419),
(2, 'App\User', 419),
(2, 'App\User', 420),
(2, 'App\Admin', 435),
(2, 'App\User', 435),
(2, 'App\Admin', 445),
(2, 'App\User', 445),
(2, 'App\User', 455),
(2, 'App\Admin', 457),
(2, 'App\User', 457),
(3, 'App\SaleAgent', 9),
(3, 'App\User', 9),
(3, 'App\SaleAgent', 10),
(3, 'App\User', 10),
(3, 'App\SaleAgent', 48),
(3, 'App\User', 48),
(3, 'App\SaleAgent', 86),
(3, 'App\User', 86),
(3, 'App\User', 90),
(3, 'App\SaleAgent', 121),
(3, 'App\User', 121),
(3, 'App\SaleAgent', 267),
(3, 'App\User', 267),
(4, 'App\CustomerAdmin', 11),
(4, 'App\User', 11),
(4, 'App\CustomerAdmin', 12),
(4, 'App\User', 12),
(4, 'App\CustomerAdmin', 13),
(4, 'App\User', 13),
(4, 'App\CustomerAdmin', 14),
(4, 'App\User', 14),
(4, 'App\CustomerAdmin', 15),
(4, 'App\User', 15),
(4, 'App\CustomerAdmin', 16),
(4, 'App\User', 16),
(4, 'App\CustomerAdmin', 17),
(4, 'App\User', 17),
(4, 'App\CustomerAdmin', 18),
(4, 'App\User', 18),
(4, 'App\CustomerAdmin', 19),
(4, 'App\User', 19),
(4, 'App\CustomerAdmin', 20),
(4, 'App\User', 20),
(4, 'App\CustomerAdmin', 21),
(4, 'App\User', 21),
(4, 'App\CustomerAdmin', 22),
(4, 'App\User', 22),
(4, 'App\CustomerAdmin', 23),
(4, 'App\User', 23),
(4, 'App\CustomerAdmin', 24),
(4, 'App\User', 24),
(4, 'App\CustomerAdmin', 25),
(4, 'App\User', 25),
(4, 'App\CustomerAdmin', 26),
(4, 'App\User', 26),
(4, 'App\CustomerAdmin', 27),
(4, 'App\User', 27),
(4, 'App\CustomerAdmin', 28),
(4, 'App\User', 28),
(4, 'App\CustomerAdmin', 29),
(4, 'App\User', 29),
(4, 'App\CustomerAdmin', 30),
(4, 'App\User', 30),
(4, 'App\CustomerAdmin', 31),
(4, 'App\User', 31),
(4, 'App\CustomerAdmin', 32),
(4, 'App\User', 32),
(4, 'App\CustomerAdmin', 33),
(4, 'App\User', 33),
(4, 'App\CustomerAdmin', 34),
(4, 'App\User', 34),
(4, 'App\CustomerAdmin', 35),
(4, 'App\User', 35),
(4, 'App\CustomerAdmin', 36),
(4, 'App\User', 36),
(4, 'App\CustomerAdmin', 37),
(4, 'App\User', 37),
(4, 'App\CustomerAdmin', 38),
(4, 'App\User', 38),
(4, 'App\CustomerAdmin', 42),
(4, 'App\User', 42),
(4, 'App\CustomerAdmin', 43),
(4, 'App\User', 43),
(4, 'App\CustomerAdmin', 44),
(4, 'App\User', 44),
(4, 'App\CustomerAdmin', 46),
(4, 'App\User', 46),
(4, 'App\CustomerAdmin', 47),
(4, 'App\User', 47),
(4, 'App\CustomerAdmin', 49),
(4, 'App\User', 49),
(4, 'App\CustomerAdmin', 50),
(4, 'App\User', 50),
(4, 'App\CustomerAdmin', 51),
(4, 'App\User', 51),
(4, 'App\CustomerAdmin', 52),
(4, 'App\User', 52),
(4, 'App\CustomerAdmin', 53),
(4, 'App\User', 53),
(4, 'App\CustomerAdmin', 54),
(4, 'App\User', 54),
(4, 'App\CustomerAdmin', 55),
(4, 'App\User', 55),
(4, 'App\CustomerAdmin', 57),
(4, 'App\User', 57),
(4, 'App\CustomerAdmin', 58),
(4, 'App\User', 58),
(4, 'App\CustomerAdmin', 59),
(4, 'App\User', 59),
(4, 'App\CustomerAdmin', 60),
(4, 'App\User', 60),
(4, 'App\CustomerAdmin', 61),
(4, 'App\User', 61),
(4, 'App\CustomerAdmin', 62),
(4, 'App\User', 62),
(4, 'App\CustomerAdmin', 63),
(4, 'App\User', 63),
(4, 'App\CustomerAdmin', 64),
(4, 'App\User', 64),
(4, 'App\CustomerAdmin', 65),
(4, 'App\User', 65),
(4, 'App\CustomerAdmin', 66),
(4, 'App\User', 66),
(4, 'App\CustomerAdmin', 67),
(4, 'App\User', 67),
(4, 'App\CustomerAdmin', 68),
(4, 'App\User', 68),
(4, 'App\CustomerAdmin', 69),
(4, 'App\User', 69),
(4, 'App\CustomerAdmin', 70),
(4, 'App\User', 70),
(4, 'App\CustomerAdmin', 71),
(4, 'App\User', 71),
(4, 'App\CustomerAdmin', 72),
(4, 'App\User', 72),
(4, 'App\CustomerAdmin', 73),
(4, 'App\User', 73),
(4, 'App\CustomerAdmin', 74),
(4, 'App\User', 74),
(4, 'App\CustomerAdmin', 75),
(4, 'App\User', 75),
(4, 'App\CustomerAdmin', 76),
(4, 'App\User', 76),
(4, 'App\CustomerAdmin', 77),
(4, 'App\User', 77),
(4, 'App\CustomerAdmin', 78),
(4, 'App\User', 78),
(4, 'App\CustomerAdmin', 79),
(4, 'App\User', 79),
(4, 'App\CustomerAdmin', 80),
(4, 'App\User', 80),
(4, 'App\CustomerAdmin', 81),
(4, 'App\User', 81),
(4, 'App\CustomerAdmin', 82),
(4, 'App\User', 82),
(4, 'App\CustomerAdmin', 83),
(4, 'App\User', 83),
(4, 'App\CustomerAdmin', 84),
(4, 'App\User', 84),
(4, 'App\CustomerAdmin', 85),
(4, 'App\User', 85),
(4, 'App\CustomerAdmin', 87),
(4, 'App\User', 87),
(4, 'App\CustomerAdmin', 89),
(4, 'App\User', 89),
(4, 'App\CustomerAdmin', 91),
(4, 'App\User', 91),
(4, 'App\CustomerAdmin', 92),
(4, 'App\User', 92),
(4, 'App\CustomerAdmin', 93),
(4, 'App\User', 93),
(4, 'App\CustomerAdmin', 96),
(4, 'App\User', 96),
(4, 'App\CustomerAdmin', 97),
(4, 'App\User', 97),
(4, 'App\CustomerAdmin', 98),
(4, 'App\User', 98),
(4, 'App\CustomerAdmin', 100),
(4, 'App\User', 100),
(4, 'App\CustomerAdmin', 101),
(4, 'App\User', 101),
(4, 'App\CustomerAdmin', 102),
(4, 'App\User', 102),
(4, 'App\CustomerAdmin', 103),
(4, 'App\User', 103),
(4, 'App\CustomerAdmin', 104),
(4, 'App\User', 104),
(4, 'App\CustomerAdmin', 105),
(4, 'App\User', 105),
(4, 'App\CustomerAdmin', 106),
(4, 'App\User', 106),
(4, 'App\CustomerAdmin', 108),
(4, 'App\User', 108),
(4, 'App\CustomerAdmin', 109),
(4, 'App\User', 109),
(4, 'App\CustomerAdmin', 111),
(4, 'App\User', 111),
(4, 'App\CustomerAdmin', 112),
(4, 'App\User', 112),
(4, 'App\CustomerAdmin', 113),
(4, 'App\User', 113),
(4, 'App\CustomerAdmin', 114),
(4, 'App\User', 114),
(4, 'App\CustomerAdmin', 115),
(4, 'App\User', 115),
(4, 'App\CustomerAdmin', 117),
(4, 'App\User', 117),
(4, 'App\CustomerAdmin', 118),
(4, 'App\User', 118),
(4, 'App\CustomerAdmin', 122),
(4, 'App\User', 122),
(4, 'App\CustomerAdmin', 123),
(4, 'App\User', 123),
(4, 'App\CustomerAdmin', 124),
(4, 'App\User', 124),
(4, 'App\CustomerAdmin', 126),
(4, 'App\User', 126),
(4, 'App\CustomerAdmin', 127),
(4, 'App\User', 127),
(4, 'App\CustomerAdmin', 128),
(4, 'App\User', 128),
(4, 'App\CustomerAdmin', 130),
(4, 'App\User', 130),
(4, 'App\CustomerAdmin', 131),
(4, 'App\User', 131),
(4, 'App\CustomerAdmin', 133),
(4, 'App\User', 133),
(4, 'App\CustomerAdmin', 134),
(4, 'App\User', 134),
(4, 'App\CustomerAdmin', 135),
(4, 'App\User', 135),
(4, 'App\CustomerAdmin', 136),
(4, 'App\User', 136),
(4, 'App\CustomerAdmin', 137),
(4, 'App\User', 137),
(4, 'App\CustomerAdmin', 138),
(4, 'App\User', 138),
(4, 'App\CustomerAdmin', 139),
(4, 'App\User', 139),
(4, 'App\CustomerAdmin', 140),
(4, 'App\User', 140),
(4, 'App\CustomerAdmin', 141),
(4, 'App\User', 141),
(4, 'App\CustomerAdmin', 142),
(4, 'App\User', 142),
(4, 'App\CustomerAdmin', 144),
(4, 'App\User', 144),
(4, 'App\CustomerAdmin', 145),
(4, 'App\User', 145),
(4, 'App\CustomerAdmin', 146),
(4, 'App\User', 146),
(4, 'App\CustomerAdmin', 147),
(4, 'App\User', 147),
(4, 'App\CustomerAdmin', 148),
(4, 'App\User', 148),
(4, 'App\CustomerAdmin', 151),
(4, 'App\User', 151),
(4, 'App\CustomerAdmin', 152),
(4, 'App\User', 152),
(4, 'App\CustomerAdmin', 153),
(4, 'App\User', 153),
(4, 'App\CustomerAdmin', 154),
(4, 'App\User', 154),
(4, 'App\CustomerAdmin', 155),
(4, 'App\User', 155),
(4, 'App\CustomerAdmin', 156),
(4, 'App\User', 156),
(4, 'App\CustomerAdmin', 158),
(4, 'App\User', 158),
(4, 'App\CustomerAdmin', 173),
(4, 'App\User', 173),
(4, 'App\CustomerAdmin', 174),
(4, 'App\User', 174),
(4, 'App\CustomerAdmin', 175),
(4, 'App\User', 175),
(4, 'App\CustomerAdmin', 176),
(4, 'App\User', 176),
(4, 'App\CustomerAdmin', 178),
(4, 'App\User', 178),
(4, 'App\CustomerAdmin', 180),
(4, 'App\User', 180),
(4, 'App\CustomerAdmin', 181),
(4, 'App\User', 181),
(4, 'App\CustomerAdmin', 182),
(4, 'App\User', 182),
(4, 'App\CustomerAdmin', 183),
(4, 'App\User', 183),
(4, 'App\CustomerAdmin', 184),
(4, 'App\User', 184),
(4, 'App\CustomerAdmin', 185),
(4, 'App\User', 185),
(4, 'App\CustomerAdmin', 186),
(4, 'App\User', 186),
(4, 'App\CustomerAdmin', 187),
(4, 'App\User', 187),
(4, 'App\CustomerAdmin', 188),
(4, 'App\User', 188),
(4, 'App\CustomerAdmin', 189),
(4, 'App\User', 189),
(4, 'App\CustomerAdmin', 190),
(4, 'App\User', 190),
(4, 'App\CustomerAdmin', 194),
(4, 'App\User', 194),
(4, 'App\CustomerAdmin', 195),
(4, 'App\User', 195),
(4, 'App\CustomerAdmin', 197),
(4, 'App\User', 197),
(4, 'App\CustomerAdmin', 202),
(4, 'App\User', 202),
(4, 'App\CustomerAdmin', 203),
(4, 'App\User', 203),
(4, 'App\CustomerAdmin', 204),
(4, 'App\User', 204),
(4, 'App\CustomerAdmin', 205),
(4, 'App\User', 205),
(4, 'App\User', 206),
(4, 'App\CustomerAdmin', 207),
(4, 'App\User', 207),
(4, 'App\CustomerAdmin', 219),
(4, 'App\User', 219),
(4, 'App\CustomerAdmin', 220),
(4, 'App\User', 220),
(4, 'App\CustomerAdmin', 221),
(4, 'App\User', 221),
(4, 'App\CustomerAdmin', 222),
(4, 'App\User', 222),
(4, 'App\CustomerAdmin', 223),
(4, 'App\User', 223),
(4, 'App\CustomerAdmin', 224),
(4, 'App\User', 224),
(4, 'App\CustomerAdmin', 226),
(4, 'App\User', 226),
(4, 'App\CustomerAdmin', 228),
(4, 'App\User', 228),
(4, 'App\CustomerAdmin', 229),
(4, 'App\User', 229),
(4, 'App\CustomerAdmin', 230),
(4, 'App\User', 230),
(4, 'App\CustomerAdmin', 231),
(4, 'App\User', 231),
(4, 'App\CustomerAdmin', 232),
(4, 'App\User', 232),
(4, 'App\CustomerAdmin', 233),
(4, 'App\User', 233),
(4, 'App\CustomerAdmin', 234),
(4, 'App\User', 234),
(4, 'App\CustomerAdmin', 235),
(4, 'App\User', 235),
(4, 'App\CustomerAdmin', 236),
(4, 'App\User', 236),
(4, 'App\CustomerAdmin', 237),
(4, 'App\User', 237),
(4, 'App\CustomerAdmin', 239),
(4, 'App\User', 239),
(4, 'App\CustomerAdmin', 240),
(4, 'App\User', 240),
(4, 'App\CustomerAdmin', 242),
(4, 'App\User', 242),
(4, 'App\CustomerAdmin', 244),
(4, 'App\User', 244),
(4, 'App\CustomerAdmin', 246),
(4, 'App\User', 246),
(4, 'App\CustomerAdmin', 247),
(4, 'App\User', 247),
(4, 'App\CustomerAdmin', 248),
(4, 'App\User', 248),
(4, 'App\CustomerAdmin', 249),
(4, 'App\User', 249),
(4, 'App\CustomerAdmin', 250),
(4, 'App\User', 250),
(4, 'App\CustomerAdmin', 253),
(4, 'App\User', 253),
(4, 'App\CustomerAdmin', 254),
(4, 'App\User', 254),
(4, 'App\CustomerAdmin', 255),
(4, 'App\User', 255),
(4, 'App\CustomerAdmin', 256),
(4, 'App\User', 256),
(4, 'App\CustomerAdmin', 257),
(4, 'App\User', 257),
(4, 'App\CustomerAdmin', 258),
(4, 'App\User', 258),
(4, 'App\CustomerAdmin', 259),
(4, 'App\User', 259),
(4, 'App\CustomerAdmin', 260),
(4, 'App\User', 260),
(4, 'App\CustomerAdmin', 261),
(4, 'App\User', 261),
(4, 'App\CustomerAdmin', 263),
(4, 'App\User', 263),
(4, 'App\CustomerAdmin', 264),
(4, 'App\User', 264),
(4, 'App\CustomerAdmin', 265),
(4, 'App\User', 265),
(4, 'App\CustomerAdmin', 266),
(4, 'App\User', 266),
(4, 'App\CustomerAdmin', 268),
(4, 'App\User', 268),
(4, 'App\CustomerAdmin', 269),
(4, 'App\User', 269),
(4, 'App\CustomerAdmin', 271),
(4, 'App\User', 271),
(4, 'App\CustomerAdmin', 272),
(4, 'App\User', 272),
(4, 'App\CustomerAdmin', 273),
(4, 'App\User', 273),
(4, 'App\User', 274),
(4, 'App\CustomerAdmin', 276),
(4, 'App\User', 276),
(4, 'App\CustomerAdmin', 277),
(4, 'App\User', 277),
(4, 'App\CustomerAdmin', 278),
(4, 'App\User', 278),
(4, 'App\CustomerAdmin', 279),
(4, 'App\User', 279),
(4, 'App\CustomerAdmin', 280),
(4, 'App\User', 280),
(4, 'App\CustomerAdmin', 282),
(4, 'App\User', 282),
(4, 'App\CustomerAdmin', 283),
(4, 'App\User', 283),
(4, 'App\CustomerAdmin', 284),
(4, 'App\User', 284),
(4, 'App\CustomerAdmin', 286),
(4, 'App\User', 286),
(4, 'App\CustomerAdmin', 287),
(4, 'App\User', 287),
(4, 'App\CustomerAdmin', 288),
(4, 'App\User', 288),
(4, 'App\CustomerAdmin', 289),
(4, 'App\User', 289),
(4, 'App\CustomerAdmin', 290),
(4, 'App\User', 290),
(4, 'App\CustomerAdmin', 293),
(4, 'App\User', 293),
(4, 'App\CustomerAdmin', 294),
(4, 'App\User', 294),
(4, 'App\CustomerAdmin', 295),
(4, 'App\User', 295),
(4, 'App\CustomerAdmin', 297),
(4, 'App\User', 297),
(4, 'App\CustomerAdmin', 298),
(4, 'App\User', 298),
(4, 'App\CustomerAdmin', 299),
(4, 'App\User', 299),
(4, 'App\CustomerAdmin', 300),
(4, 'App\User', 300),
(4, 'App\CustomerAdmin', 301),
(4, 'App\User', 301),
(4, 'App\CustomerAdmin', 302),
(4, 'App\User', 302),
(4, 'App\CustomerAdmin', 303),
(4, 'App\User', 303),
(4, 'App\CustomerAdmin', 304),
(4, 'App\User', 304),
(4, 'App\CustomerAdmin', 305),
(4, 'App\User', 305),
(4, 'App\CustomerAdmin', 306),
(4, 'App\User', 306),
(4, 'App\CustomerAdmin', 307),
(4, 'App\User', 307),
(4, 'App\CustomerAdmin', 308),
(4, 'App\User', 308),
(4, 'App\CustomerAdmin', 309),
(4, 'App\User', 309),
(4, 'App\User', 310),
(4, 'App\CustomerAdmin', 311),
(4, 'App\User', 311),
(4, 'App\CustomerAdmin', 312),
(4, 'App\User', 312),
(4, 'App\CustomerAdmin', 313),
(4, 'App\User', 313),
(4, 'App\CustomerAdmin', 314),
(4, 'App\User', 314),
(4, 'App\CustomerAdmin', 315),
(4, 'App\User', 315),
(4, 'App\CustomerAdmin', 316),
(4, 'App\User', 316),
(4, 'App\CustomerAdmin', 317),
(4, 'App\User', 317),
(4, 'App\CustomerAdmin', 318),
(4, 'App\User', 318),
(4, 'App\CustomerAdmin', 320),
(4, 'App\User', 320),
(4, 'App\CustomerAdmin', 323),
(4, 'App\User', 323),
(4, 'App\CustomerAdmin', 324),
(4, 'App\User', 324),
(4, 'App\CustomerAdmin', 326),
(4, 'App\User', 326),
(4, 'App\CustomerAdmin', 327),
(4, 'App\User', 327),
(4, 'App\CustomerAdmin', 328),
(4, 'App\User', 328),
(4, 'App\CustomerAdmin', 329),
(4, 'App\User', 329),
(4, 'App\CustomerAdmin', 332),
(4, 'App\User', 332),
(4, 'App\CustomerAdmin', 333),
(4, 'App\User', 333),
(4, 'App\CustomerAdmin', 334),
(4, 'App\User', 334),
(4, 'App\CustomerAdmin', 335),
(4, 'App\User', 335),
(4, 'App\CustomerAdmin', 336),
(4, 'App\User', 336),
(4, 'App\CustomerAdmin', 337),
(4, 'App\User', 337),
(4, 'App\CustomerAdmin', 339),
(4, 'App\User', 339),
(4, 'App\CustomerAdmin', 341),
(4, 'App\User', 341),
(4, 'App\CustomerAdmin', 342),
(4, 'App\User', 342),
(4, 'App\CustomerAdmin', 343),
(4, 'App\User', 343),
(4, 'App\CustomerAdmin', 344),
(4, 'App\User', 344),
(4, 'App\CustomerAdmin', 345),
(4, 'App\User', 345),
(4, 'App\CustomerAdmin', 350),
(4, 'App\User', 350),
(4, 'App\CustomerAdmin', 352),
(4, 'App\User', 352),
(4, 'App\CustomerAdmin', 353),
(4, 'App\User', 353),
(4, 'App\CustomerAdmin', 355),
(4, 'App\User', 355),
(4, 'App\CustomerAdmin', 358),
(4, 'App\User', 358),
(4, 'App\CustomerAdmin', 360),
(4, 'App\User', 360),
(4, 'App\CustomerAdmin', 361),
(4, 'App\User', 361),
(4, 'App\CustomerAdmin', 362),
(4, 'App\User', 362),
(4, 'App\CustomerAdmin', 363),
(4, 'App\User', 363),
(4, 'App\CustomerAdmin', 364),
(4, 'App\User', 364),
(4, 'App\CustomerAdmin', 365),
(4, 'App\User', 365),
(4, 'App\CustomerAdmin', 366),
(4, 'App\User', 366),
(4, 'App\User', 367),
(4, 'App\CustomerAdmin', 368),
(4, 'App\User', 368),
(4, 'App\CustomerAdmin', 369),
(4, 'App\User', 369),
(4, 'App\CustomerAdmin', 371),
(4, 'App\User', 371),
(4, 'App\CustomerAdmin', 372),
(4, 'App\User', 372),
(4, 'App\User', 373),
(4, 'App\User', 374),
(4, 'App\CustomerAdmin', 384),
(4, 'App\User', 384),
(4, 'App\CustomerAdmin', 386),
(4, 'App\User', 386),
(4, 'App\CustomerAdmin', 387),
(4, 'App\User', 387),
(4, 'App\CustomerAdmin', 388),
(4, 'App\User', 388),
(4, 'App\CustomerAdmin', 389),
(4, 'App\User', 389),
(4, 'App\CustomerAdmin', 390),
(4, 'App\User', 390),
(4, 'App\CustomerAdmin', 391),
(4, 'App\User', 391),
(4, 'App\CustomerAdmin', 392),
(4, 'App\User', 392),
(4, 'App\CustomerAdmin', 398),
(4, 'App\User', 398),
(4, 'App\CustomerAdmin', 401),
(4, 'App\User', 401),
(4, 'App\CustomerAdmin', 403),
(4, 'App\User', 403),
(4, 'App\CustomerAdmin', 404),
(4, 'App\User', 404),
(4, 'App\CustomerAdmin', 405),
(4, 'App\User', 405),
(4, 'App\CustomerAdmin', 406),
(4, 'App\User', 406),
(4, 'App\CustomerAdmin', 407),
(4, 'App\User', 407),
(4, 'App\CustomerAdmin', 408),
(4, 'App\User', 408),
(4, 'App\User', 409),
(4, 'App\CustomerAdmin', 410),
(4, 'App\User', 410),
(4, 'App\CustomerAdmin', 411),
(4, 'App\User', 411),
(4, 'App\CustomerAdmin', 412),
(4, 'App\User', 412),
(4, 'App\CustomerAdmin', 413),
(4, 'App\User', 413),
(4, 'App\CustomerAdmin', 414),
(4, 'App\User', 414),
(4, 'App\CustomerAdmin', 415),
(4, 'App\User', 415),
(4, 'App\CustomerAdmin', 418),
(4, 'App\User', 418),
(4, 'App\CustomerAdmin', 421),
(4, 'App\User', 421),
(4, 'App\CustomerAdmin', 422),
(4, 'App\User', 422),
(4, 'App\CustomerAdmin', 423),
(4, 'App\User', 423),
(4, 'App\CustomerAdmin', 424),
(4, 'App\User', 424),
(4, 'App\CustomerAdmin', 425),
(4, 'App\User', 425),
(4, 'App\CustomerAdmin', 426),
(4, 'App\User', 426),
(4, 'App\CustomerAdmin', 427),
(4, 'App\User', 427),
(4, 'App\CustomerAdmin', 428),
(4, 'App\User', 428),
(4, 'App\CustomerAdmin', 429),
(4, 'App\User', 429),
(4, 'App\CustomerAdmin', 430),
(4, 'App\User', 430),
(4, 'App\CustomerAdmin', 431),
(4, 'App\User', 431),
(4, 'App\CustomerAdmin', 432),
(4, 'App\User', 432),
(4, 'App\CustomerAdmin', 433),
(4, 'App\User', 433),
(4, 'App\CustomerAdmin', 434),
(4, 'App\User', 434),
(4, 'App\CustomerAdmin', 436),
(4, 'App\User', 436),
(4, 'App\CustomerAdmin', 438),
(4, 'App\User', 438),
(4, 'App\CustomerAdmin', 439),
(4, 'App\User', 439),
(4, 'App\CustomerAdmin', 440),
(4, 'App\User', 440),
(4, 'App\CustomerAdmin', 447),
(4, 'App\User', 447),
(4, 'App\CustomerAdmin', 448),
(4, 'App\User', 448),
(4, 'App\CustomerAdmin', 449),
(4, 'App\User', 449),
(4, 'App\CustomerAdmin', 450),
(4, 'App\User', 450),
(4, 'App\CustomerAdmin', 451),
(4, 'App\User', 451),
(4, 'App\CustomerAdmin', 452),
(4, 'App\User', 452),
(4, 'App\CustomerAdmin', 453),
(4, 'App\User', 453),
(4, 'App\CustomerAdmin', 454),
(4, 'App\User', 454),
(4, 'App\User', 455),
(4, 'App\CustomerAdmin', 456),
(4, 'App\User', 456),
(4, 'App\CustomerAdmin', 458),
(4, 'App\User', 458),
(4, 'App\CustomerAdmin', 460),
(4, 'App\User', 460);
        ");

        DB::unprepared("
          INSERT INTO `model_has_permissions` (`permission_id`, `model_type`, `model_id`) VALUES 
          ('1', 'type model test 1', '4'),
          ('1', 'type model test 2', '5'),
          ('1', 'type model test 3', '6'); 
        ");

    }
}
