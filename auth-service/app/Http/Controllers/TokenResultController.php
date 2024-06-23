<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class TokenResultController extends Controller
{
    public function tokenResultPage($token)
    {
        try {
            // Your logic here
            return view('auth.TokenValdiationResult', compact('token'));
        } catch (Exception $e) {
            return redirect()->route('login.form')->with('error', 'Invalid Token');
        }
    }
    // public function tokenResultPage($token)
    // {
    //     try {
    //         $user = JWTAuth::authenticate($token);
    //         $token_validation_service_url = config('services.microservice.token_validation_service_url');
    //         $client = new \GuzzleHttp\Client();
    //         $response = $client->request('POST', $token_validation_service_url, [
    //             'headers' => [
    //                 'Authorization' => 'Bearer ' . $token
    //             ],
    //             'form_params' => [
    //                 'token' => $token
    //             ]
    //         ]);
    //         $response = json_decode($response->getBody()->getContents());
    //         return view('auth.TokenValdiationResult', compact('user', 'response'));
    //     } catch (Exception $e) {
    //         return redirect()->route('login.form')->with('error', 'Invalid Token');
    //     }
    // }

}
