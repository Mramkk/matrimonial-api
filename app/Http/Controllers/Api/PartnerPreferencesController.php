<?php

namespace App\Http\Controllers\Api;

use App\Helper\ApiRes;
use App\Http\Controllers\Controller;
use App\Models\PartnerPreference;
use Illuminate\Http\Request;

class PartnerPreferencesController extends Controller
{
    public function partnerPreference(Request $req)
    {
        if ($req->action == "basic-preference") {
            return $this->save($req);
        } else if ($req->action == "education-profession") {
            return $this->educationProfession($req);
        } else if ($req->action == "religion") {
            return $this->religion($req);
        } else if ($req->action == "location") {
            return $this->location($req);
        } else if ($req->action == "expectation") {
            return $this->expectation($req);
        } else {
            return  ApiRes::invalidAction();
        }
    }

    public function save(Request $req)
    {
        $status = PartnerPreference::where('uid', $req->user()->uid)->first();
        if ($status) {
            $partner = PartnerPreference::where('uid', $req->user()->uid)->first();
            $partner->marrital_status = $req->marrital_status;
            $partner->age = $req->age;
            $partner->height = $req->height;
            $partner->diet = $req->diet;
            $partner->smoking = $req->smoking;
            $partner->drinking = $req->drinking;
            $partner->physical_status = $req->physical_status;

            $res = $partner->update();
            if ($res) {
                return ApiRes::success('Data Updated Successfully!');
            } else {
                return ApiRes::error();
            }
        } else {
            $partner = new PartnerPreference();
            $partner->uid = $req->user()->uid;
            $partner->marrital_status = $req->marrital_status;
            $partner->age = $req->age;
            $partner->height = $req->height;
            $partner->diet = $req->diet;
            $partner->smoking = $req->smoking;
            $partner->drinking = $req->drinking;
            $partner->physical_status = $req->physical_status;

            $res = $partner->save();
            if ($res) {
                return ApiRes::success('Data Saved Successfully!');
            } else {

                return ApiRes::error();
            }
        }
    }

    public function educationProfession(Request $req)
    {
        $partner = PartnerPreference::where('uid', $req->user()->uid)->first();
        $partner->highest_education = $req->highest_education;
        $partner->annual_income = $req->annual_income;
        $partner->occupation = $req->occupation;

        $res = $partner->update();
        if ($res) {
            return ApiRes::success('Data Updated Successfully!');
        } else {
            return ApiRes::error();
        }
    }


    public function religion(Request $req)
    {
        $partner = PartnerPreference::where('uid', $req->user()->uid)->first();
        $partner->religion = $req->religion;
        $partner->caste = $req->caste;
        $partner->have_dosh = $req->have_dosh;
        $partner->dosh_type = $req->dosh_type;
        $partner->star = $req->star;
        $partner->mother_tounge = $req->mother_tounge;

        $res = $partner->update();
        if ($res) {
            return ApiRes::success('Data Updated Successfully!');
        } else {
            return ApiRes::error();
        }
    }

    public function location(Request $req)
    {
        $partner = PartnerPreference::where('uid', $req->user()->uid)->first();
        $partner->country = $req->country;
        $partner->state = $req->state;
        $partner->city = $req->city;

        $res = $partner->update();
        if ($res) {
            return ApiRes::success('Data Updated Successfully!');
        } else {
            return ApiRes::error();
        }
    }

    public function expectation(Request $req)
    {
        $partner = PartnerPreference::where('uid', $req->user()->uid)->first();
        $partner->expectation = $req->expectation;

        $res = $partner->update();
        if ($res) {
            return ApiRes::success('Data Updated Successfully!');
        } else {
            return ApiRes::error();
        }
    }
}
