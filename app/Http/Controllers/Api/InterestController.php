<?php

namespace App\Http\Controllers\Api;

use App\Helper\ApiRes;
use App\Http\Controllers\Controller;
use App\Models\Interest;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\New_;

class InterestController extends Controller
{
    public function interest(Request $req)
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
        $inte = new Interest();
        $inte->uid = $req->uid;
        $inte->muid = $req->user()->uid;
        $res = $inte->save();
        if ($res) {
            return ApiRes::success('Interest sent !');
        } else {
            return ApiRes::error();
        }
    }
    public function data(Request $req)
    {
        $short = Interest::select('uid')->where('muid', $req->user()->uid)->latest()->with('user')->get();

        if ($short) {
            return ApiRes::data('Interest Data', $short);
        } else {
            return ApiRes::error();
        }
    }
    public function delete(Request $req)
    {
        $short = Interest::where('uid', $req->uid)->where('muid', $req->user()->uid)->first();
        $res = $short->delete();
        if ($res) {
            return ApiRes::success('Interest request deleted !');
        } else {
            return ApiRes::error();
        }
    }
}
