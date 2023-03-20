<?php

namespace App\Http\Controllers\Api;

use App\Helper\ApiRes;
use App\Http\Controllers\Controller;
use App\Models\Img;
use App\Models\User;
use App\Models\Shortlit;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Image;

class UserController extends Controller
{
    public function user(Request $req)
    {
        if ($req->action == "register") {
            return $this->register($req);
        } else if ($req->action == "verify-user") {
            return $this->verifyUser($req);
        } else if ($req->action == "otp") {
            return $this->sendOtp($req);
        } else if ($req->action == "verify otp") {
            return $this->verifyOtp($req);
        } else if ($req->action == "data") {
            return $this->data($req);
        } else if ($req->action == "user-list") {
            if ($req->user()->status == 1) {
                return $this->UserList($req);
            } else {
                return  ApiRes::inactiveUser();
            }
        } else if ($req->action == "update") {
            if ($req->user()->status == 1) {
                return $this->update($req);
            } else {
                return  ApiRes::inactiveUser();
            }
        } else if ($req->action == "upload-id-proof") {
            if ($req->user()->status == 1) {
                return $this->uploadIdProof($req);
            } else {
                return  ApiRes::inactiveUser();
            }
        } else if ($req->action == "upload-profile-img") {
            if ($req->user()->status == 1) {
                return $this->uploadProfileImg($req);
            } else {
                return  ApiRes::inactiveUser();
            }
        } else if ($req->action == "logout") {
            return $this->logout($req);
        } else {
            return  ApiRes::invalidAction();
        }
    }
    public function register(Request $req)
    {
        $error = $this->customValidation($req);
        if ($error) {
            return $error;
        }
        $user = new User();
        $user->uid = uniqid();
        $user->bhid = "BH" . random_int(100000, 999999);
        $user->created_by = $req->created_by;
        $user->first_name = $req->first_name;
        $user->last_name = $req->last_name;
        $user->dob = $req->dob;
        $user->age = $this->getAge($req->dob);
        $user->gender = $req->gender;
        $user->phone = $req->phone;
        $user->email = $req->email;
        $user->religion = $req->religion;
        $user->caste = $req->caste;
        $user->mother_tounge = $req->mother_tounge;
        $user->country = $req->country;
        $user->state = $req->state;
        $user->city = $req->city;
        $status = $user->save();
        if ($status) {
            return  $this->sendOtp($req);
            // return  ApiRes::success('You Register Successfuly !');
        } else {
            return  ApiRes::error();
        }
    }
    public function update(Request $req)
    {
        if ($req->action == "upload-profile-img") {
            return $this->uploadProfileImg($req);
        } else {
            return  ApiRes::invalidAction();
        }










        $data = [
            'marrital_status' => $req->marrital_status,
            'subcaste' => $req->subcaste,
            'marry_other_caste' => $req->marry_other_caste,
            'height' => $req->height,
            'weight' => $req->weight,
            'body_type' => $req->body_type,
            'complexion' => $req->complexion,
            'physical_status' => $req->physical_status,
            'highest_education' => $req->highest_education,
            'additional_degree' => $req->additional_degree,
            'occupation' => $req->occupation,
            'employed_in' => $req->employed_in,
            'annual_income' => $req->annual_income,
            'diet' => $req->diet,
            'smoking' => $req->smoking,
            'drinking' => $req->drinking,
            'have_dosh' => $req->have_dosh,
            'star' => $req->star,
            'rasi' => $req->rasi,
            'birth_time' => $req->birth_time,
            'birth_place' => $req->birth_place,
            'family_status' => $req->family_status,
            'family_type' => $req->family_type,
            'family_values' => $req->family_values,
            'father_occupation' => $req->father_occupation,
            'mother_occupation' => $req->mother_occupation,
            'no_brothers' => $req->no_brothers,
            'no_married_brothers' => $req->no_married_brothers,
            'no_sisters' => $req->no_sisters,
            'no_married_sisters' => $req->no_married_sisters,
            'about_me' => $req->about_me,
            'complete' => '1',




        ];
        $res = User::Where('uid', $req->user()->uid)->update($data);
        if ($res) {
            return ApiRes::success("Account update successfully !.");
        } else {
            return ApiRes::error();
        }
    }
    public function uploadIdProof(Request $req)
    {
        $status = false;
        if ($req->hasFile('id_proof')) {
            $user = User::Where('uid', $req->user()->uid)->first();
            if ($user->id_proof != null) {
                // delete old file
                unlink('./uploads/document/' . $user->id_proof);
            }
            $name =  uniqid() . ".webp";
            Image::make($req->id_proof->getRealPath())->resize('480', '360')->save('uploads/document/' . $name);
            $user->id_proof = $name;
            $status = $user->update();
        }


        if ($status) {
            return ApiRes::success("Id proof uploaded successfully !.");
        } else {
            return ApiRes::error();
        }
    }
    public function uploadProfileImg(Request $req)
    {
        $status = false;
        $uid = $req->user()->uid;
        $maxId = Img::where('uid', $uid)->max('img_id') + 1;
        if ($req->hasFile('image')) {
            $img = new Img();
            $name =  uniqid() . ".webp";
            Image::make($req->image->getRealPath())->resize('320', '180')->save('uploads/image/' . $name);
            $img->uid = $uid;
            $img->img_id = $maxId;
            $img->type = "sm";
            $img->image = $name;
            $img->active = "1";
            $status = $img->save();
            $img = new Img();
            $name =  uniqid() . ".webp";
            Image::make($req->image->getRealPath())->resize('640', '480')->save('uploads/image/' . $name);
            $img->uid = $uid;
            $img->img_id = $maxId;
            $img->type = "md";
            $img->image = $name;
            $img->active = "1";
            $status = $img->save();

            $img = new Img();
            $name =  uniqid() . ".webp";
            Image::make($req->image->getRealPath())->resize('1280', '720')->save('uploads/image/' . $name);
            $img->uid = $uid;
            $img->img_id = $maxId;
            $img->type = "lg";
            $img->image = $name;
            $img->active = "1";
            $status = $img->save();
        }


        if ($status) {
            return ApiRes::success("Profile image uploaded successfully !.");
        } else {
            return ApiRes::error();
        }
    }
    public function UserImage(Request $req)
    {
        if ($req->action == "get") {
            $img = Img::Where('uid', $req->user()->uid)->get();
            if ($img) {

                return ApiRes::data("Image list.", $img);
            } else {
                return ApiRes::error();
            }
        } elseif ($req->action == "upload-profile-img") {
            return $this->uploadProfileImg($req);
        } elseif ($req->action == "delete") {
            $img = Img::Where('img_id', $req->img_id)->delete();
            if ($img) {

                return ApiRes::success("Image Deleted Successfully !");
            } else {
                return ApiRes::error();
            }
        } else {
            return  ApiRes::invalidAction();
        }
    }
    public function verifyUser(Request $req)
    {
        $status = $this->phoneValidation($req->phone);
        if ($status == true) {
            $user = User::Where('phone', $req->phone)->first();
            if (!$user || $user->phone != $req->phone) {
                return ApiRes::failed("You don't have account !.");
            } else {
                return ApiRes::success("veryfied");
            }
        }
    }
    public function sendOtp(Request $req)
    {
        $status = $this->phoneValidation($req->phone);
        if ($status == true) {
            $user = User::Where('phone', $req->phone)->first();
            if (!$user || $user->phone != $req->phone) {
                return ApiRes::failed("Invalid Phone Number !.");
            } else {
                // $otp = random_int(100000, 999999);
                // $otp = "123456";
                // $req->session()->put('otp', "123456");
                $res = User::Where('phone', $req->phone)->update(['otp' => '123456']);
                if ($res) {
                    return ApiRes::otp("otp sent on your phone.", "123456");
                } else {
                    return ApiRes::error();
                }
            }
        }
    }
    public function verifyOtp(Request $req)
    {
        $phone = $this->phoneValidation($req->phone);
        if ($phone == false) {
            return ApiRes::failed("Please enter 10 (Digit) phone no.");
        } else {
            $user  = User::Where('phone', $req->phone)->first();

            if ($user->otp == $req->otp) {
                $token = $user->createToken($user->uid)->plainTextToken;
                $res = User::where('uid', $user->uid)->update([
                    "remember_token" => $token
                ]);
                if ($res) {
                    return ApiRes::rlMsg("You login successfully !.", $user->uid, $token);
                } else {
                    return ApiRes::error();
                }
            } else {
                return ApiRes::failed("OTP not matched !.");
            }
        }
    }
    public function data(Request $req)
    {
        $user = User::where('uid', $req->user()->uid)->with('img')->withCount('imglg')->with('shortlist')->with('interest')->get();

        if ($user) {

            return ApiRes::data("User Details !.", $user);
        } else {
            return ApiRes::error();
        }
    }
    public function UserList(Request $req)
    {


        // $user = User::whereNotIn('id', [$req->user()->id])->where('complete', '1')->with('imglg')->with('shortlist')->with('interest')->get();
        $user = User::whereNotIn('id', [$req->user()->id])->where('complete', '1')->with('img')->withCount('imglg')->with('shortlist')->with('interest')->get();


        if ($user) {
            return ApiRes::data("User List !.", $user);
        } else {
            return ApiRes::error();
        }
    }
    public function partialUpdate(Request $req)
    {
        if ($req->action == "upload-profile-img") {
            return $this->uploadProfileImg($req);
        } elseif ($req->action == "basic-details-update") {
            return $this->basicDetailsUpdate($req);
        } elseif ($req->action == "about-me-update") {
            return $this->aboutMeUpdate($req);
        } elseif ($req->action == "religion-information-update") {
            return $this->religionInfoUpdate($req);
        } elseif ($req->action == "education-information-update") {
            return $this->educationInfoUpdate($req);
        } elseif ($req->action == "family-details-update") {
            return $this->familyDetailsUpdate($req);
        } elseif ($req->action == "location-info-update") {
            return $this->locationInfoUpdate($req);
        } elseif ($req->action == "habits-hobbies-update") {
            return $this->habitsHobbiesUpdate($req);
        } elseif ($req->action == "horoscope-info-update") {
            return $this->horoscopeInfoUpdate($req);
        } else {
            return  ApiRes::invalidAction();
        }
    }
    public function basicDetailsUpdate(Request $req)
    {
        $user = User::Where('uid', $req->user()->uid)->first();
        $user->created_by = $req->created_by;
        $user->first_name = $req->first_name;
        $user->last_name = $req->last_name;
        $user->dob = $req->dob;
        $user->age = $this->getAge($req->dob);
        $user->gender = $req->gender;
        $user->mother_tounge = $req->mother_tounge;
        $user->marrital_status = $req->marrital_status;

        $status = $user->update();
        if ($status) {
            return ApiRes::success("Basic Details Updated Successfully !.");
        } else {
            return ApiRes::error();
        }
    }

