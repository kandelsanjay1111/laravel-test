<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use App\Mail\TestMail;

class EmailController extends Controller
{
    public function sendMail()
    {
        $tasks=[
            'solve the problem',
            'get the work done',
            'inform the manager'
        ];

        Mail::to('kandelsanjay1111@gmail.com')->send(new Testmail($tasks));
        dd('email is send with the tasks');
    }
}
