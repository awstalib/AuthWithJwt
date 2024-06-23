<?php
namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Services\FreeIPAAuthService;
use Exception;
use Tymon\JWTAuth\JWT;

class AuthController extends Controller
{
    protected $freeIPAAuthService;

    public function __construct(FreeIPAAuthService $freeIPAAuthService)
    {
        $this->freeIPAAuthService = $freeIPAAuthService;
    }
    public function LoginForm()
    {
        $token_validation_service_url_config = config('services.microservice.token_validation_service_url');
        return view('auth/login',compact('token_validation_service_url_config'));
    }
    public function loginApi(LoginRequest $request)
    {
        try {
            $credentials = $request->credentials();
            $freeIPA = $request->has('freeipa');

            if ($freeIPA) {
                $token = $this->freeIPAAuthService->authenticate($credentials);
                if ($token) {
                    //set token in session
                    session(['token' => $token]);
                    return response()->json(['redirect' => route('token-result', ['token' => $token])]);
                } else {
                    return $this->errorResponse('Error', 'Invalid FreeIPA credentials.', 401);
                }
            } else {
                if (Auth::attempt($credentials)) {
                    $user = Auth::user();
                    $token = JWTAuth::fromUser($user);
                    //set token in session
                    session(['token' => $token]);
                    //redirect to token result page with token
                    return response()->json(['redirect' => route('token-result', ['token' => $token])]);

                } else {
                    return $this->errorResponse('Error', 'Invalid credentials.', 401);
                }
            }
        } catch (Exception $e) {
            return $this->errorResponse('Error',$e->getMessage(), 500);
        }
    
    }

    public function logout(Request $request)
    {
        try {
            Auth::logout();
            session()->forget('token');
            JWTAuth::invalidate($request->bearerToken());
            return response()->json(['message' => 'Logged out successfully']);
        } catch (Exception $e) {
            return response()->json(['error' => $e], 500);
        }
    }
}

