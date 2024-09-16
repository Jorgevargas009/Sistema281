<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class logoutController extends Controller
{
    public function logout(){
        Session()->flush();
        Auth::logout();
        return redirect()->route('login');
    }
}
