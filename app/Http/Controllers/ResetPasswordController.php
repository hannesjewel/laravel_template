<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ResetCodePassword;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class ResetPasswordController extends Controller
{
    public function __invoke(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'code' => 'required|string|exists:reset_code_passwords',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if($validator->fails()){
            return response($validator->errors(),403);
        }

        $validated = $validator->validated();

        // find the code
        $passwordReset = ResetCodePassword::firstWhere('code', $request->code);

        // check if it does not expired: the time is one hour
        if ($passwordReset->created_at > now()->addHour()) {
            $passwordReset->delete();
            return response(['message' => trans('passwords.code_is_expire')], 422);
        }

        // find user's email 
        $user = User::firstWhere('email', $passwordReset->email);

        // update user password
        $validated['password'] = bcrypt($request->password);

        $user->update($validated);

        // delete current code 
        $passwordReset->delete();

        return response(['message' =>'password has been successfully reset'], 200);
    }
}
