<?php

namespace App\Http\Controllers;

use Exception;
use Tymon\JWTAuth\JWTAuth as JWTAuthInstance;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
//Method 2 related imports
// use Tymon\JWTAuth\Facades\JWTAuth;
// use Tymon\JWTAuth\Exceptions\TokenExpiredException;
// use Tymon\JWTAuth\Exceptions\TokenInvalidException;
// use Tymon\JWTAuth\Exceptions\JWTException;
// use Lcobucci\JWT\Configuration;
// use Lcobucci\JWT\Validation\Constraint\SignedWith;
// use Lcobucci\JWT\Signer\Key\InMemory;
// use Lcobucci\JWT\Parser;
// use Lcobucci\JWT\ValidationData;
// use Lcobucci\JWT\Signer\Rsa\Sha256;
// use Illuminate\Support\Facades\Config;
use \Firebase\JWT\JWT;
use Firebase\JWT\Key;
use stdClass;

class TokenValidationController extends Controller
{


    public function validateToken(Request $request)
    {
        $token = $request->token;

        if (!$token) {
            return $this->errorResponse('Error occuried', 'Token not provided', 400);
        }
        try {
            $publicKeyPath = storage_path('key/public.pem');
            //log if public key path is correct
            Log::info('Public key path: ' . $publicKeyPath);
            $publicKey = file_get_contents($publicKeyPath);
            if(!$publicKey){
                return $this->errorResponse('Error occuried', 'Public key not found', 400);
            }
            //log if public key is found
            Log::info('Public key found');            
            $decoded = JWT::decode($token,new Key($publicKey,'RS256'));

            return $this->successResponse($decoded, 'Token is valid', code:200);
        }catch (Exception $e) {
            dd($e->getMessage());
            return $this->errorResponse('Error occuried', $e->getMessage(), 401);
        }
    }
    //megthod 2 
    // public function validateToken2(Request $request)
    // {
    //     $token = $request->input('token');

    //     try {
    //         // // Log public key path and algorithm for debugging
    //         // Log::info('Public key path: ' . config('jwt.keys.public'));
    //         // Log::info('JWT algorithm: ' . config('jwt.algo'));

    //         // // Set the token and get the payload
    //         // JWTAuth::setToken($token);
    //         // $payload = JWTAuth::getPayload();

    //         // return response()->json(['success' => true, 'data' => $payload]);

    //         // $publicKey = InMemory::file(storage_path('key/public.pem'));
    //         // $config = Configuration::forAsymmetricSigner(
    //         //     new Sha256(),
    //         //     InMemory::empty(), // No private key needed
    //         //     $publicKey
    //         // );

    //         // $token = $config->parser()->parse($token);
    //         // $constraints = $config->validationConstraints();
    //         // $constraints[] = new SignedWith($config->signer(), $publicKey);

    //         // if (!$config->validator()->validate($token, ...$constraints)) {
    //         //     throw new \Exception('Token signature is invalid.');
    //         // }

    //         // $payload = $token->claims()->all();
    //        $payload= JWTAuth::parseToken()->authenticate();

    //         return response()->json(['success' => true, 'data' => $payload]);
    //     }  catch (TokenExpiredException $e) {
    //         return response()->json(['success' => false, 'error' => 'Token has expired.']);
    //     } catch (TokenInvalidException $e) {
    //         return response()->json(['success' => false, 'error' => 'Token is invalid.']);
    //     } catch (JWTException $e) {
    //         dd($e->getMessage());
    //         return response()->json(['success' => false, 'error' => 'Token could not be parsed.']);
    //     } catch (\Exception $e) {
    //         return response()->json(['success' => false, 'error' => $e->getMessage(), 'message' => 'An error occurred.']);
    //     }
    // }
}
