<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ResetCodePassword;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class CodeCheckController extends Controller
{
    public function __invoke(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'code' => 'required|string|exists:reset_code_passwords',
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
            return response('The OTP you entered has expired.', 422);
        }

        return response([
            'code' => $passwordReset->code,
            'message' => trans('passwords.code_is_valid')
        ], 200);
    }
}
