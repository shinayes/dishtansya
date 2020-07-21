<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class AuthFeatureTest extends TestCase
{
    use WithoutMiddleware;

    /**
     * @test
     */
    public function it_should_successfully_register_a_user()
    {
        $response = $this->post(
            '/api/register',
            [
                'email' => 'backend@multisyscorp.com',
                'password' => 'test123'
            ]
        );
        $response->assertStatus(201);
        $response->assertJson([
            'message' => 'User successfully registered!'
        ]);
    }

    /**
     * @test
     */
    public function it_should_failed_to_register_a_user()
    {
        $response = $this->post(
            '/api/register',
            [
                'email' => 'backend@multisyscorp.com',
                'password' => 'test123'
            ]
        );
        $response->assertStatus(201);
        $response->assertJson([
            'message' => 'User successfully registered!'
        ]);

        $response = $this->post(
            '/api/register',
            [
                'email' => 'backend@multisyscorp.com',
                'password' => 'test123'
            ]
        );

        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'Invalid form values!',
            'errors' => ['email' => ['Email already taken']]
        ]);
    }

    /**
     * @test
     */
    public function it_should_successfully_login()
    {
        $response = $this->post(
            '/api/register',
            [
                'email' => 'backend@multisyscorp.com',
                'password' => 'test123'
            ]
        );
        $response->assertStatus(201);
        $response->assertJson([
            'message' => 'User successfully registered!'
        ]);

        $response = $this->post(
            '/api/auth/login',
            [
                'email' => 'backend@multisyscorp.com',
                'password' => 'test123'
            ]
        );
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'message',
            'data' => ['access_token', 'token_type']
        ]);
    }
}
