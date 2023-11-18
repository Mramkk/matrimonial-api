<?php

namespace App\Http\Controllers\Api;

use App\Helper\ApiRes;
use App\Http\Controllers\Controller;
use App\Models\Img;
use App\Models\PartnerPreference;
use App\Models\User;
use App\Models\Shortlit;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Image;
use Illuminate\Support\Facades\Hash;

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
        } else if ($req->action == "reg-otp") {
            return $this->regSendOtp($req);
        } else if ($req->action == "verify-user-phone") {
            return $this->verifyUserPhone($req);
        } else if ($req->action == "verify otp") {
            return $this->verifyOtp($req);
        } else if ($req->action == "data") {
            return $this->data($req);
        } else if ($req->action == "login") {
            return $this->login($req);
        } elseif ($req->action == "forget-password") {
            return $this->forgetPassword($req);
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
        if ($this->getAge($req->dob) <= 17) {
            return ApiRes::failed('Age must be 18 or greater than.');
        }
        $user = new User();
        $user->uid = "BH" . random_int(100000, 999999);
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
        if ($status) {
            // return  $this->sendOtp($req);
            return  ApiRes::success('You Register Successfuly !');
        } else {
            return  ApiRes::error();
        }
    }
    public function update(Request $req)
    {

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
        $user = User::Where('uid', $req->user()->uid)->first();
        $emailVerificationCode =  uniqid();
        if ($req->hasFile('id_proof')) {

            if ($user->id_proof != null) {
                // delete old file
                unlink('./uploads/document/' . $user->id_proof);
            }
            $name =  uniqid() . ".webp";
            Image::make($req->id_proof->getRealPath())->resize('480', '360')->save('uploads/document/' . $name);
            $user->id_proof = $name;
            $user->document = "1";
            $user->status = "1";
            $user->completed = "1";
            $user->completed = "1";
            $user->email_verification_code = $emailVerificationCode;
            $status = $user->update();
        }


        if ($status) {
            $mail = new MailController();
            $url = url('') . "/email-varification/" . $emailVerificationCode;
            $body = "<H1> Best Humsafar </H1> <br>
                     <p>Thank you for registration </p>
                     <a href='$url'> verify email </a>
            ";
            $mail->regMail($user->email, $body);

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
            Image::make($req->image->getRealPath())->resize('150', '150')->save('uploads/image/' . $name);
            $img->uid = $uid;
            $img->img_id = $maxId;
            $img->type = "sm";
            $img->image = $name;
            $img->active = "1";
            $status = $img->save();
            $img = new Img();
            $name =  uniqid() . ".webp";
            Image::make($req->image->getRealPath())->resize('300', '300')->save('uploads/image/' . $name);
            $img->uid = $uid;
            $img->img_id = $maxId;
            $img->type = "md";
            $img->image = $name;
            $img->active = "1";
            $status = $img->save();

            $img = new Img();
            $name =  uniqid() . ".webp";
            Image::make($req->image->getRealPath())->resize('700', '700')->save('uploads/image/' . $name);
            $img->uid = $uid;
            $img->img_id = $maxId;
            $img->type = "lg";
            $img->image = $name;
            $img->active = "1";
            $status = $img->save();
        }


        if ($status) {
            $user = User::Where('uid', $req->user()->uid)->first();
            $user->photo = "1";
            $user->update();
            if ($status) {
                return ApiRes::success("Profile image uploaded successfully !.");
            } else {
                return ApiRes::error();
            }
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
        } elseif ($req->action == "set-profile-picture") {

            $status = Img::Where('uid', $req->user()->uid)->update(['active' => "0"]);
            if ($status) {
                $status = Img::Where('uid', $req->user()->uid)->Where('img_id', $req->img_id)->update(['active' => "1"]);
                if ($status) {
                    return ApiRes::success("Profile Picture Set Successfully !");
                } else {
                    return ApiRes::error();
                }
                return ApiRes::success("Profile Picture Set Successfully !");
            } else {
                return ApiRes::error();
            }
        } elseif ($req->action == "delete") {
            $status = Img::Where('uid', $req->user()->uid)->Where('img_id', $req->img_id)->Where('type', "lg")->Where('active', '1')->first();
            if ($status) {
                return ApiRes::failed("You can't Delete Profile Picture !");
            } else {

                $img = Img::Where('uid', $req->user()->uid)->Where('img_id', $req->img_id)->delete();
                if ($img) {

                    return ApiRes::success("Image Deleted Successfully !");
                } else {
                    return ApiRes::error();
                }
            }
        } else {
            return  ApiRes::invalidAction();
        }
    }
    public function verifyUser(Request $req)
    {
        $validator = Validator::make($req->all(), [

            'phone' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            if ($errors->first('phone')) {
                return ApiRes::failed($errors->first('phone'));
            }
        }
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

    public function verifyUserPhone(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'phone' => 'required|unique:users|max:255',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            if ($errors->first('phone')) {
                return ApiRes::failed($errors->first('phone'));
            }
        }
        $status = $this->phoneValidation($req->phone);
        if ($status) {
            return ApiRes::success("success");
        } else {
            return ApiRes::failed("Please enter (10) digits mobile number .");
        }
    }

    public function regSendOtp(Request $req)
    {
        $status = $this->phoneValidation($req->phone);
        if ($status == true) {
            $user = User::Where('email', $req->email)->first();
            if (!$user || $user->email != $req->email) {
                return ApiRes::failed("User not found.");
            } else {

                $res = User::Where('email', $req->email)->update(['phone' => $req->phone, 'otp' => '123456']);
                if ($res) {
                    return ApiRes::otp("otp sent on your phone.", "123456");
                } else {
                    return ApiRes::error();
                }
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
                    return ApiRes::rlMsg("You login successfully !.", $user->uid, $token, $user->completed);
                } else {
                    return ApiRes::error();
                }
            } else {
                return ApiRes::failed("OTP not matched !.");
            }
        }
    }
    public function login(Request $req)
    {
        if ($req->email_phone == null && $req->email_phone == "") {
            return ApiRes::failed("Email id or Mobile Number Requred !");
        }
        if ($req->password == null && $req->password == "") {
            return ApiRes::failed(" Password Requred !");
        } else {
            $user = null;
            $user = User::Where('email',  $req->email_phone)->first();
            if ($user != null) {
                if ($user && Hash::check($req->password, $user->password)) {

                    $token = $user->createToken($user->uid)->plainTextToken;
                    $res = User::where('uid', $user->uid)->update([
                        "remember_token" => $token
                    ]);
                    if ($res) {
                        return ApiRes::rlMsg("You login successfully !.", $user->uid, $token, $user->completed);
                    } else {
                        return ApiRes::credentials();
                    }
                } else {
                    return ApiRes::credentials();
                }
            } else {
                $user = User::Where('phone',  'like', '%' . $req->email_phone . '%')->first();
                if ($user && Hash::check($req->password, $user->password)) {

                    $token = $user->createToken($user->uid)->plainTextToken;
                    $res = User::where('uid', $user->uid)->update([
                        "remember_token" => $token
                    ]);
                    if ($res) {
                        return ApiRes::rlMsg("You login successfully !.", $user->uid, $token, $user->completed);
                    } else {
                        return ApiRes::credentials();
                    }
                } else {
                    return ApiRes::credentials();
                }
            }
        }
    }
    public function data(Request $req)
    {
        // $user = User::where('uid', $req->user()->uid)->where('completed', '1')->with('imgsm')->with('imgmd')->with('imglg')->withCount('img')->with('shortlist', function ($shortlist) {
        //     return $shortlist->where('muid', auth()->user()->uid)->get();
        // })->with('interest', function ($interest) {
        //     return $interest->where('muid', auth()->user()->uid)->get();
        // })->with('visited', function ($visited) {
        //     return $visited->where('muid', auth()->user()->uid)->get();
        // })->get();

        $user = User::where('uid', $req->user()->uid)->with('imgsm')->with('imgmd')->with('imglg')->get();

        if ($user) {

            return ApiRes::data("User Details !.", $user);
        } else {
            return ApiRes::error();
        }
    }
    public function UserList(Request $req)
    {
        $user = null;
        if ($req->user()->gender == "Male") {
            $user = User::where('gender', 'Female')->where('completed', '1')->latest()->with('imgsm')->with('imgmd')->with('imglg')->withCount('img')->with('shortlist', function ($shortlist) {
                return $shortlist->where('muid', auth()->user()->uid)->get();
            })->with('interest', function ($interest) {
                return $interest->where('muid', auth()->user()->uid)->get();
            })->with('visited', function ($visited) {
                return $visited->where('muid', auth()->user()->uid)->get();
            })->get();
        } elseif ($req->user()->gender == "Female") {
            $user = User::where('gender', 'Male')->where('completed', '1')->latest()->with('imgsm')->with('imgmd')->with('imglg')->withCount('img')->with('shortlist', function ($shortlist) {
                return $shortlist->where('muid', auth()->user()->uid)->get();
            })->with('interest', function ($interest) {
                return $interest->where('muid', auth()->user()->uid)->get();
            })->with('visited', function ($visited) {
                return $visited->where('muid', auth()->user()->uid)->get();
            })->get();
        }


        if ($user) {
            return ApiRes::data("User List !.", $user);
        } else {
            return ApiRes::error();
        }
        // $user = User::whereNotIn('id', [$req->user()->id])->where('completed', '1')->latest()->with('imgsm')->with('imgmd')->with('imglg')->withCount('img')->with('shortlist', function ($shortlist) {
        //     return $shortlist->where('muid', auth()->user()->uid)->get();
        // })->with('interest', function ($interest) {
        //     return $interest->where('muid', auth()->user()->uid)->get();
        // })->with('visited', function ($visited) {
        //     return $visited->where('muid', auth()->user()->uid)->get();
        // })->get();

        // if ($user) {
        //     return ApiRes::data("User List !.", $user);
        // } else {
        //     return ApiRes::error();
        // }
    }
    public function search(Request $req)
    {
        if ($req->action == "search-by-id") {
            return $this->searchById($req);
        } elseif ($req->action == "advance-search") {
            return $this->advanceSearch($req);
        }
    }
    public function myMatches(Request $req)
    {


        $user = null;
        if ($req->user()->gender == "Male") {
            $pp = PartnerPreference::Where('uid', $req->user()->uid)->first();
            $user = User::Where('gender', "Female")
                ->WhereBetween('age', [$pp->age_from, $pp->age_to])
                ->WhereBetween('height', [$pp->height_from, $pp->height_to])
                ->Where('marrital_status', $pp->marrital_status)
                // ->orWhere('physical_status', $pp->physical_status)
                // ->orWhere('diet', $pp->diet)
                // ->orWhere('smoking', $pp->smoking)
                // ->orWhere('drinking', $pp->drinking)
                ->Where('religion', $pp->religion)
                ->Where('community', $pp->caste)
                ->Where('mother_tounge', $pp->mother_tounge)
                ->Where('country', $pp->country)
                ->Where('state', $pp->state)
                ->Where('city', $pp->city)
                ->Where('highest_education', $pp->highest_education)
                ->Where('occupation', $pp->occupation)
                // ->orWhere('annual_income', $pp->annual_income)
                ->where('completed', '1')->with('imgsm')->with('imgmd')->with('imglg')->withCount('img')->with('shortlist', function ($shortlist) {
                    return $shortlist->where('muid', auth()->user()->uid)->get();
                })->with('interest', function ($interest) {
                    return $interest->where('muid', auth()->user()->uid)->get();
                })->with('visited', function ($visited) {
                    return $visited->where('muid', auth()->user()->uid)->get();
                })->get();
        } elseif ($req->user()->gender == "Female") {
            $pp = PartnerPreference::Where('uid', $req->user()->uid)->first();
            $user = User::Where('gender', "Male")
                ->WhereBetween('age', [$pp->age_from, $pp->age_to])
                ->WhereBetween('height', [$pp->height_from, $pp->height_to])
                ->Where('marrital_status', $pp->marrital_status)
                // ->orWhere('physical_status', $pp->physical_status)
                // ->orWhere('diet', $pp->diet)
                // ->orWhere('smoking', $pp->smoking)
                // ->orWhere('drinking', $pp->drinking)
                ->Where('religion', $pp->religion)
                ->Where('community', $pp->caste)
                ->Where('mother_tounge', $pp->mother_tounge)
                ->Where('country', $pp->country)
                ->Where('state', $pp->state)
                ->Where('city', $pp->city)
                ->Where('highest_education', $pp->highest_education)
                ->Where('occupation', $pp->occupation)
                // ->orWhere('annual_income', $pp->annual_income)
                ->where('completed', '1')->with('imgsm')->with('imgmd')->with('imglg')->withCount('img')->with('shortlist', function ($shortlist) {
                    return $shortlist->where('muid', auth()->user()->uid)->get();
                })->with('interest', function ($interest) {
                    return $interest->where('muid', auth()->user()->uid)->get();
                })->with('visited', function ($visited) {
                    return $visited->where('muid', auth()->user()->uid)->get();
                })->get();
        }




        if ($user) {
            return ApiRes::data("Matches List !.", $user);
        } else {
            return ApiRes::error();
        }
    }
    public function advanceSearch(Request $req)
    {

        $user = User::whereNotIn('id', [$req->user()->id])->where('completed', '1')
            ->with('imgsm')->with('imgmd')->with('imglg')->withCount('img')->with('shortlist', function ($shortlist) {
                return $shortlist->where('muid', auth()->user()->uid)->get();
            })->with('interest', function ($interest) {
                return $interest->where('muid', auth()->user()->uid)->get();
            })->with('visited', function ($visited) {
                return $visited->where('muid', auth()->user()->uid)->get();
            })->get();
        if ($req->user()->gender == "Male") {
            $user = $user->where('gender', "Female");
        } else if ($req->user()->gender == "Female") {
            $user = $user->where('gender', "Male");
        }
        // age
        if ($req->age_from != null && $req->age_to != null) {
            $user =  $user->whereBetween('age', [$req->age_from, $req->age_to]);
        }
        // height
        if ($req->height_from != null && $req->height_to != null) {
            $user = $user->whereBetween('height', [$req->height_from, $req->height_to]);
        }
        if ($req->marrital_status != null) {
            $user = $user->where('marrital_status', $req->marrital_status);
        }
        if ($req->physical_status != null) {
            $user = $user->where('physical_status', $req->physical_status);
        }
        if ($req->country != null) {
            $user = $user->where('country', $req->country);
        }
        if ($req->state != null) {
            $user = $user->where('state', $req->state);
        }
        if ($req->city != null) {
            $user = $user->where('city', $req->city);
        }
        // religion
        if ($req->religion != null) {
            $user = $user->where('religion', $req->religion);
        }
        if ($req->caste != null) {
            $user = $user->where('community', $req->caste);
        }
        if ($req->mother_tounge != null) {
            $user = $user->where('mother_tounge', $req->mother_tounge);
        }


        if ($req->education != null) {
            $user = $user->where('highest_education', $req->education);
        }
        if ($req->occupation != null) {
            $user = $user->where('occupation', $req->occupation);
        }
        if ($req->annual_income != null) {
            $user = $user->where('annual_income', $req->annual_income);
        }
        if ($req->diet != null) {
            $user = $user->where('diet', $req->diet);
        }
        if ($req->smoking != null) {
            $user = $user->where('smoking', $req->smoking);
        }
        if ($req->drinking != null) {
            $user = $user->where('drinking', $req->drinking);
        }








        if ($user) {
            return ApiRes::data("User Details !.", $user->values()->all());
        } else {
            return ApiRes::error();
        }
    }
    public function searchById(Request $req)
    {
        if ($req->uid == "" || $req->uid == null) {
            return ApiRes::failed("* Matri Id or Name Required !");
        }
        $user = User::orWhere('uid', $req->uid)->orWhere('first_name', 'like', '%' . $req->uid . '%')->orWhere('last_name', 'like', '%' . $req->uid . '%')->where('completed', '1')->latest()->with('imgsm')->with('imgmd')->with('imglg')->withCount('img')->with('shortlist', function ($shortlist) {
            return $shortlist->where('muid', auth()->user()->uid)->get();
        })->with('interest', function ($interest) {
            return $interest->where('muid', auth()->user()->uid)->get();
        })->with('visited', function ($visited) {
            return $visited->where('muid', auth()->user()->uid)->get();
        })->get();



        if ($user) {
            return ApiRes::data("User Details !.", $user);
        } else {
            return ApiRes::failed("User Not Found !");
        }
    }
    public function nearMe(Request $req)
    {
        if ($req->user()->gender == "Male") {
            $user = User::WhereNotIn('id', [$req->user()->id])->Where('gender', "Female")->Where('country', $req->user()->country)->Where('state', $req->user()->state)->Where('city', $req->user()->city)->Where('completed', '1')->latest()->with('imgsm')->with('imgmd')->with('imglg')->withCount('img')->with('shortlist', function ($shortlist) {
                return $shortlist->where('muid', auth()->user()->uid)->get();
            })->with('interest', function ($interest) {
                return $interest->where('muid', auth()->user()->uid)->get();
            })->with('visited', function ($visited) {
                return $visited->where('muid', auth()->user()->uid)->get();
            })->get();
        } else if ($req->user()->gender == "Female") {
            $user = User::WhereNotIn('id', [$req->user()->id])->Where('gender', "Male")->Where('country', $req->user()->country)->Where('state', $req->user()->state)->Where('city', $req->user()->city)->Where('completed', '1')->latest()->with('imgsm')->with('imgmd')->with('imglg')->withCount('img')->with('shortlist', function ($shortlist) {
                return $shortlist->where('muid', auth()->user()->uid)->get();
            })->with('interest', function ($interest) {
                return $interest->where('muid', auth()->user()->uid)->get();
            })->with('visited', function ($visited) {
                return $visited->where('muid', auth()->user()->uid)->get();
            })->get();
        }
        // $user = User::WhereNotIn('id', [$req->user()->id])->Where('country', $req->user()->country)->Where('state', $req->user()->state)->Where('city', $req->user()->city)->Where('completed', '1')->latest()->with('imgsm')->with('imgmd')->with('imglg')->withCount('img')->with('shortlist', function ($shortlist) {
        //     return $shortlist->where('muid', auth()->user()->uid)->get();
        // })->with('interest', function ($interest) {
        //     return $interest->where('muid', auth()->user()->uid)->get();
        // })->with('visited', function ($visited) {
        //     return $visited->where('muid', auth()->user()->uid)->get();
        // })->get();



        if ($user) {
            return ApiRes::data("Near Me list !.", $user);
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
        } elseif ($req->action == "physical-attributes-update") {
            return $this->physicalAttributesUpdate($req);
        } elseif ($req->action == "horoscope-info-update") {
            return $this->horoscopeInfoUpdate($req);
        } elseif ($req->action == "reg-step-1") {
            return $this->regStep1($req);
        } elseif ($req->action == "acc-details") {
            return $this->accDetails($req);
        } elseif ($req->action == "personal-details") {
            return $this->personalDetails($req);
        } elseif ($req->action == "physical-details") {
            return $this->physicalDetails($req);
        } elseif ($req->action == "edu-occ-details") {
            return $this->eduOccDetails($req);
        } elseif ($req->action == "habit-details") {
            return $this->habitDetails($req);
        } elseif ($req->action == "family-details") {
            return $this->familyDetails($req);
        } elseif ($req->action == "about-me") {
            return $this->aboutMe($req);
        } elseif ($req->action == "online") {
            return $this->onlineUpdate($req);
        } else {
            return  ApiRes::invalidAction();
        }
    }
    public function regStep1(Request $req)
    {
        $user = User::Where('uid', $req->user()->uid)->first();
        $user->state = $req->state;
        $user->city = $req->city;
        $user->marrital_status = $req->marrital_status;
        $user->diet = $req->diet;
        $user->height = $req->height;
        $user->sub_community = $req->sub_community;
        $user->marry_other_caste = $req->marry_other_caste;

        $status = $user->update();
        if ($status) {
            return ApiRes::success("Profile Update Successfully !.");
        } else {
            return ApiRes::error();
        }
    }
    public function forgetPassword(Request $req)
    {
        $user = User::Where('phone',   $req->phone)->first();
        $user->password = Hash::make($req->password);
        $status = $user->update();
        if ($status) {
            return ApiRes::success("Password Changed Successfully !.");
        } else {
            return ApiRes::error();
        }
    }
    public function resetPassword(Request $req)
    {
        $user = User::Where('uid', $req->user()->uid)->first();
        if (Hash::check($req->password, $user->password)) {
            $user->password = Hash::make($req->new_password);
            $status = $user->update();
            if ($status) {
                return ApiRes::success("Password Updated Successfully !.");
            } else {
                return ApiRes::error();
            }
        } else {
            return ApiRes::failed("Old Password Incorrect !");
        }
    }
    public function accDetails(Request $req)
    {
        $error =  $this->accDetailsValidation($req);
        if ($error) {
            return $error;
        }

        $user = User::Where('uid', $req->user()->uid)->first();
        $user->first_name = $req->first_name;
        $user->last_name = $req->last_name;
        $user->phone = $req->phone;
        $user->email = $req->email;
        $user->password = Hash::make($req->password);
        $status = $user->update();
        if ($status) {
            return ApiRes::success("Profile Update Successfully !.");
        } else {
            return ApiRes::error();
        }
    }
    public function personalDetails(Request $req)
    {
        $error =  $this->personalDetailsValidation($req);
        if ($error) {
            return $error;
        }

        $user = User::Where('uid', $req->user()->uid)->first();
        $user->marrital_status = $req->marrital_status;
        $user->sub_community = $req->sub_community;
        $user->marry_other_caste = $req->marry_other_caste;
        $user->state = $req->state;
        $user->city = $req->city;

        $status = $user->update();
        if ($status) {
            return ApiRes::success("Profile Update Successfully !.");
        } else {
            return ApiRes::error();
        }
    }
    public function physicalDetails(Request $req)
    {
        $error =  $this->physicalDetailsValidation($req);
        if ($error) {
            return $error;
        }

        $user = User::Where('uid', $req->user()->uid)->first();
        $user->height = $req->height;
        $user->weight = $req->weight;
        $user->body_type = $req->body_type;
        $user->complexion = $req->complexion;
        $user->physical_status = $req->physical_status;

        $status = $user->update();
        if ($status) {
            return ApiRes::success("Profile Update Successfully !.");
        } else {
            return ApiRes::error();
        }
    }
    public function eduOccDetails(Request $req)
    {
        $error =  $this->eduOccDetailsValidation($req);
        if ($error) {
            return $error;
        }

        $user = User::Where('uid', $req->user()->uid)->first();
        $user->highest_education = $req->highest_education;
        $user->additional_degree = $req->additional_degree;
        $user->occupation = $req->occupation;
        $user->employed_in = $req->employed_in;
        $user->annual_income = $req->annual_income;

        $status = $user->update();
        if ($status) {
            return ApiRes::success("Profile Update Successfully !.");
        } else {
            return ApiRes::error();
        }
    }
    public function habitDetails(Request $req)
    {
        $error =  $this->habitDetailsValidation($req);
        if ($error) {
            return $error;
        }

        $user = User::Where('uid', $req->user()->uid)->first();
        $user->diet = $req->diet;
        $user->smoking = $req->smoking;
        $user->drinking = $req->drinking;


        $status = $user->update();
        if ($status) {
            return ApiRes::success("Profile Update Successfully !.");
        } else {
            return ApiRes::error();
        }
    }
    public function familyDetails(Request $req)
    {
        $error =  $this->familyDetailsValidation($req);
        if ($error) {
            return $error;
        }

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
            return ApiRes::success("Profile Update Successfully !.");
        } else {
            return ApiRes::error();
        }
    }
    public function aboutMe(Request $req)
    {
        $error =  $this->aboutMeValidation($req);
        if ($error) {
            return $error;
        }

        $user = User::Where('uid', $req->user()->uid)->first();
        $user->about_me = $req->about_me;
        $status = $user->update();
        if ($status) {
            return ApiRes::success("Profile Update Successfully !.");
        } else {
            return ApiRes::error();
        }
    }

    public function onlineUpdate(Request $req)
    {
        $user = User::Where('uid', $req->user()->uid)->first();
        $user->online = $req->online;
        $status = $user->update();
        if ($status) {
            return ApiRes::success("Update Successfully !.");
        } else {
            return ApiRes::error();
        }
    }











    // =================================================================
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

        // $phone = $this->phoneValidation($req->phone);
        // if ($phone == false) {
        //     return ApiRes::failed("Please enter 10 (Digit) phone no.");
        // }
    }
    public function personalDetailsValidation(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'marrital_status' => 'required|max:255',
            'sub_community' => 'required|max:255',
            'marry_other_caste' => 'required|max:255',
            'state' => 'required|max:255',
            'city' => 'required|max:255',

        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            if ($errors->first('marrital_status')) {
                return ApiRes::failed($errors->first('marrital_status'));
            } else if ($errors->first('sub_community')) {
                return ApiRes::failed($errors->first('sub_community'));
            } else if ($errors->first('marry_other_caste')) {
                return ApiRes::failed($errors->first('marry_other_caste'));
            } else if ($errors->first('state')) {
                return ApiRes::failed($errors->first('state'));
            } else if ($errors->first('city')) {
                return ApiRes::failed($errors->first('city'));
            }
        }
    }
    public function  accDetailsValidation(Request $req)
    {
        $validator = Validator::make($req->all(), [

            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:6',



        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            if ($errors->first('first_name')) {
                return ApiRes::failed($errors->first('first_name'));
            } else if ($errors->first('last_name')) {
                return ApiRes::failed($errors->first('last_name'));
            } else if ($errors->first('email')) {
                return ApiRes::failed($errors->first('email'));
            } else if ($errors->first('password')) {
                return ApiRes::failed($errors->first('password'));
            }
        }

        $phone = $this->phoneValidation($req->phone);
        if ($phone == false) {
            return ApiRes::failed("Please enter 10 (Digit) phone no.");
        }
    }
    public function physicalDetailsValidation(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'height' => 'required|max:255',
            'weight' => 'required|max:255',
            'complexion' => 'required|max:255',
            'physical_status' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            if ($errors->first('height')) {
                return ApiRes::failed($errors->first('height'));
            } else if ($errors->first('weight')) {
                return ApiRes::failed($errors->first('weight'));
            } else if ($errors->first('complexion')) {
                return ApiRes::failed($errors->first('complexion'));
            } else if ($errors->first('physical_status')) {
                return ApiRes::failed($errors->first('physical_status'));
            }
        }
    }
    public function eduOccDetailsValidation(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'highest_education' => 'required|max:255',
            'occupation' => 'required|max:255',
            'employed_in' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            if ($errors->first('highest_education')) {
                return ApiRes::failed($errors->first('highest_education'));
            } else if ($errors->first('occupation')) {
                return ApiRes::failed($errors->first('occupation'));
            } else if ($errors->first('employed_in')) {
                return ApiRes::failed($errors->first('employed_in'));
            }
        }
    }
    public function habitDetailsValidation(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'diet' => 'required|max:255',
            'smoking' => 'required|max:255',
            'drinking' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            if ($errors->first('diet')) {
                return ApiRes::failed($errors->first('diet'));
            } else if ($errors->first('smoking')) {
                return ApiRes::failed($errors->first('smoking'));
            } else if ($errors->first('drinking')) {
                return ApiRes::failed($errors->first('drinking'));
            }
        }
    }
    public function familyDetailsValidation(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'family_status' => 'required|max:255',
            'family_type' => 'required|max:255',
            'family_values' => 'required|max:255',
            'no_married_sisters' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            if ($errors->first('family_status')) {
                return ApiRes::failed($errors->first('family_status'));
            } else if ($errors->first('family_type')) {
                return ApiRes::failed($errors->first('family_type'));
            } else if ($errors->first('family_values')) {
                return ApiRes::failed($errors->first('family_values'));
            } else if ($errors->first('no_married_sisters')) {
                return ApiRes::failed($errors->first('no_married_sisters'));
            }
        }
    }
    public function aboutMeValidation(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'about_me' => 'required|max:255',

        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            if ($errors->first('about_me')) {
                return ApiRes::failed($errors->first('about_me'));
            }
        }
    }
    public function phoneValidation($phone)
    {



        $arr = str_split($phone);
        $len = count($arr);
        if ($len <= 11) {
            return false;
        }
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
