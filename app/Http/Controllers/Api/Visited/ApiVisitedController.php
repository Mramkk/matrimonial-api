<?php

namespace App\Http\Controllers\Api\Visited;

use App\Helper\ApiRes;
use App\Http\Controllers\Controller;
use App\Models\VisitedProfile;
use Illuminate\Http\Request;

class ApiVisitedController extends Controller
{
    public function save(Request $req)
    {
        if ($req->uid != $req->user()->uid) {
            $status = VisitedProfile::where('uid', $req->uid)->where('muid', $req->user()->uid)->first();
            if ($status) {
                return ApiRes::success('Already Visited Profile');
            } else {
                $visit = new VisitedProfile();
                $visit->uid = $req->uid;
                $visit->muid =   $req->user()->uid;
                $res = $visit->save();
                if ($res) {
                    return ApiRes::success('Visited Profile');
                } else {
                    return ApiRes::error();
                }
            }
        }
    }
    public function data(Request $req)
    {
        $visit = VisitedProfile::where('uid', $req->user()->uid)->latest()->with('user', function ($user) {
            return $user->with('img')->with('imglg')->with('shortlist')->with('interest')->with('visited');
        })->get();

        if ($visit) {
            return ApiRes::data('Visited Profile Data', $visit);
        } else {
            return ApiRes::error();
        }
    }
    public function delete(Request $req)
    {
        $visit = VisitedProfile::where('uid', $req->uid)->where('muid', $req->user()->uid)->first();
        $res = $visit->delete();
        if ($res) {
            return ApiRes::success('Deleted !');
        } else {
            return ApiRes::error();
        }
    }
}
