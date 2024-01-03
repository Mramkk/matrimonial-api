<?php

namespace App\Http\Controllers\Api\NearMe;

use App\Helper\ApiRes;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ApiNearMeController extends Controller
{

    public function data()
    {
        $data = User::whereNotIn('uid', [auth()->user()->uid])->where('completed', '1')->where('country', auth()->user()->country)->where('state', auth()->user()->state)->with('img')->with('imglg')->with('shortlist')->with('interest')->with('visited')->get();
        return ApiRes::data("Datalist", $data);
    }
}
