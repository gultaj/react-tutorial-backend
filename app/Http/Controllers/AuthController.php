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
        if (!is_null($token = $request->input('token'))) {
            if ($user = User::where('remember_token', $token)->first()) {
                $user->remember_token = null;
                $user->save();
                return response(['success' => true])->header('Access-Control-Allow-Origin', '*');
            }
        }
        return response(['success' => false, 'message' => 'User not found'])
            ->header('Access-Control-Allow-Origin', '*');
    }

    public function register(Request $request)
    {
        $rules = [
            'email' => 'required|unique:users|email',
            'nickname' => 'required|unique:users|max:50',
            'password' => 'required|min:6|max:12|confirmed'
        ];
        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response(['success' => false, 'message' => $validator->errors()->first()])->header('Access-Control-Allow-Origin', '*');
        }
        User::create($request->only('email', 'nickname', 'password'));
        return response(['success' => true])->header('Access-Control-Allow-Origin', '*');
    }

    public function getUser(Request $request)
    {
        $token = $request->input('token');
        $validator = \Validator::make([$token], ['token' => 'required|min:1']);
        if ($validator->fails() and !($user = User::loggedUser($token)->first())) {
            return response(['success' => false])->header('Access-Control-Allow-Origin', '*');
        }
        return response(['success' => true, 'user' => $user])->header('Access-Control-Allow-Origin', '*');
    }
    
}
