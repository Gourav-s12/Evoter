<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function errorResponse($title= null){
        return response()->json([
            'errors' => [
                'code' => 404,
                'title' => $title,
                'detail' => 'Unable to locate the given information. (Not Found)',
            ]
        ], 404);
    }
    public function successfulResponse($title= null){
        return response()->json([
            'data' => [
                'code' => 201,
                'title' => $title,
                'message' => 'successful',
            ]
        ], 201);
    }
    public function unsuccessfulResponse($error= null){
        return response()->json([
            'data' => [
                'code' => 201,
                'error' => $error,
                'message' => 'unsuccessful',
            ]
        ], 201);
    }
    public function UnauthorizedResponse($detail= null){
        return response()->json([
            'errors' => [
                'code' => 401,
                'message' => 'Unauthorized',
                'detail' => $detail,
            ]
        ], 401);
    }
}
