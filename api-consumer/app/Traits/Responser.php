<?php

namespace App\Traits;


//handle Success Response , Error Response , Validation Error Response ,with total and token
trait Responser
{
    public function successResponse($data=[], $message = null,$token=null,$count=1, $code = 200)
    {
        $response = [
            'success' => true,
            'data' => $data ?? [],
            'message' => $message,
            'total' =>$count,
            'token' => $token
        ];
        return response()->json($response, $code);
    }

    public function errorResponse($message,$error_message, $code)
    {
        $response = [
            'success' => false,
            'message' => $message,
            'error_message' => $error_message,
        ];
        return response()->json($response, $code);
    }
}

