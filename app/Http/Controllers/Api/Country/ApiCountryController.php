<?php

namespace App\Http\Controllers\Api\Country;

use App\Helper\ApiRes;
use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;

class ApiCountryController extends Controller
{

    public function country()
    {
        $data = Country::all();
        if ($data) {
            return ApiRes::data("datalist", $data);
        } else {
            return ApiRes::dataNotFound();
        }
        // return ApiRes::error();
    }
    public function state(Request $req)
    {
        $country = Country::Where('name', $req->country)->first();
        $state = State::Where('country_id', $country->id)->get();
        if ($state) {
            return ApiRes::data("State List", $state);
        } else {
            return ApiRes::error();
        }
    }
    public function city(Request $req)
    {
        $state = State::Where('name', $req->state)->first();
        $city = City::Where('state_id', $state->id)->get();
        if ($city) {
            return ApiRes::data("City List", $city);
        } else {
            return ApiRes::error();
        }
    }
}
