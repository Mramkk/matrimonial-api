<?php

namespace App\Helper;

class ApiRes
{
    public static function invalidAction()
    {

        return response()->json(["status" => false, "message" => "Invalid action type"]);
    }
    public static function invalidUser()
    {
        return response()->json(["status" => false, "message" => "Invalid user"]);
    }
    public static function credentials()
    {

        return response()->json(["status" => false, "message" => "Invalid login credentials"]);
    }
    public static function error()
    {
        return response()->json(["status" => false, "message" => "Error ! please try again later."]);
    }
    public static function failed($msg)
    {

        return response()->json(["status" => false, "message" => $msg]);
    }
    public static function inactiveUser()
    {
        return response()->json(["status" => false, "message" => "Account Deactive User !"]);
    }
    public static function rlMsg($msg, $uid, $token, $completed)
    {
        return response()->json(["status" => true, "message" => $msg, "uid" => $uid, "token" => $token, "completed" => $completed]);
    }
    public static function data($msg, $data)
    {
        return response()->json(["status" => true, "message" => $msg, "data" => $data]);
    }
    public static function otp($msg, $otp)
    {

        return response()->json(["status" => true, "message" => $msg, "otp" => $otp]);
    }
    public static function success($msg)
    {

        return response()->json(["status" => true, "message" => $msg]);
    }
    public static function logout()
    {

        return response()->json(["status" => true, "message" => "You logout successfully !"]);
    }
}
