<?php

namespace App\Http\Controllers\Api\Interest;

use App\Helper\ApiRes;
use App\Http\Controllers\Controller;
use App\Models\Interest;
use Illuminate\Http\Request;

class ApiInterestController extends Controller
{
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
        $short = Interest::where('muid', $req->user()->uid)->latest()->with('user', function ($user) {
            return $user->with('img')->with('imglg')->with('shortlist')->with('interest')->with('visited');
        })->get();

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
            return ApiRes::success('Interest has deleted !');
        } else {
            return ApiRes::error();
        }
    }
}
