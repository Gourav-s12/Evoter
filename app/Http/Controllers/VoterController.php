<?php

namespace App\Http\Controllers;

use App\Http\Resources\Result;
use App\Models\Election;
use App\Models\Nominee;
use App\Models\Voter;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoterController extends Controller
{
    //
    public function voting(Request $request)
    {
        $data = $request->validate([
            'data.attributes.election_id'=>'required|numeric',
            'data.attributes.nominee_id'=>'required|numeric',
        ]);
        $e_id = $data['data']['attributes']['election_id'];
        $n_id = $data['data']['attributes']['nominee_id'];

        try {
            $Ele = Election::findOrFail($e_id);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Election');
        }
        try {
            $Nom = Nominee::findOrFail($n_id);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Nominee');
        }

        $u_id = Auth::user()->id;
        //dd($u_id);
        if (Auth::user()->is_admin == 1) {
            // return response()->json([
            //     'errors' => [
            //         'code' => 401,
            //         'message' => 'Unauthorized',
            //         'detail' => 'Admin can not vote in Election',
            //     ]
            // ], 401);
            return $this->UnauthorizedResponse('Admin can not vote in Election');
        }
        
        if (!($Ele->start != null && $Ele->end == null)) {
            
            return $this->UnauthorizedResponse('This election is not open to vote');
        }

        if (Voter::where('user_id',$u_id)->where('election_id',$e_id)->count() > 0) {

            return $this->UnauthorizedResponse('You already voted in this election');
        }

        // Voter::create($data['data']['attributes']);
        $voter = new Voter();
        $voter->election_id = $e_id;
        $voter->nominee_id = $n_id;
        $voter->user_id = $u_id;
        $voter->save();
        
        return $this->successfulResponse('Voted Successfully');
    }
    public function showResult($id)
    {
        try {
            //$result = Election::where('id',$ele)->withCount(['total_voters'])->get();
            $result = Election::withCount(['total_voters'])->findOrFail($id);
            //dd(Election::findOrFail($ele)->with('nominees')->get());
            //dd(Election::where('id',$ele)->with('nominees')->withCount(['voters'])->get());
            //dd(Election::findOrFail($ele)->nominees()->withCount(['voters'])->get());
            // $ele = Election::findOrFail($ele);
            
        } catch (ModelNotFoundException $e) {

            return $this->errorResponse('Election');
        }
        // $result->start != null && $result->end == null && Auth::user()->is_admin == 0
        if ($result->end == null && Auth::user()->is_admin == 0) {
            // dump($result->start ,$result->end);
            // dd(Auth::user()->is_admin);
            return $this->UnauthorizedResponse('This election has not ended , you can not see the result now');
        }
        
        // dd($result);
        // dd($result->withCount(['total_voters'])->get());
        //return new Result($result);
        return new Result($result);
    }
    public function canVote($id)
    {
        try {
            $Ele = Election::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Election');
        }

        $u_id = Auth::user()->id;
        //dd($u_id);
        if (Auth::user()->is_admin == 1) {
            return $this->UnauthorizedResponse('Admin can not vote in Election');
        }
        
        if (!($Ele->start != null && $Ele->end == null)) {
            
            return $this->UnauthorizedResponse('This election is not open to vote');
        }

        if (Voter::where('user_id',$u_id)->where('election_id',$id)->count() > 0) {

            return $this->UnauthorizedResponse('You already voted in this election');
        }
        
        return $this->successfulResponse('You can vote in the Election');
    }
}
