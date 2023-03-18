<?php

namespace App\Http\Controllers\Api;

use App\Helper\ApiRes;
use App\Http\Controllers\Controller;
use App\Models\Occupation;
use Illuminate\Http\Request;

class OccupationController extends Controller
{
    public function occupation(Request $req)
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
        $de = new Occupation();
        $de->title = $req->title;
        $status = $de->save();
        if ($status) {

            return  ApiRes::success('Data Save Successfuly !');
        } else {
            return  ApiRes::error();
        }
    }
    public function data(Request $req)
    {
        $data = Occupation::all();

        if ($data) {
            return ApiRes::data('Data List !', $data);
        } else {
            return ApiRes::error();
        }
    }
}
