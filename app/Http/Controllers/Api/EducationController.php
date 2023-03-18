<?php

namespace App\Http\Controllers\Api;

use App\Helper\ApiRes;
use App\Http\Controllers\Controller;
use App\Models\Education;
use Illuminate\Http\Request;

class EducationController extends Controller
{
    public function education(Request $req)
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
        $de = new Education();
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
        $data = Education::all();

        if ($data) {
            return ApiRes::data('Data List !', $data);
        } else {
            return ApiRes::error();
        }
    }
}
