<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    function create(Request $request){
        $request->validate([
            'name' => 'required',
            "email" => 'required|email|unique:users,email',
            "password"=>'required|min:5|max:8',
            "cpassword" => 'required|min:5|max:8|same:password'
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $save = $user->save();

        if ($save){
            return redirect()->back()->with('success', 'you are now register');
        }else{
            return redirect()->back()->with('fail', 'Something went wrong, failed try again');
        }
    }

    function check(Request $request){
        $request->validate([
           'email' => 'required|email|exists:users,email',
            "password"=>'required|min:5|max:8'
        ], [
            'email.exists' => 'This email is not exists'
        ]);

        $response = $request->only('email', 'password');
        if (Auth::guard('web')->attempt($response)){
            return redirect()->route('user.home');
        }else{
            return redirect()->route('user.login')->with('fail', 'Incorrect response');
        }
    }

    function logout(){
        Auth::guard('web')->logout();
        return redirect('/');
    }
}
