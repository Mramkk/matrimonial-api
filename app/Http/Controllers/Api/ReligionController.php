<?php

namespace App\Http\Controllers\Api;

use App\Helper\ApiRes;
use App\Http\Controllers\Controller;
use App\Models\Religion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReligionController extends Controller
{

    public function religion(Request $req)
    {
        if ($req->action == "save") {
            return $this->save($req);
        } else if ($req->action == "data") {
            return $this->data($req);
        } else {
            return  ApiRes::invalidAction();
        }
    }
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
