<?php

namespace App\Http\Controllers\Api\Preference;

use App\Helper\ApiRes;
use App\Http\Controllers\Controller;
use App\Models\PartnerPreference;
use Illuminate\Http\Request;

class ApiPreferenceController extends Controller
{
    public function data()
    {
        $data = PartnerPreference::where('uid', auth()->user()->uid)->first();
        if ($data) {
            return ApiRes::data('datalist', $data);
        } else {
            return ApiRes::error();
        }
    }
    public function basic(Request $req)
    {
        $status = PartnerPreference::where('uid', $req->user()->uid)->first();
        if ($status) {
            $partner = PartnerPreference::where('uid', $req->user()->uid)->first();
            $partner->age_from = $req->age_from;
            $partner->age_to = $req->age_to;
            $partner->height_from = $req->height_from;
            $partner->height_to = $req->height_to;
            $partner->marrital_status = $req->marrital_status;
            $partner->physical_status = $req->physical_status;
            $partner->diet = $req->diet;
            $partner->smoking = $req->smoking;
            $partner->drinking = $req->drinking;


            $res = $partner->update();
            if ($res) {
                return ApiRes::success('Data Updated Successfully!');
            } else {
                return ApiRes::error();
            }
        } else {
            $partner = new PartnerPreference();
            $partner->uid = $req->user()->uid;
            $partner->age_from = $req->age_from;
            $partner->age_to = $req->age_to;
            $partner->height_from = $req->height_from;
            $partner->height_to = $req->height_to;
            $partner->marrital_status = $req->marrital_status;
            $partner->physical_status = $req->physical_status;
            $partner->diet = $req->diet;
            $partner->smoking = $req->smoking;
            $partner->drinking = $req->drinking;

            $res = $partner->save();
            if ($res) {
                return ApiRes::success('Data Saved Successfully!');
            } else {

                return ApiRes::error();
            }
        }
    }
    public function religion(Request $req)
    {
        $pre = PartnerPreference::where('uid', $req->user()->uid)->first();
        $pre->religion = $req->religion;
        $pre->caste = $req->caste;
        $pre->mother_tounge = $req->mother_tounge;
        $status =  $pre->save();
        if ($status) {
            return ApiRes::success('Data Updated Successfully!');
        } else {

            return ApiRes::error();
        }
    }
    public function location(Request $req)
    {
        $pre = PartnerPreference::where('uid', $req->user()->uid)->first();
        $pre->country = $req->country;
        $pre->state = $req->state;
        $pre->city = $req->city;
        $status =  $pre->save();
        if ($status) {
            return ApiRes::success('Data Updated Successfully!');
        } else {

            return ApiRes::error();
        }
    }
    public function edu(Request $req)
    {
        $pre = PartnerPreference::where('uid', $req->user()->uid)->first();
        $pre->highest_education = $req->highest_education;
        $pre->occupation = $req->occupation;
        $pre->annual_income = $req->annual_income;
        $status =  $pre->save();
        if ($status) {
            return ApiRes::success('Data Updated Successfully!');
        } else {

            return ApiRes::error();
        }
    }
    public function expectation(Request $req)
    {
        $pre = PartnerPreference::where('uid', $req->user()->uid)->first();
        $pre->expectation = $req->expectation;
        $status =  $pre->save();
        if ($status) {
            return ApiRes::success('Data Updated Successfully!');
        } else {

            return ApiRes::error();
        }
    }
}