    public function aboutMeUpdate(Request $req)
    {
        $user = User::Where('uid', $req->user()->uid)->first();
        $user->about_me = $req->about_me;

        $status = $user->update();
        if ($status) {
            return ApiRes::success("AboutMe Updated Successfully !.");
        } else {
            return ApiRes::error();
        }
    }

    public function religionInfoUpdate(Request $req)
    {
        $user = User::Where('uid', $req->user()->uid)->first();
        $user->religion = $req->religion;
        $user->caste = $req->caste;
        $user->subcaste = $req->subcaste;
        $user->marry_other_caste = $req->marry_other_caste;

        $status = $user->update();
        if ($status) {
            return ApiRes::success("Religion Information Updated Successfully !.");
        } else {
            return ApiRes::error();
        }
    }

    public function educationInfoUpdate(Request $req)
    {
        $user = User::Where('uid', $req->user()->uid)->first();
        $user->highest_education = $req->highest_education;
        $user->additional_degree = $req->additional_degree;
        $user->occupation = $req->occupation;
        $user->employed_in = $req->employed_in;
        $user->annual_income = $req->annual_income;

        $status = $user->update();
        if ($status) {
            return ApiRes::success("Education Information Updated Successfully !.");
        } else {
            return ApiRes::error();
        }
    }

