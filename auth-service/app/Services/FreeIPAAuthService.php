<?php

namespace App\Services;

use GuzzleHttp\Client;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class FreeIPAAuthService
{
    public function authenticate($credentials)
    {
        try{
        $client = new Client();
        $response = $client->post('https://ipa.demo1.freeipa.org/ipa/session/login_password', [
            'form_params' => [
                'user' => $credentials['username'],
                'password' => $credentials['password']
            ],
            'headers' => [
                'Referer' => 'https://ipa.demo1.freeipa.org/ipa',
                'Accept' => 'application/json',
            ]
        ]);
        if ($response->getStatusCode() == 200) {
            $user = User::where('username', $credentials['username'])->first();
            if (!$user) {
                // Create the user if not found in the database
                $user = User::create(['username' => $credentials['username'], 'password' => bcrypt($credentials['password'])]);
            }
            Auth::attempt($credentials);
            $token = JWTAuth::fromUser($user);  // Generate token from user
            return $token;
        }
    } catch (\Exception $e) {
        throw new \Exception('Invalid FreeIPA credentials.');
    }

    }
}
