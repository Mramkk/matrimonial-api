<?php

namespace App\Http\Controllers\Api\MotherTongue;

use App\Helper\ApiRes;
use App\Http\Controllers\Controller;
use App\Models\MotherTongue;
use Illuminate\Http\Request;

class ApiMotherTongueController extends Controller
{
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
    public function data()
    {

        $data = MotherTongue::all();
        if ($data) {
            return ApiRes::data('Data List !', $data);
        } else {
            return ApiRes::error();
        }
    }
}
