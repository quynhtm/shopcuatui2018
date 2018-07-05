<?php

namespace App\Http\Controllers;

use App\Mail\MailSystem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;


class MailSendController extends Controller
{
    public function sentEmail(){
        Mail::to('manhquynh1984@gmail.com')->send(new MailSystem());
    }
}
