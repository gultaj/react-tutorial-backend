<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $rules = [
            'email' => 'required|email',
            'password' => 'required'
        ];
        $user = \App\User::where('email', $request->input('email'))->first();
        if (!$this->validate($request, $rules) and $user) {
            if (Hash::check($request->input('password'), $user->password)) {
                $user->remember_token = bin2hex(random_bytes(50));
                $user->save();
                $user = collect($user)->only('id', 'nickname', 'remember_token');
                return response(['success' => true, 'user' => $user])->header('Access-Control-Allow-Origin', '*');
            }
        }
        return response(['success' => false, 'message' => 'Invalid email or password.'])
            ->header('Access-Control-Allow-Origin', '*');

    }

    
}
