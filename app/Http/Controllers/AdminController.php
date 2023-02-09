<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    //
    public function index()
    {
        //dd(Auth::user());
        return response([
    		'isAdmin' => Auth::user()->is_admin,
            'user_id' => Auth::user()->id
    	],200);
    }
}
