<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserCollection;
use App\Models\Image;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    //
    public function createAccount(Request $request)
    {
        $rules = [
            'name' => 'unique:users|required|max:50|min:3',
            'email'    => 'unique:users|required',
            'password' => 'required|confirmed|min:6',
            'is_admin' => 'boolean',
        ];
    
        $input     = $request->only('name', 'email','password','is_admin','password_confirmation');
        //dd($input);
        $validator = Validator::make($input, $rules);
    
        if ($validator->fails()) {
            //return response()->json(['message' => 'unsuccess', 'error' => $validator->errors()]);
            return $this->unsuccessfulResponse($validator->errors());
        }
        $name = $request->name;
        $email    = $request->email;
        $password = $request->password;
        $is_admin = $request->is_admin ? 1 : 0;
        
        $user     = User::create(['name' => $name, 'email' => $email, 'password' => bcrypt($password), 'is_admin' => $is_admin]);
        
        return $this->successfulResponse(['user_id' => $user->id,'user_name'=>$user->name, 'email' => $user->email]);
    }
    public function deleteAccount($id)
    {
        $user = User::findOrFail($id);
        
        if($user->is_admin){
            
            return $this->UnauthorizedResponse('You can not delete an admin');
        }
        $user->delete();
        return response()->json(['massage' => 'successfully deleted' , 'new'=> new UserCollection(User::IsNotAdmin()->get())]); 
    }
    public function listAccount(){

        return new UserCollection(User::IsNotAdmin()->get());
        //return new UserCollection(User::all()); //(Post::all());
    }
    public function deleteThisUser(){
        
        $user_id= Auth::user()->id;
        Session::flush();
        
        // Auth::logout();

        $user = User::findOrFail($user_id);
        $user->delete();
        return $this->successfulResponse('User deleted');
    }
    public function userImagePath(){
        
        $user= Auth::user();
        
        return $this->successfulResponse(
            optional($user->image)->url() ?? 'http://127.0.0.1:8000/default_profile_image.png'
        );
    }
    public function userImageUpload(Request $request)
    {
        $rules = [
            'image' => 'required|image|mimes:jpg,jpeg,png,gif,svg',           
        ];
        // $request = request();
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            return $this->unsuccessfulResponse($validator->errors());
        }

        // $user = Auth::user();
        $user = User::findOrFail(Auth::user()->id);

            
        $path = $request->file('image')->store('images');

        if ($user->image) {
            Storage::delete($user->image->path);
            $user->image->path = $path;
            $user->image->save();
        } else {
            $user->image()->save(
                Image::make(['path' => $path])
            );
        }
        return $this->successfulResponse($user->image->url());
        // return $path;
    }
}
