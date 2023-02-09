<?php

namespace App\Exceptions;

use Exception;

class CustomException extends Exception
{
    //
    public function render($request)
    {
        return response()->json([
            'errors' => [
                'code' => 404,
                'title' => $this->getMessage(),
                'detail' => 'Unable to locate the given information. (Not Found)',
            ]
        ], 404);
    }
}
