<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMail;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function sendContact(Request $request){

        $validator = Validator::make($request->all(), [
            'fullname' => 'required|string|max:191',
            'email' => 'required|email|max:191',
            'message'=> 'required|string|max:1000'
        ]);

        if($validator->fails()){
            return response($validator->errors(), 403);
        }

        $validated = $validator->validated();

        $record = Message::create($validated);

        Mail::to("hannes@optimalonline.co.za")->send(new ContactMail($record));

        return response([
            'success' => 'message sent'
        ], 200);
    }
}
