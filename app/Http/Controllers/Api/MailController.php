<?php

namespace App\Http\Controllers\Api;

use App\Helper\ApiRes;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailController extends Controller
{
    public function regMail($email, $body)
    {
        require base_path("vendor/autoload.php");
        $mail = new PHPMailer(true);     // Passing `true` enables exceptions

        try {

            // Email server settings
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = 'mail.jissoftware.in';             //  smtp host
            $mail->SMTPAuth = true;
            $mail->Username = 'besthumsafar@jissoftware.in';   //  sender username
            $mail->Password = 'X;RD?.OD0Odo';       // sender password
            $mail->SMTPSecure = 'ssl';                  // encryption - ssl/tls
            $mail->Port = 465;                          // port - 587/465

            $mail->setFrom('besthumsafar@jissoftware.in', 'Best Humsafar');
            $mail->addAddress($email);
            $mail->isHTML(true);                // Set email content format to HTML

            $mail->Subject = "Email Verification";
            $mail->Body    = $body;

            // $mail->AltBody = plain text version of email body;

            if (!$mail->send()) {
                return ApiRes::failed("Error ! Verification Email not sent.");
            } else {

                return ApiRes::success("Verification email  has been sent.");
            }
        } catch (Exception $e) {

            return ApiRes::failed("Error ! Verification email could not be sent.");
        }
    }
}
