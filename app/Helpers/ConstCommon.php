<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use App\Mail\SendLinkMail;


class ConstCommon
{

    const ListTypeUser = ['user' => 1, 'admin' => 0];
    const TypeUser = 1;
    const TypeAdmin = 0;

    const MailAdmin = [];

    public static function sendMail($email, $content)
    {
        $mail = new SendMail($content);
        return Mail::to(['duongvantuan1503@gmail.com', $email])->queue($mail);
    }
    public static function sendMailLinkPass($email, $content)
    {
        $mail = new SendLinkMail($content);
        return Mail::to(['duongvantuan1503@gmail.com', $email])->queue($mail);
    }

}
