<?php

namespace App\Http\Controllers\Api\Profile;

use App\Helper\ApiRes;
use App\Http\Controllers\Api\MailController;
use App\Http\Controllers\Controller;
use App\Models\Img;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

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
    public function uploadImg(Request $req)
    {
        $imgPath = "public/users/imgs/";
        $status = false;
        $uid = $req->user()->uid;
        $maxId = Img::where('uid', $uid)->max('img_id') + 1;
        $validator = Validator::make($req->all(), [
            'image' => 'required|mimes:jpeg,jpg'
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            if ($errors->first('image')) {
                return ApiRes::failed($errors->first('image'));
            }
        }


        if ($req->hasFile('image')) {

            $img = new Img();
            $name =  uniqid() . ".webp";
            Image::make($req->image->getRealPath())->resize('350', '350')->save($imgPath . $name);
            $img->uid = $uid;
            $img->img_id = $maxId;
            $img->type = "md";
            $img->image = $imgPath . $name;
            $status = $img->save();

            $img = new Img();
            $name =  uniqid() . ".webp";
            Image::make($req->image->getRealPath())->resize('750', '750')->save($imgPath . $name);
            $img->uid = $uid;
            $img->img_id = $maxId;
            $img->type = "lg";
            $img->image = $imgPath . $name;
            $status = $img->save();
            // for last image active at profile
            $status = Img::where('uid', $uid)->update(['active' => '0']);
            $img = Img::where('uid', $uid)->where('type', 'lg')->latest()->first();
            $img->active = '1';
            $status = $img->save();
            $img = Img::where('uid', $uid)->where('type', 'md')->latest()->first();
            $img->active = '1';
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
    public function uploadDocument(Request $req)
    {
        $filePath = "public/users/documents/";
        $status = false;
        $user = User::Where('uid', $req->user()->uid)->first();
        $emailVFC =  uniqid();
        $validator = Validator::make($req->all(), [
            'document' => 'required|mimes:jpeg,jpg'
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            if ($errors->first('document')) {
                return ApiRes::failed($errors->first('document'));
            }
        }
        if ($req->hasFile('document')) {


            if ($user->document_img != null) {
                // delete old file
                File::delete($user->document_img);
            }
            $name =  uniqid() . ".webp";
            Image::make($req->document->getRealPath())->resize('450', '450')->save($filePath . $name);
            $user->document_img = $filePath . $name;
            $user->document = "1";
            $user->status = "1";
            $user->completed = "1";
            $user->email_verification_code = $emailVFC;
            $status = $user->update();
        }


        if ($status) {
            $mail = new MailController();
            $url = url('') . "/email-varification/" . $emailVFC;
            $body = "<H1> Best Humsafar </H1> <br>
                     <p>Thank you for registration </p>
                     <a href='$url'> verify email </a>
            ";
            $mail->regMail($user->email, $body);

            return ApiRes::success("Document uploaded successfully !.");
        } else {
            return ApiRes::error();
        }
    }
    public function photoPrivacy(Request $req)
    {

        try {
            $user = User::Where('uid', $req->user()->uid)->first();
            $user->photo_privacy = $req->photo_privacy;
            $user->update();
            return ApiRes::success("Photo privacy updated successfully !.");
        } catch (\Throwable $th) {
            //throw $th;
            return ApiRes::failed($th->getMessage());
        }
    }
    public function phonePrivacy(Request $req)
    {
        try {
            $user = User::Where('uid', $req->user()->uid)->first();
            $user->phone_privacy = $req->phone_privacy;
            $user->update();
            return ApiRes::success("Phone privacy updated successfully !.");
        } catch (\Throwable $th) {
            //throw $th;
            return ApiRes::error();
        }
    }
}