    public function familyDetailsUpdate(Request $req)
    {
        $user = User::Where('uid', $req->user()->uid)->first();
        $user->family_status = $req->family_status;
        $user->family_type = $req->family_type;
        $user->family_values = $req->family_values;
        $user->father_occupation = $req->father_occupation;
        $user->mother_occupation = $req->mother_occupation;
        $user->no_brothers = $req->no_brothers;
        $user->no_married_brothers = $req->no_married_brothers;
        $user->no_sisters = $req->no_sisters;
        $user->no_married_sisters = $req->no_married_sisters;

        $status = $user->update();
        if ($status) {
            return ApiRes::success("Family Details Updated Successfully !.");
        } else {
            return ApiRes::error();
        }
    }

    public function locationInfoUpdate(Request $req)
    {
        $user = User::Where('uid', $req->user()->uid)->first();
        $user->country = $req->country;
        $user->state = $req->state;
        $user->city = $req->city;

        $status = $user->update();
        if ($status) {
            return ApiRes::success("Location Information Updated Successfully !.");
        } else {
            return ApiRes::error();
        }
    }

    public function habitsHobbiesUpdate(Request $req)
    {
        $user = User::Where('uid', $req->user()->uid)->first();
        $user->diet = $req->diet;
        $user->drinking = $req->drinking;
        $user->smoking = $req->smoking;

        $status = $user->update();
        if ($status) {
            return ApiRes::success("Habits and Hobbies Updated Successfully !.");
        } else {
            return ApiRes::error();
        }
    }

