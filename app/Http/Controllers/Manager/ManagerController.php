<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Manager;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ManagerController extends Controller
{
    function create(Request $request){
        $request->validate([
            'name' => 'required',
            'description'=> 'required',
            "email" => 'required|email|unique:users,email',
            "password"=>'required|min:5|max:8',
            "cpassword" => 'required|min:5|max:8|same:password'
        ]);

        $manager = new Manager();
        $manager->name = $request->name;
        $manager->description = $request->description;
        $manager->email = $request->email;
        $manager->password = Hash::make($request->password);
        $save = $manager->save();

        if ($save){
            return redirect()->back()->with('success', 'you are now register');
        }else{
            return redirect()->back()->with('fail', 'Something went wrong, failed try again');
        }
    }
    function check(Request $request){
        $request->validate([
            'email' => 'required|email|exists:managers,email',
            "password"=>'required|min:5|max:8'
        ], [
            'email.exists' => 'This email is not exists'
        ]);
        $cred = $request->only('email', 'password');
        if (Auth::guard('manager')->attempt($cred)){
            return redirect()->route('manager.home');
        }else{
            return redirect()->route('manager.login')->with('fail', 'Incorrect response');
        }
    }
    function logout(){
        Auth::guard('manager')->logout();
        return redirect('/');
    }

}
