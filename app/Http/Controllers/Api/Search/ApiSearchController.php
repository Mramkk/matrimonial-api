<?php

namespace App\Http\Controllers\Api\Search;

use App\Helper\ApiRes;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ApiSearchController extends Controller
{
    public function data(Request $req)
    {
        $data = null;
        if ($req->age_from != "" && $req->age_to != "") {
            $data = User::whereNotIn('uid', [auth()->user()->uid])->where('completed', '1')->whereBetween('age', [$req->age_from, $req->age_to])->with('img')->with('imglg')->with('shortlist')->with('interest')->with('visited')->get();
        }
        if ($req->height_from != "" && $req->height_to != "") {
            $data = $data->whereBetween('height', [$req->height_from, $req->height_to]);
        }
        if ($req->marrital_status != "") {
            $data = $data->where('marrital_status', $req->marrital_status);
        }
        if ($req->marrital_status != "") {
            $data = $data->where('physical_status', $req->physical_status);
        }
        if ($req->religion != "") {
            $data = $data->where('religion', $req->religion);
        }
        if ($req->community != "") {
            $data = $data->where('community', $req->community);
        }
        if ($req->mother_tounge != "") {
            $data = $data->where('mother_tounge', $req->mother_tounge);
        }
        if ($req->country != "") {
            $data = $data->where('country', $req->country);
        }
        if ($req->state != "") {
            $data = $data->where('state', $req->state);
        }
        if ($req->city != "") {
            $data = $data->where('city', $req->city);
        }
        if ($req->highest_education != "") {
            $data = $data->where('highest_education', $req->highest_education);
        }
        if ($req->occupation != "") {
            $data = $data->where('occupation', $req->occupation);
        }
        if ($req->annual_income != "") {
            $data = $data->where('annual_income', $req->annual_income);
        }
        if ($req->diet != "") {
            $data = $data->where('diet', $req->diet);
        }
        if ($req->smoking != "") {
            $data = $data->where('smoking', $req->smoking);
        }
        if ($req->drinking != "") {
            $data = $data->where('drinking', $req->drinking);
        }
        if ($data != null) {
            return ApiRes::data("Datalist", $data->values()->all());
        }
        return ApiRes::failed("Data not found.");
    }
    public function dataBy(Request $req)
    {
        if ($req->search != "") {
            $data = User::whereNotIn('uid', [auth()->user()->uid])->orwhere('uid', 'like', '%' . $req->search . '%')->orwhere('first_name', 'like', '%' . $req->search . '%')->where('completed', '1')->with('img')->with('imglg')->with('shortlist')->with('interest')->with('visited')->get();
            return ApiRes::data("Datalist", $data);
        }
        return ApiRes::failed("Data not found.");
    }
}
