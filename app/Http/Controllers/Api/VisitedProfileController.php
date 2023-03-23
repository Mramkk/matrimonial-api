<?php

namespace App\Http\Controllers\Api;

use App\Helper\ApiRes;
use App\Http\Controllers\Controller;
use App\Models\VisitedProfile;
use Illuminate\Http\Request;

class VisitedProfileController extends Controller
{
    public function visitedProfile(Request $req)
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
        $status = VisitedProfile::where('muid', $req->user()->uid)->first();
        if ($status) {
            return ApiRes::success('Already Visited Profile');
        } else {
            $visit = new VisitedProfile();
            $visit->uid = $req->user()->uid;
            $visit->muid =  $req->uid;
            $res = $visit->save();
            if ($res) {
                return ApiRes::success('Visited Profile');
            } else {
                return ApiRes::error();
            }
        }
    }
    public function data(Request $req)
    {
        $visit = VisitedProfile::select('uid')->where('muid', $req->user()->uid)->latest()->with('user')->get();

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
