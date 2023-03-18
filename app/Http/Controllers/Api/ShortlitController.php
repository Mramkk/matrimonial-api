<?php

namespace App\Http\Controllers\Api;

use App\Helper\ApiRes;
use App\Http\Controllers\Controller;
use App\Models\Shortlit;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Return_;

class ShortlitController extends Controller
{
    public function shortlist(Request $req)
    {
        if ($req->user()->status == 1) {
            if ($req->action == "save") {
                return $this->save($req);
            } elseif ($req->action == "data") {
                return $this->data($req);
            } elseif ($req->action == "delete") {
                return $this->delete($req);
            } else {
                return  ApiRes::invalidAction();
            }
        } else {
            return  ApiRes::invalidAction();
        }
    }
    public function save(Request $req)
    {
        $short = new Shortlit();
        $short->uid = $req->uid;
        $short->muid = $req->user()->uid;
        $res = $short->save();
        if ($res) {
            return ApiRes::success('Shortlited');
        } else {
            return ApiRes::error();
        }
    }
    public function data(Request $req)
    {
        $short = Shortlit::where('muid', $req->user()->uid)->get();

        if ($short) {
            return ApiRes::data('Shortlited Data', $short);
        } else {
            return ApiRes::error();
        }
    }
    public function delete(Request $req)
    {
        $short = Shortlit::where('uid', $req->uid)->where('muid', $req->user()->uid)->first();
        $res = $short->delete();
        if ($res) {
            return ApiRes::success('Deleted !');
        } else {
            return ApiRes::error();
        }
    }
}
