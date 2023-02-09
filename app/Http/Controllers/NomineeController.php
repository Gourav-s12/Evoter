<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomException;
use App\Http\Resources\NomineeCollection;
use App\Models\Election;
use App\Models\Image;
use App\Models\Nominee;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class NomineeController extends Controller
{
    //
    public function createNominee($id)
    {
        $rules = [
            'name'=>'required|min:4',
            'description'=>'required',           
        ];
        $request = request();
        $input = $request['data']['attributes'];
        $validator = Validator::make($input, $rules);
    
        if ($validator->fails()) {
            return $this->unsuccessfulResponse($validator->errors());
        }

        // $data = request()->validate([
        //     'data.attributes.name'=>'required|min:2',
        //     'data.attributes.description'=>'required',
        // ]);
        // if ($input->hasFile('image')) {
        //     $path = $input->file('image')->store('images');
        //     dd($path);
        // }
        // dd($request);

        try {
            $ele = Election::findOrFail($id);
            
        } catch (ModelNotFoundException $e) {

            return $this->errorResponse('Election');
        }
        $ele->nominees()->create($request['data']['attributes']);
        
        //dd(new NomineeCollection($ele->nominees));
        //dd($ele->nominees);
        return new NomineeCollection($ele->nominees);
    }
    public function deleteNominee($id)
    {
        try {
            $Nom = Nominee::findOrFail($id);
            $Nom->delete();
        } catch (ModelNotFoundException $e) {

            return $this->errorResponse('Nominee');
        }
        
        if ($Nom->image) {
            Storage::delete($Nom->image->path);
            $Nom->image()->delete();
        } 
        // return response([
    	// 	'message' => 'successful',
    	// ],201);
        return $this->successfulResponse('Nominee deleted');
    }
    public function listNominee($id)
    {
        try {
            $ele = Election::findOrFail($id);
            
        } catch (ModelNotFoundException $e) {

            return $this->errorResponse('Election');
        }
        // dd($ele->nominees);
        return new NomineeCollection($ele->nominees);
    }
    public function uploadImage($id,Request $request)
    {
        $rules = [
            'image' => 'image|mimes:jpg,jpeg,png,gif,svg',           
        ];
        // $request = request();
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            return $this->unsuccessfulResponse($validator->errors());
        }

        // dd($request);

        try {
            $nom = Nominee::findOrFail($id);
            
        } catch (ModelNotFoundException $e) {

            return $this->errorResponse('Nominee');
        }
        $path = '' ;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images');
            $nom->image()->save(
                Image::make(['path' => $path])
            );
        }
        return $this->successfulResponse($path);
        // return $path;
    }
}
