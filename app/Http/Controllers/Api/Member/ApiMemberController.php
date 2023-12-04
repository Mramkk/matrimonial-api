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
        $data = User::with('img')->with('imgmd')->get();
        return ApiRes::data("Datalist", $data);
    }
}
