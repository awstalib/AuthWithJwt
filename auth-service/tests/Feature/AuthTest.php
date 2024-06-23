<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthTest extends TestCase
{
    /**
     * A basic feature test login.
     *
     * @return void
     */
    public function testLogin()
    {
        //checjk if the user admin is created
         $userDB= User::where('username','admin')->first();
        if ($userDB) {
            $userDB->delete();
        }
        $user = User::factory()->create([
            'username' => 'admin',
            'password' => bcrypt('12345678')
        ]);

        $response = $this->post('/api/login', [
            'username' => 'admin',
            'password' => '12345678'
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                'token',
                'token_type'
            ]
        ]);
        JWTAuth::setToken($response->json('data.token'))->checkOrFail();
    }
}
