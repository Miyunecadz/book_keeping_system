<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserAuthenticationController extends Controller
{
    public function login()
    {
        return view('user.login');
    }

    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'username' => 'required',
            'password' => 'required'
        ]);

        if($validator->fails())
        {
            return redirect(route('user.login'))->withErrors($validator->errors())->withInput();
        }

        $fieldType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        if(Auth::attempt([$fieldType => $request->username, 'password' => $request->password]))
        {
            $request->session()->regenerate();
            return redirect()->intended(route('user.dashboard'));
        }

        return redirect(route('user.login'))->withErrors([
            'username' => 'The provided credentials do not match our records'
        ])->onlyInput('username');
    }

    public function dashboard()
    {
        return view('user.dashboard');
    }
}
