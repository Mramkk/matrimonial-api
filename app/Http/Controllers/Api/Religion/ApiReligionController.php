<?php

namespace App\Http\Controllers\Api\Religion;

use App\Helper\ApiRes;
use App\Http\Controllers\Controller;
use App\Models\Religion;
use Illuminate\Http\Request;

class ApiReligionController extends Controller
{
    public function save(Request $req)
    {
        $rel = new Religion();
        $rel->religion = $req->religion;
        $rel->caste = $req->caste;
        $status = $rel->save();
        if ($status) {
            return  ApiRes::success('Data Save Successfuly !');
        } else {
            return  ApiRes::error();
        }
    }

    public function data(Request $req)
    {
        $data = null;
        if ($req->religion != null || $req->religion != "") {
            $data = Religion::Where('religion', $req->religion)->get();
        } else {
            $data = Religion::select('*')
                ->groupBy('religion')
                ->get();
        }


        if ($data) {
            return ApiRes::data('Data List !', $data);
        } else {
            return ApiRes::error();
        }
    }
}
