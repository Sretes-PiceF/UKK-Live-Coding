<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function  ViewLogin()
    {
        return view('Auth.Login');
    }

    public function ViewRegister()
    {
        return view('Auth.Register');
    }
}
