<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $rules = [
            'email' => 'required|email',
            'password' => 'required'
        ];
        $user = User::where('email', $request->input('email'))->first();
        if (!$this->validate($request, $rules) and $user) {
            if (Hash::check($request->input('password'), $user->password)) {
                $user->remember_token = bin2hex(random_bytes(50));
                $user->save();
                $user = collect($user)->only('id', 'nickname', 'remember_token');
                return response(['success' => true, 'user' => $user])
                    ->header('Access-Control-Allow-Origin', '*');
            }
        }
        return response(['success' => false, 'message' => 'Invalid email or password.'])
            ->header('Access-Control-Allow-Origin', '*');
    }

    public function logout(Request $request)
    {
        if (!is_null($token = $request->input('remember_token'))) {
            if ($user = User::where('remember_token', $token)->first()) {
                $user->remember_token = null;
                $user->save();
                return response(['success' => true])->header('Access-Control-Allow-Origin', '*');
            }
        }
        return response(['success' => false, 'message' => 'User not found'])
            ->header('Access-Control-Allow-Origin', '*');
    }

    
}