    public function physicalAttributesUpdate(Request $req)
    {
        $user = User::Where('uid', $req->user()->uid)->first();
        $user->height = $req->height;
        $user->weight = $req->weight;
        $user->body_type = $req->body_type;
        $user->complexion = $req->complexion;
        $user->physical_status = $req->physical_status;

        $status = $user->update();
        if ($status) {
            return ApiRes::success("Physical Attributes Updated Successfully !.");
        } else {
            return ApiRes::error();
        }
    }

    public function horoscopeInfoUpdate(Request $req)
    {
        $user = User::Where('uid', $req->user()->uid)->first();
        $user->have_dosh = $req->have_dosh;
        $user->star = $req->star;
        $user->rasi = $req->rasi;
        $user->birth_time = $req->birth_time;
        $user->birth_place = $req->birth_place;

        $status = $user->update();
        if ($status) {
            return ApiRes::success("Horoscope Information Updated Successfully !.");
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
    public function customValidation(Request $req)
    {

        $validator = Validator::make($req->all(), [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            // 'phone' => 'required|max:255',
            'phone' => 'required|unique:users|max:255',
            'email' => 'required|email|unique:users|max:255',
            'religion' => 'required|max:255',
            'caste' => 'required|max:255',
            'mother_tounge' => 'required|max:255',
            'country' => 'required|max:255',
            'state' => 'required|max:255',
            'city' => 'required|max:255',

        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            if ($errors->first('first_name')) {
                return ApiRes::failed($errors->first('first_name'));
            } else if ($errors->first('last_name')) {
                return ApiRes::failed($errors->first('last_name'));
            } else if ($errors->first('phone')) {
                return ApiRes::failed($errors->first('phone'));
            } else if ($errors->first('email')) {
                return ApiRes::failed($errors->first('email'));
            } else if ($errors->first('religion')) {
                return ApiRes::failed($errors->first('religion'));
            } else if ($errors->first('caste')) {
                return ApiRes::failed($errors->first('caste'));
            } else if ($errors->first('mother_tounge')) {
                return ApiRes::failed($errors->first('mother_tounge'));
            } else if ($errors->first('country')) {
                return ApiRes::failed($errors->first('country'));
            } else if ($errors->first('state')) {
                return ApiRes::failed($errors->first('state'));
            } else if ($errors->first('city')) {
                return ApiRes::failed($errors->first('city'));
            }
        }

        $phone = $this->phoneValidation($req->phone);
        if ($phone == false) {
            return ApiRes::failed("Please enter 10 (Digit) phone no.");
        }
    }
    public function phoneValidation($phone)
    {

        $arr = str_split($phone);
        $len = count($arr);
        $startPoint = $len - 10;
        $newPhone = substr($phone,  $startPoint, 10);
        if (count(str_split($newPhone)) == 10) {
            return true;
        } else {
            return false;
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
