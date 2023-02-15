<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(){
        return response(User::all(), 200);
    }

    public function create(Request $request){

        $validator = Validator::make($request->all(), [
            "name" => "required|string|max:200",
            "email" => "required|email|unique:users",
            "password" => "required|confirmed",
            "role" => "string|max:200"
        ]);

        if($validator->fails()){
            return response($validator->errors(), 403);
        }

        $validated = $validator->validated();

        $validated['password'] = bcrypt($request->password);

        $user = User::create($validated);

        return response($user, 200);

    }

    public function show($id){
        return response(User::find($id), 200);
    }

    public function update(Request $request, $id){

        $validator = Validator::make($request->all(), [
            "name" => "required|string|max:200",
            "email" => 'required|email|unique:users,email,'.$id,
            "password" => "confirmed",
            "role" => "string|max:200"
        ]);

        if($validator->fails()){
            return response($validator->errors(), 403);
        }

        $validated = $validator->validated();

        if($request->password){
            $validated['password'] = bcrypt($request->password);
        }        

        $user = User::find($id);

        $user->update($validated);

        return response($user, 200);
    }

    public function delete($id){
        $record = User::withTrashed()->where('id', $id)->first();

        if($record){
            if($record->trashed()){

                $record->forceDelete();
                return response('Deleted permanently', 200);
                
            } else {
                $record->delete();
                return response('Moved to trash', 200);
            }
        }
        return response('Not Found', 403);
        
    }

    public function bin(){

        $records = User::onlyTrashed()->get();
        
        return response($records, 200);
    }

    public function restore($id){
        $record = User::withTrashed()->where('id', $id)->first();

        if($record){
            $record->restore();
            return response($record, 200);
        }
        return response('Not Found', 403);
    }
}
