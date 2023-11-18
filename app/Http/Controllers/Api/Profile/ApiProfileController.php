<?php

namespace App\Http\Controllers\Api\Profile;

use App\Helper\ApiRes;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ApiProfileController extends Controller
{
    public function data()
    {
        $user = User::where('id', Auth::id())->first();
        return ApiRes::data('datalist', $user);
    }
    public function personal(Request $req)
    {
        $user = User::where('id', Auth::id())->first();
        $user->marrital_status = $req->marrital_status;
        $user->sub_community = $req->sub_community;
        $user->marry_other_caste = $req->marry_other_caste;
        $user->state = $req->state;
        $user->city = $req->city;
        $status = $user->save();
        if ($status) {
            return ApiRes::update();
        } else {
            return ApiRes::error();
        }
    }
    public function physical(Request $req)
    {
        $user = User::where('id', Auth::id())->first();
        $user->height = $req->height;
        $user->weight = $req->weight;
        $user->body_type = $req->body_type;
        $user->complexion = $req->complexion;
        $user->physical_status = $req->physical_status;
        $status = $user->save();
        if ($status) {
            return ApiRes::update();
        } else {
            return ApiRes::error();
        }
    }
    public function accDetails(Request $req)
    {
        $user = User::where('id', Auth::id())->first();
        $user->first_name = $req->first_name;
        $user->last_name = $req->last_name;
        $user->last_name = $req->last_name;
        $user->ccode = $req->ccode;
        $user->phone = $req->phone;
        $user->email =  $req->email;
        $user->password = Hash::make($req->password);
        $status = $user->save();
        if ($status) {
            return ApiRes::update();
        } else {
            return ApiRes::error();
        }
    }
    public function buildProfileStepOne(Request $req)
    {
        $user  = User::where('id', auth()->user()->id)->first();
        $user->state = $req->state;
        $user->city = $req->city;
        $user->marrital_status = $req->marrital_status;
        $user->height = $req->height;
        $user->sub_community = $req->sub_community;
        $user->caste_no_bar = $req->caste_no_bar;
        $status = $user->update();
        if ($status) {
            return ApiRes::update();
        } else {
            return ApiRes::error();
        }
    }
    public function edu(Request $req)
    {
        $user = User::where('id', Auth::id())->first();
        $user->highest_education = $req->highest_education;
        $user->additional_degree = $req->additional_degree;
        $user->occupation = $req->occupation;
        $user->employed_in = $req->employed_in;
        $user->annual_income = $req->annual_income;
        $status = $user->save();
        if ($status) {
            return ApiRes::update();
        } else {
            return ApiRes::error();
        }
    }
    public function habits(Request $req)
    {
        $user = User::where('id', Auth::id())->first();
        $user->diet = $req->diet;
        $user->smoking = $req->smoking;
        $user->drinking = $req->drinking;
        $status = $user->save();
        if ($status) {
            return ApiRes::update();
        } else {
            return ApiRes::error();
        }
    }
    public function family(Request $req)
    {
        $user = User::where('id', auth()->user()->id)->first();
        $user->family_status = $req->family_status;
        $user->family_type = $req->family_type;
        $user->family_values = $req->family_values;
        $user->father_occupation = $req->father_occupation;
        $user->mother_occupation = $req->mother_occupation;
        $user->num_brothers = $req->num_brothers;
        $user->num_married_brothers = $req->num_married_brothers;
        $user->num_sisters = $req->num_sisters;
        $user->num_married_sisters = $req->num_married_sisters;
        $status = $user->save();
        if ($status) {
            return ApiRes::update();
        } else {
            return ApiRes::error();
        }
    }

    public function about(Request $req)
    {
        $user = User::where('id', Auth::id())->first();
        $user->about_me = $req->about_me;
        $status = $user->save();
        if ($status) {
            return ApiRes::update();
        } else {
            return ApiRes::error();
        }
    }
}
