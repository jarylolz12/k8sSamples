<?php

use Illuminate\Database\Seeder;

class CarrierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        DB::table('carrier_types')->insert([
            [ 'name' => 'Air'],
            [ 'name' => 'Ocean']
        ]);


        DB::table('carriers')->insert([
            [ 'name' => 'Cosco', 'carrier_type_id' => 2],
            [ 'name' => 'Zim', 'carrier_type_id' => 2],
            [ 'name' => 'Wanhai', 'carrier_type_id' => 2],
            [ 'name' => 'Evergreen', 'carrier_type_id' => 2],
            [ 'name' => 'SM LINE', 'carrier_type_id' => 2],
            [ 'name' => 'OOCL', 'carrier_type_id' => 2],
            [ 'name' => 'ONE', 'carrier_type_id' => 2],
            [ 'name' => 'YML', 'carrier_type_id' => 2],
            [ 'name' => 'APL', 'carrier_type_id' => 2],
            [ 'name' => 'HPL', 'carrier_type_id' => 2],
            [ 'name' => 'MSC', 'carrier_type_id' => 2],
            [ 'name' => 'MSK', 'carrier_type_id' => 2],
            [ 'name' => 'HMM', 'carrier_type_id' => 2],
            [ 'name' => 'MATSON', 'carrier_type_id' => 2],
            [ 'name' => 'CMA', 'carrier_type_id' => 2],
            [ 'name' => 'ACL', 'carrier_type_id' => 2],
            [ 'name' => 'HAMBURG SUD', 'carrier_type_id' => 2],
            [ 'name' => 'Air China Cargo', 'carrier_type_id' => 1],
            [ 'name' => 'Cargolux', 'carrier_type_id' => 1],
            [ 'name' => 'All Nippon Airways', 'carrier_type_id' => 1],
            [ 'name' => 'Qantas Freight', 'carrier_type_id' => 1],
            [ 'name' => 'DHL AVIATION', 'carrier_type_id' => 1],
            [ 'name' => 'Korean Air', 'carrier_type_id' => 1],
            [ 'name' => 'KLM', 'carrier_type_id' => 1],
            [ 'name' => 'Aeroflot Cargo', 'carrier_type_id' => 1],
            [ 'name' => 'Hainan Airlines', 'carrier_type_id' => 1],
            [ 'name' => 'Turkish Airlines', 'carrier_type_id' => 1],
            [ 'name' => 'Lot Polish', 'carrier_type_id' => 1],
            [ 'name' => 'KALITTA AIR', 'carrier_type_id' => 1],
            [ 'name' => 'Air France', 'carrier_type_id' => 1],
            [ 'name' => 'Hainan Airlines', 'carrier_type_id' => 1],
            [ 'name' => 'Swiss World Cargo', 'carrier_type_id' => 1],
            [ 'name' => 'VIRGIN ATLANTIC AIRWAYS', 'carrier_type_id' => 1],
            [ 'name' => 'Silk Way West Airlines', 'carrier_type_id' => 1],
            [ 'name' => 'China Southern', 'carrier_type_id' => 1],
            [ 'name' => 'Atlas Air', 'carrier_type_id' => 1],
            [ 'name' => 'Sky Lease Cargo', 'carrier_type_id' => 1],
            [ 'name' => 'China Eastern Airlines', 'carrier_type_id' => 1],
            [ 'name' => 'CATHAY PACIFIC AIRWAYS', 'carrier_type_id' => 1],
            [ 'name' => 'CHINA AIRLINES LTD', 'carrier_type_id' => 1],
            [ 'name' => 'United Airlines', 'carrier_type_id' => 1],
            [ 'name' => 'United Cargo', 'carrier_type_id' => 1],
            [ 'name' => 'Turkon Line', 'carrier_type_id' => 2],
            [ 'name' => 'China Southern Airlines', 'carrier_type_id' => 1],
            [ 'name' => 'Skylease Cargo', 'carrier_type_id' => 1],
            [ 'name' => 'Cargolux Italia', 'carrier_type_id' => 1],
            [ 'name' => 'HAPAG LLOYD', 'carrier_type_id' => 2],
            [ 'name' => 'AeroUnion', 'carrier_type_id' => 1],
            [ 'name' => 'Japan Airlines (JL) / JAL Cargo', 'carrier_type_id' => 1],
            [ 'name' => 'CONTAINER MOVEMENT (BOMBAY) TRANSPORT PVT. LTD,', 'carrier_type_id' => 1],
            [ 'name' => 'Delta Airlines', 'carrier_type_id' => 1],
            [ 'name' => 'MAERSK', 'carrier_type_id' => 2],
            [ 'name' => 'SINGAPORE AIRLINES', 'carrier_type_id' => 1],
            [ 'name' => 'QATAR AIRWAYS', 'carrier_type_id' => 1],
            [ 'name' => 'SPICE JET', 'carrier_type_id' => 1],
            [ 'name' => 'FEDEX', 'carrier_type_id' => 1],
            [ 'name' => 'ASIA MARITIME PACIFIC (SHANGHAI) LI', 'carrier_type_id' => 2],
            [ 'name' => 'ASL Airlines', 'carrier_type_id' => 2],
            [ 'name' => 'CUL', 'carrier_type_id' => 2],
            [ 'name' => 'SEA LEAD', 'carrier_type_id' => 2],
            [ 'name' => 'AIR INDIA', 'carrier_type_id' =>1],
            [ 'name' => 'EUROPEAN AIR TRANSPORT', 'carrier_type_id' =>1],
            [ 'name' => 'SJJ', 'carrier_type_id' =>2],
            [ 'name' => 'Lufthansa Cargo(LH)', 'carrier_type_id' =>1],
            [ 'name' => 'CORTEN', 'carrier_type_id' =>2],
            [ 'name' => 'TEAMGLOBAL', 'carrier_type_id' =>2],
            [ 'name' => 'American Airlines cargo', 'carrier_type_id' =>1],
            [ 'name' => 'Seaboard Marine', 'carrier_type_id' =>2],
            [ 'name' => 'Alitalia Cargo', 'carrier_type_id' =>1],
            [ 'name' => 'GILL SHIPPING SERVICES PVT. LTD', 'carrier_type_id' =>2],
            [ 'name' => 'KLM Airlines', 'carrier_type_id' =>1],
            [ 'name' => 'Copa Airlines', 'carrier_type_id' =>1],
            [ 'name' => 'Turkish Airlines / Choice Airlines (NEWARK)', 'carrier_type_id' =>1],
            [ 'name' => 'IAG Cargo / Iberia Airlines', 'carrier_type_id' =>1],
            [ 'name' => 'AIREXPRESS / DHLGLOBAL FORWARDING', 'carrier_type_id' =>1],
            [ 'name' => 'MNG Airlines Cargo', 'carrier_type_id' =>1],
            [ 'name' => 'GALLIARD', 'carrier_type_id' =>1],
            [ 'name' => 'THAI AIRLINES', 'carrier_type_id' =>1],
            [ 'name' => 'MASTER INTERNATIONAL NINGBO', 'carrier_type_id' => 2],
            [ 'name' => 'Seth Shipping Ltd', 'carrier_type_id' =>2],
            [ 'name' => 'UNITED FREIGHT MANAGEMENT', 'carrier_type_id' =>2],
            [ 'name' => 'Neos Cargo', 'carrier_type_id' =>1]
        ]);

    }
}
