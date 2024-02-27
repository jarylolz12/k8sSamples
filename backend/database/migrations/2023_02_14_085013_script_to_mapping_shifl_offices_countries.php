<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\ShiflOffice;
use App\Country;
use Illuminate\Support\Facades\DB;

class ScriptToMappingShiflOfficesCountries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $ShiflOffice = ShiflOffice::selectRaw("*, (SELECT `id` FROM `countries` WHERE `name` = SUBSTRING(`shifl_offices`.`name`, 7)) AS `selected_county_id`")
            ->whereRaw("`id` NOT IN (SELECT `shifl_office_id` FROM `shifl_offices_countries`)")
            ->get();
        foreach ($ShiflOffice AS $key => $value){
            DB::table('shifl_offices_countries')->insert([
                'shifl_office_id' => $value->id,
                'country_id' => $value->selected_county_id]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shifl_offices_countries', function (Blueprint $table) {
            //
        });
    }
}
