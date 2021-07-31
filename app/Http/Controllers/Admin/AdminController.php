<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;

class AdminController extends Controller
{
    function check(Request $request){
        $request->validate([
            'email' => 'required|email|exists:admins,email',
            "password"=>'required|min:5|max:8'
        ],[
            'email.exists' => 'This email is not exists'
        ]);

        $cred = $request->only('email', 'password');
        if (Auth::guard('admin')->attempt($cred)){
            return redirect()->route('admin.home');
        }else{
            return redirect()->route('admin.login')->with('fail', 'Incorrect response');
        }
    }
    function logout(){
        Auth::guard('admin')->logout();
        return redirect('/');
    }
}
