<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\Type;

class ProductController extends Controller
{
    public function index(){

        $records = Product::orderBy('title', 'ASC')->get();
        foreach ($records as $record){
            $record->category;
            $record['type_id'] = $record->types->pluck('id');
        }
        return response($records, 200);
    }

    public function create(Request $request){

        $validator = Validator::make($request->all(), [
            'main_category_name' => 'string|max:191',
            'brand_id' => 'integer',
            'category_id' => 'integer',
            'category_id_2' => 'integer',
            'delivery_option_id' => 'integer',
            'title' => 'required|string|max:191',
            'status' => 'string|max:191',
            'summary' => 'required|string|max:1000',
            'description' => 'required|string|max:10000',
            'meta_description' => 'required|string|max:165',
            'meta_keywords' => 'max:1000',
            'price' => 'string|max:191',
            'delivery' => 'string|max:191',
            'preview_image' => 'required|image|mimes:jpg,jpeg,png,svg,gif|max:1000',
            'banner_image' => 'required|image|mimes:jpg,jpeg,png,svg,gif|max:1000',
            'pdf' => 'file|mimes:pdf,docx,doc|max:10000',
            'order_number' => 'integer',
            'youtube_1' => 'string|max:1000',
            'youtube_2' => 'string|max:1000',
            'youtube_3' => 'string|max:1000',
        ]);

        if($validator->fails()){
            return response($validator->errors(), 403);
        }

        $validated = $validator->validated();

        $validated['slug'] = Str::of($validated['title'])->slug('-');

        if($request->price){
            $validated['price'] = str_replace(' ', '', $request->price);
        }

        if($image = $request->file('preview_image')){
            $path = '/uploads/products/preview/';
            $imageName = $validated['slug'].'-'.rand().'.'.$image->extension();
            $image->move(public_path($path), $imageName);
            $validated['preview_image'] = $path.$imageName;
        }

        if($image = $request->file('banner_image')){
            $path = '/uploads/products/banner/';
            $imageName = $validated['slug'].'-'.rand().'.'.$image->extension();
            $image->move(public_path($path), $imageName);
            $validated['banner_image'] = $path.$imageName;
        }

        if($image = $request->file('pdf')){
            $path = '/uploads/products/docs/';
            $imageName = $validated['slug'].'-'.rand().'.'.$image->extension();
            $image->move(public_path($path), $imageName);
            $validated['pdf'] = $path.$imageName;
        }

        if($image = $request->file('video')){
            $path = '/uploads/products/videos/';
            $imageName = $validated['slug'].'-'.rand().'.'.$image->extension();
            $image->move(public_path($path), $imageName);
            $validated['video'] = $path.$imageName;
        }

        $record = Product::create($validated);

        if($request->type_id){
            $array = explode(',', $request->type_id);
            $record->types()->sync($array);
        }

        return response($record, 200);
    }

    public function show($id){

        $record = Product::find($id);

        $record['type_id'] = $record->types->pluck('id');

        if(!$record){
            return response("Not found", 403);
        }

        return response($record, 200);
    }

    public function update(Request $request, $id){

        $validator = Validator::make($request->all(), [
            'main_category_name' => 'string|max:191',
            'brand_id' => 'integer',
            'category_id' => 'integer',
            'category_id_2' => 'integer',
            'delivery_option_id' => 'integer',
            'title' => 'required|string|max:191',
            'status' => 'string|max:191',
            'summary' => 'required|string|max:1000',
            'description' => 'required|string|max:10000',
            'meta_description' => 'string|max:165',
            'meta_keywords' => 'max:1000',
            'price' => 'string|max:191',
            'delivery' => 'string|max:191',
            'preview_image' => 'image|mimes:jpg,jpeg,png,svg,gif|max:1000',
            'banner_image' => 'image|mimes:jpg,jpeg,png,svg,gif|max:1000',
            'pdf' => 'file|mimes:pdf,docx,doc|max:10000',
            'order_number' => 'integer',
            'youtube_1' => 'string|max:1000',
            'youtube_2' => 'string|max:1000',
            'youtube_3' => 'string|max:1000',
        ]);

        if($validator->fails()){
            return response($validator->errors(), 403);
        }

        $validated = $validator->validated();

        if(!$request->main_category_name || $request->main_category_name === ''){
            $validated['main_category_name'] = NULL;
        }

        if(!$request->category_id_2 || $request->category_id_2 === ''){
            $validated['category_id_2'] = NULL;
        }

        if(!$request->delivery_option_id || $request->delivery_option_id === ''){
            $validated['delivery_option_id'] = NULL;
        }

        if(!$request->youtube_1 || $request->youtube_1 === ''){
            $validated['youtube_1'] = NULL;
        }

        if(!$request->youtube_2 || $request->youtube_2 === ''){
            $validated['youtube_2'] = NULL;
        }

        if(!$request->youtube_3 || $request->youtube_3 === ''){
            $validated['youtube_3'] = NULL;
        }

        $validated['slug'] = Str::of($validated['title'])->slug('-');

        if(!isset($request->price)){
            $validated['price'] = NULL;
        } else {
            $validated['price'] = str_replace(' ', '', $request->price);
        }

        if($image = $request->file('preview_image')){
            $path = '/uploads/products/preview/';
            $imageName = $validated['slug'].'-'.rand().'.'.$image->extension();
            $image->move(public_path($path), $imageName);
            $validated['preview_image'] = $path.$imageName;
        }

        if($image = $request->file('banner_image')){
            $path = '/uploads/products/banner/';
            $imageName = $validated['slug'].'-'.rand().'.'.$image->extension();
            $image->move(public_path($path), $imageName);
            $validated['banner_image'] = $path.$imageName;
        }

        if($image = $request->file('pdf')){
            $path = '/uploads/products/docs/';
            $imageName = $validated['slug'].'-'.rand().'.'.$image->extension();
            $image->move(public_path($path), $imageName);
            $validated['pdf'] = $path.$imageName;
        }

        if($image = $request->file('video')){
            $path = '/uploads/products/videos/';
            $imageName = $validated['slug'].'-'.rand().'.'.$image->extension();
            $image->move(public_path($path), $imageName);
            $validated['video'] = $path.$imageName;
        }

        $record = Product::find($id);

        if(!$record){
            return response('Not found', 403);
        }

        if($request->type_id){
            $array = explode(',', $request->type_id);
            $record->types()->sync($array);
        }

        $record->update($validated);
        return response($record, 200);
    }

    public function delete($id){
        $record = Product::withTrashed()->where('id', $id)->first();

        if($record){
            if($record->trashed()){

                $record->forceDelete();
                return response('Deleted permanently', 200);
                
            } else {
                $record->status = 'draft';
                $record->update();
                $record->delete();
                return response('Moved to trash', 200);
            }
        }
        return response('Not Found', 403);
        
    }

    public function bin(){

        $records = Product::onlyTrashed()->get();

        foreach ($records as $record){
            $record->category;
        }
        
        return response($records, 200);
    }

    public function restore($id){
        $record = Product::withTrashed()->where('id', $id)->first();

        if($record){
            $record->restore();
            return response($record, 200);
        }
        return response('Not Found', 403);
    }
}
