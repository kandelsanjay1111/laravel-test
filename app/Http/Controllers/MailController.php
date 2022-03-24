<?php

namespace App\Http\Controllers;
use Mail;
use App\Mail\TestMail;

use Illuminate\Http\Request;

class MailController extends Controller
{
    public function sendEmail()
    {
       Mail::to('sanjay.kandel@readytowork.work')->send(new TestMail);
       dd('email is send to the user. check the inbox');
    }

    public function test()
    {
        $users=[
            'name'=>['Sanjay Kandel','another user'],
            'user'=>'sanjay002@gmail.com'
        ];
        $name=\Arr::get($users,'name');
        dd($name);
    }
}
