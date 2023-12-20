<?php

namespace App\Http\Controllers\Api\Member;

use App\Helper\ApiRes;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ApiMemberController extends Controller
{
    public function data()
    {
        $data = User::whereNotIn('uid', [auth()->user()->uid])->where('completed', '1')->with('img')->with('imglg')->with('shortlist')->with('interest')->with('visited')->get();
        return ApiRes::data("Datalist", $data);
    }
}
