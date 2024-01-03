<?php

namespace App\Http\Controllers\Api\MyMatches;

use App\Helper\ApiRes;
use App\Http\Controllers\Controller;
use App\Models\PartnerPreference;
use App\Models\User;
use Illuminate\Http\Request;

class ApiMyMatchesController extends Controller
{
    public function data()
    {
        $prefe = PartnerPreference::where('uid', auth()->user()->uid)->first();
        if (auth()->user()->gender == "Male") {
            $data = User::where('completed', '1')->where('gender', 'Female')->with('img')->with('imglg')->with('shortlist')->with('interest')->with('visited')->get();
        } else {
            $data = User::where('completed', '1')->where('gender', 'Male')->with('img')->with('imglg')->with('shortlist')->with('interest')->with('visited')->get();
        }

        // $data = $data->whereIn('country', explode(',', $prefe->country));
        // $data = $data->whereIn('state', explode(',', $prefe->state));
        // $data = $data->whereIn('city', explode(',', $prefe->city));
        // $data =  $data->whereBetween('height', [$prefe->height_from, $prefe->height_to]);
        // $data = $data->whereBetween('age', [$prefe->age_from, $prefe->age_to]);
        // $data = $data->whereIn('marrital_status', explode(',', $prefe->marrital_status));

        // $data = $data->whereIn('mother_tounge', explode("\n", $prefe->mother_tounge));
        // $data = $data->whereIn('country', explode(',', $prefe->country));
        return ApiRes::data("Datalist", $data);
    }
}
