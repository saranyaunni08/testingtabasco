<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login()
    {
        return view('pages.login');
    }

    public function doLogin()
    {
        // Authentication code
    }

    public function forgotPassword()
    {
        return view('pages.reset-password');
    }

    public function logout() {
        
    }
}
