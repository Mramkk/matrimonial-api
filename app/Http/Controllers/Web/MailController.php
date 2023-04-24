<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class MailController extends Controller
{
    public function verifyRegEmail(Request $req)
    {
        if ($req->code != null) {
            $user =  User::Where('email_verification_code', $req->code)->first();
            $user->email_verified = "1";
            $status = $user->update();
            $str = "";
            if ($status) {
                $str = "Email Verified Successfully !";
                $data = compact('str');
                return view('email-varification')->with($data);
            } else {
                $str = "Error ! Verification Code Invalied.";
                $data = compact('str');
                return view('email-varification')->with($data);;
            }
        }
    }
}
