<?php

namespace App\Http\Controllers\Api;

use App\Helper\ApiRes;
use App\Http\Controllers\Controller;
use App\Models\MotherTongue;
use Illuminate\Http\Request;

class MotherTongueController extends Controller
{
    public function motherTongue(Request $req)
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
        $rel = new MotherTongue();
        $rel->mother_tongue = $req->mother_tongue;
        $status = $rel->save();
        if ($status) {
            return  ApiRes::success('Data Save Successfuly !');
        } else {
            return  ApiRes::error();
        }
    }
    public function data(Request $req)
    {
        $data = MotherTongue::all();

        if ($data) {
            return ApiRes::data('Data List !', $data);
        } else {
            return ApiRes::error();
        }
    }
}
