<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PragmaRX\Countries\Package\Countries;

class CountryStateCityController extends Controller
{
    private $countryObject;

    public function __construct()
    {
        $this->countryObject = new Countries();
    }

    /**
     * Display list of Country
     *
     * @response status=401 {
     *      "message": "The token has been expired, Please reload the page",
     *      "error": "UnAuthenticated",
     *      "status": 401
     * }
     *
     * @return \Illuminate\Http\JsonResponse
     *
     */


    public function getCountries()
    {
        $simplifiedCountries = $this->countryObject->all()->pluck('name.common')->toArray();
        sort($simplifiedCountries);
        return response()->json($simplifiedCountries);
    }

    /**
     * Display list of Country with Flag
     *
     * @response status=401 {
     *      "message": "The token has been expired, Please reload the page",
     *      "error": "UnAuthenticated",
     *      "status": 401
     * }
     *
     * @return \Illuminate\Http\JsonResponse
     *
     */

    public function getCountriesWIthFlag()
    {
        $simplifiedCountriesCode = $this->countryObject->all()->pluck('cca2')->toArray();
        $simplifiedCountries = $this->countryObject->all()->pluck('name.common')->toArray();
        $countries = array_combine($simplifiedCountriesCode, $simplifiedCountries);
        return response()->json($countries);
    }

    /**
     *
     * Display Country State
     *
     * @response status=401 {
     *      "message": "The token has been expired, Please reload the page",
     *      "error": "UnAuthenticated",
     *      "status": 401
     * }
     *
     * @param $country
     * @return \Illuminate\Http\JsonResponse
     *
     *
     */
    public function getCountryStates($country)
    {
        $simplifiedStates = $this->countryObject->where('name.common', $country)->first()->hydrate('states')->states->sortBy('name')->pluck('name')->toArray();
        return response()->json($simplifiedStates);
    }

    /**
     *
     * Display State Cities
     *
     * @response status=401 {
     *      "message": "The token has been expired, Please reload the page",
     *      "error": "UnAuthenticated",
     *      "status": 401
     * }
     *
     * @param $country
     * @param $state
     * @return \Illuminate\Http\JsonResponse
     *
     */

    public function getStateCities($country, $state)
    {
        $simplifiedCities = $this->countryObject->where('name.common', $country)->first()->hydrate('cities')->cities->where('adm1name', $state)->sortBy('name')->pluck('name')->toArray();;
        return response()->json($simplifiedCities);
    }
}
