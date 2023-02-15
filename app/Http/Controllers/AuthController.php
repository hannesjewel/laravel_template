<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request){

        $validator = Validator::make($request->all(), [
            "name" => "required|string|max:200",
            "email" => "required|email|unique:users",
            "password" => "required|confirmed"
        ]);

        if($validator->fails()){
            return response($validator->errors(), 403);
        }

        $validated = $validator->validated();

        $validated['password'] = bcrypt($request->password);

        $user = User::create($validated);

        $token = $user->createToken("My Secret Token")->accessToken;

        return response($token, 200);
    }

    public function login(Request $request){

        $validator = Validator::make($request->all(), [
            "email" => "required|email",
            "password" => "required"
        ]);

        if($validator->fails()){
            return response($validator->errors(), 403);
        }

        $validated = $validator->validated();

        if(!auth()->attempt($validated)){
            return response('The username or password you entered does not exist', 403);
        }

        $user = Auth::user();

        if($user->role !== 'admin'){
            return response('You are not authorized', 403);
        }

        $token = $user->createToken("My Secret Token")->accessToken;

        return response($token, 200);
    }

    public function logout(Request $request){
        Auth::user()->token()->revoke();
        return response('logged out', 200);
    }

    public function details(){
        return response(Auth::user(), 200);
    }

    public function update(Request $request){

        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            "name" => "required|string|max:200",
            "email" => "required|email|unique:users,email,".$user->id,
            "password" => "confirmed"
        ]);

        if($validator->fails()){
            return response($validator->errors(), 403);
        }

        $validated = $validator->validated();

        if($request->password){
            $validated['password'] = bcrypt($request->password);
        }   

        $user->update($validated);

        return response($user, 200);
    }
}
