<?php

namespace App\Http\Controllers\Api\Viewed;

use App\Helper\ApiRes;
use App\Http\Controllers\Controller;
use App\Models\ViewedProfile;
use Illuminate\Http\Request;

class ApiViewedController extends Controller
{
    public function save(Request $req)
    {
        if ($req->uid != $req->user()->uid) {
            $status = ViewedProfile::where('uid', $req->user()->uid)->where('muid', $req->uid)->first();
            if ($status) {
                return ApiRes::success('Already Viewed Profile');
            } else {
                $view = new ViewedProfile();
                $view->uid = $req->user()->uid;
                $view->muid =   $req->uid;
                $res = $view->save();
                if ($res) {
                    return ApiRes::success('Viewed Profile');
                } else {
                    return ApiRes::error();
                }
            }
        }
    }
    public function data(Request $req)
    {
        $visit = ViewedProfile::where('uid', $req->user()->uid)->latest()->with('user', function ($user) {
            return $user->with('img')->with('imglg')->with('shortlist')->with('interest');
        })->get();

        if ($visit) {
            return ApiRes::data('Viewed Profile Data', $visit);
        } else {
            return ApiRes::error();
        }
    }
    public function delete(Request $req)
    {
        $visit = ViewedProfile::where('uid', $req->user()->uid)->where('muid', $req->uid)->first();
        $res = $visit->delete();
        if ($res) {
            return ApiRes::success('Deleted !');
        } else {
            return ApiRes::error();
        }
    }
}
