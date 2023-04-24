<?php

namespace App\Http\Controllers\Api;

use App\Helper\ApiRes;
use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function country(Request $req)
    {
        if ($req->action == "all") {
            $country = Country::all();

            if ($country) {
                return ApiRes::data("Country list", $country);
            } else {
                return ApiRes::error();
            }
        } elseif ($req->action == "states") {
            $country = Country::Where('name', $req->country)->first();
            $state = State::Where('country_id', $country->id)->get();
            if ($state) {
                return ApiRes::data("State List", $state);
            } else {
                return ApiRes::error();
            }
        } elseif ($req->action == "cities") {
            $state = State::Where('name', $req->state)->first();
            $city = City::Where('state_id', $state->id)->get();
            if ($city) {
                return ApiRes::data("City List", $city);
            } else {
                return ApiRes::error();
            }
        }
    }
}
