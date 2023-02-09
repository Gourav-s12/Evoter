<?php

namespace App\Http\Controllers;

use App\Http\Resources\Election as ResourcesElection;
use App\Http\Resources\ElectionCollection;
use App\Models\Election;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ElectionController extends Controller
{
    // //
    // public function index()
    // {
    //     //dd(Auth::user());
    //     $a=2;
    //     $b=3;
    //     return response([
    // 		'message' => $a+$b,
    //         'user_id' => Auth::user()->id
    // 	],201);
    // }
    public function createElection(Request $request)
    {
        // $data = $request->validate([
        //     'data.attributes.name'=>'required|min:4',
        // ]);
        $rules = [
            'name'=>'required|min:4',
        ];

        $input = $request['data']['attributes'];
        $validator = Validator::make($input, $rules);
    
        if ($validator->fails()) {
            return $this->unsuccessfulResponse($validator->errors());
        }

        $ele= Election::create($request['data']['attributes']);
        
        return new ResourcesElection($ele);
    }

    public function eleList()
    {
        return new ElectionCollection(Election::all()); //(Post::all());
    }

    public function deleteElection($id)
    {
        try {
            $Ele = Election::findOrFail($id);
            
        } catch (ModelNotFoundException $e) {

            return $this->errorResponse('Election');
        }
        $Ele->delete();
        
        return $this->successfulResponse('Election deleted');
    }
    public function startElection($id)
    {
        try {
            $Ele = Election::findOrFail($id);
            
        } catch (ModelNotFoundException $e) {

            return $this->errorResponse('Election');
        }

        $Ele->update( [
            'start' => now(),
            'end' => null,
        ]);
        //dd(Election::findOrFail($id));
        return new ResourcesElection($Ele);
    }
    public function stopElection($id)
    {
        try {
            $Ele = Election::findOrFail($id);
            
        } catch (ModelNotFoundException $e) {

            return $this->errorResponse('Election');
        }

        $Ele->update( [
            'end' => now(),
        ]);
        //dd(Election::findOrFail($id));
        return new ResourcesElection($Ele);
    }
    public function eleOpenList()
    {
        //dd(Voter::where('user_id',$u_id)->where('election_id',$ele)->count() > 0);
        // dd(Election::isOpen()->isVoted(Auth::user()->id)->get());
        return new ElectionCollection(Election::isOpen()->isVoted(Auth::user()->id)->get()); //(Post::all());
    }
    public function eleCloseList()
    {
        //Voter::where('user_id',$u_id)->where('election_id',$ele)->count() > 0;
        //dd(Election::isOpen()->get());
        return new ElectionCollection(Election::isClose()->get()); //(Post::all());
    }
}
