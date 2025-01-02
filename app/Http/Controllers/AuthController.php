<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function loginUser(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if( Auth::attempt([
            'email' => $request->get('email'),
            'password' => $request->get('password')
        ]) ) 
        {
            return redirect('/admin');
        }
        
        return redirect()->back()->with('status', 'Неправильный Email или Пароль!');
    }

    public function logout()
    {
        Auth::logout();

        return redirect('/admin_login');
    }
}
