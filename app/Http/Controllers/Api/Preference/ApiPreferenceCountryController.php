<?php

namespace App\Http\Controllers\Api\Preference;

use App\Helper\ApiRes;
use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;

class ApiPreferenceCountryController extends Controller
{
    public function state(Request $req)
    {


        $countrys = Country::whereIn('name', $req->name)->get('id');
        $ids = [];
        foreach ($countrys as $key =>  $country) {
            $ids[$key]  = $country->id;
        }

        $state = State::whereIn('country_id', $ids)->get();


        if ($state) {
            return ApiRes::data("State List", $state);
        } else {
            return ApiRes::error();
        }
    }
    public function city(Request $req)
    {


        $states = State::whereIn('name', $req->name)->get('id');
        $ids = [];
        foreach ($states as $key =>  $state) {
            $ids[$key]  = $state->id;
        }

        $city = City::whereIn('state_id', $ids)->get();
        if ($city) {
            return ApiRes::data("City List", $city);
        } else {
            return ApiRes::error();
        }
    }
}
