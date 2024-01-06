<?php

namespace App\Http\Controllers\Api\User;

use App\Helper\ApiRes;
use App\Http\Controllers\Controller;
use App\Models\commission;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class ApiUserController extends Controller
{
    public function data()
    {
        $data = User::where('uid', auth()->user()->uid)->with('img')->with('imglg')->get();
        return ApiRes::data("Datalist", $data);
    }
    public function byId(Request $req)
    {
        $data = User::where('uid', $req->uid)->where('completed', '1')->with('img')->with('imglg')->with("interest")->get();
        return ApiRes::data("Datalist", $data);
    }
    public function register(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'profile_for' => 'required|max:255',
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'dob' => 'date_format:Y-m-d|before:today',
            'email' => 'required|email|unique:users|max:255',
            'religion' => 'required|max:255',
            'community' => 'required|max:255',
            'mother_tounge' => 'required|max:255',
            'country' => 'required|max:255',


        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            if ($errors->first('profile_for')) {
                return ApiRes::failed($errors->first('profile_for'));
            } else if ($errors->first('first_name')) {
                return ApiRes::failed($errors->first('first_name'));
            } else if ($errors->first('last_name')) {
                return ApiRes::failed($errors->first('last_name'));
            } else if ($errors->first('dob')) {
                return ApiRes::failed($errors->first('dob'));
            } else if ($errors->first('email')) {
                return ApiRes::failed($errors->first('email'));
            } else if ($errors->first('religion')) {
                return ApiRes::failed($errors->first('religion'));
            } else if ($errors->first('community')) {
                return ApiRes::failed($errors->first('community'));
            } else if ($errors->first('mother_tounge')) {
                return ApiRes::failed($errors->first('mother_tounge'));
            } else if ($errors->first('country')) {
                return ApiRes::failed($errors->first('country'));
            }
        }
        $uid = "BH" . random_int(10000000, 99999999);
        $user = new User();
        $user->uid = $uid;
        $user->profile_for = $req->profile_for;
        $user->first_name = $req->first_name;
        $user->last_name = $req->last_name;
        $user->dob = $req->dob;
        $user->age = $this->getAge($req->dob);
        $user->gender = $req->gender;
        $user->email = $req->email;
        $user->religion = $req->religion;
        $user->community = $req->community;
        $user->mother_tounge = $req->mother_tounge;
        $user->country = $req->country;
        $status = $user->save();

        if ($req->referral_code != null) {
            $user = User::where('uid', $req->referral_code)->first();
            $com = commission::where('uid', $user->uid)->first();
            if ($com != null) {
                $com->points = $com->points + 10;
                $com->update();
                $user = User::where('uid', $uid)->first();
                $user->ref_id = $req->referral_code;
                $status =  $user->update();
            } else {
                $com = new commission();
                $com->uid = $user->uid;
                $com->points =  10;
                $status = $com->save();
                $user = User::where('uid', $uid)->first();
                $user->ref_id = $req->referral_code;
                $status =  $user->update();
            }
        }
        if ($status) {
            return  ApiRes::success('You Register Successfuly !');
        } else {
            return  ApiRes::error();
        }
    }
    public function sendOTP(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'ccode' => 'required|string|max:255',
            'phone' => 'required|numeric|digits:10|unique:users',
            'email' => 'required|email|max:255',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            if ($errors->first('ccode')) {
                return ApiRes::failed($errors->first('ccode'));
            } else if ($errors->first('phone')) {
                return ApiRes::failed($errors->first('phone'));
            } else if ($errors->first('email')) {
                return ApiRes::failed($errors->first('email'));
            }
        }
        $user = User::where('email', $req->email)->first();
        if ($user != null) {
            $user->ccode = $req->ccode;
            $user->phone = $req->phone;
            $user->otp = "123456";
            $status = $user->update();
            if ($status) {
                return  ApiRes::success('OTP sent successfuly !');
            } else {
                return  ApiRes::error();
            }
        } else {
            return ApiRes::error();
        }
    }
    public function sendOTPLogin(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'ccode' => 'required|string|max:255',
            'phone' => 'required|numeric|digits:10',

        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            if ($errors->first('ccode')) {
                return ApiRes::failed($errors->first('ccode'));
            } else if ($errors->first('phone')) {
                return ApiRes::failed($errors->first('phone'));
            }
        }
        $user = User::where('phone', $req->phone)->first();
        if ($user != null) {
            $user->otp = "123456";
            $status = $user->update();
            if ($status) {
                return  ApiRes::success('OTP sent successfuly !');
            } else {
                return  ApiRes::error();
            }
        } else {
            return ApiRes::error();
        }
    }
    public function verifyOTP(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'otp' => 'required|string',
            'ccode' => 'required|string|max:255',
            'phone' => 'required|numeric|digits:10',

        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            if ($errors->first('ccode')) {
                return ApiRes::failed($errors->first('ccode'));
            } else if ($errors->first('phone')) {
                return ApiRes::failed($errors->first('phone'));
            }
        }
        $user = User::where('phone', $req->phone)->first();
        if ($user != null) {
            if ($user->otp == $req->otp) {
                $token = $user->createToken($user->uid)->plainTextToken;
                return ApiRes::rlMsg("You login successfully !.", $user->uid, $token, $user->completed);
            } else {
                return ApiRes::credentials();
            }
        } else {
            return ApiRes::failed("Data not found !");
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
    public function login(Request $req)
    {

        $validator = Validator::make($req->all(), [
            'email_or_phone' => 'required|string',
            'password' => 'required|string',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            if ($errors->first('email_or_phone')) {
                return ApiRes::failed($errors->first('email_or_phone'));
            } else if ($errors->first('password')) {
                return ApiRes::failed($errors->first('password'));
            }
        }
        $user = User::orwhere('email', $req->email_or_phone)->orwhere('phone', $req->email_or_phone)->first();
        if ($user != null) {
            if (Hash::check($req->password, $user->password)) {
                $token = $user->createToken($user->uid)->plainTextToken;
                return ApiRes::rlMsg("You login successfully !.", $user->uid, $token, $user->completed);
            } else {
                return ApiRes::credentials();
            }
        } else {
            return ApiRes::failed("Data not found !");
        }
    }
    public function passwordReset(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'password' => 'required|min:8',
            'confirm_password' => 'required|min:8|same:password',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            if ($errors->first('password')) {
                return ApiRes::failed($errors->first('password'));
            } else if ($errors->first('confirm_password')) {
                return ApiRes::failed($errors->first('confirm_password'));
            }
        }
        $user = User::where('id', auth()->user()->id)->first();
        if ($user != null) {
            $user->password = Hash::make($req->password);
            $status = $user->update();
            if ($status) {
                $status =  $req->user()->currentAccessToken()->delete();
                if ($status) {
                    return  ApiRes::success('Password changed successfuly !');
                } else {
                    return  ApiRes::error();
                }
            } else {
                return  ApiRes::error();
            }
        } else {
            return ApiRes::error();
        }
    }
    public function logout(Request $req)
    {
        $user =  $req->user()->currentAccessToken()->delete();
        if ($user) {
            return  ApiRes::logout();
        } else {
            return ApiRes::error();
        }
    }
    public function getAge($date)
    {
        $d1 = new DateTime(date("Y-m-d"));
        $d2 = new DateTime($date);
        $diff = $d2->diff($d1);
        return $diff->y;
    }
}
