<?php

namespace App\Http\Controllers\Api\Shortlist;

use App\Helper\ApiRes;
use App\Http\Controllers\Controller;
use App\Models\Interest;
use App\Models\Shortlit;
use Illuminate\Http\Request;

class ApiShortlistController extends Controller
{
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

        $short = Shortlit::where('muid', $req->user()->uid)
            ->latest()->with('user', function ($user) {
                return $user->with('img')->with('imglg')->with('shortlist')->with('interest')->with('visited');
            })->get();


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
