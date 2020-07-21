<?php

use Tests\TestCase;

class ProductFeatureTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_successfully_order_a_product()
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

        $resultToken = $response->decodeResponseJson();

        $response = $this->get(
            '/api/product/get/1', ['Authorization' => 'Bearer ' . $resultToken['data']['access_token']]
        );

        $product = $response->decodeResponseJson();
        $response->assertStatus(200);
        $response->assertJson($product);

        $response = $this->post(
            '/api/product/order', ['id' => 1, 'quantity' => $product['data']['available_stock'] - 1], ['Authorization' => 'Bearer ' . $resultToken['data']['access_token']]
        );

        $response->assertStatus(200);
        $response->assertJson(["message" => "You have successfully ordered this product."]);
    }

    /**
     * @test
     */
    public function it_should_failed_to_order_a_product()
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

        $resultToken = $response->decodeResponseJson();

        $response = $this->get(
            '/api/product/get/1', ['Authorization' => 'Bearer ' . $resultToken['data']['access_token']]
        );

        $product = $response->decodeResponseJson();
        $response->assertStatus(200);
        $response->assertJson($product);
        $stock = $product['data']['available_stock'];

        $response = $this->post(
            '/api/product/order', ['id' => 1, 'quantity' => $stock - 1], ['Authorization' => 'Bearer ' . $resultToken['data']['access_token']]
        );

        $response->assertStatus(200);
        $response->assertJson(["message" => "You have successfully ordered this product."]);

        $response = $this->get(
            '/api/product/get/1', ['Authorization' => 'Bearer ' . $resultToken['data']['access_token']]
        );

        $product = $response->decodeResponseJson();
        $response->assertStatus(200);
        $response->assertJson($product);
        $this->assertTrue((int)$product['data']['available_stock'] === 1);

        $response = $this->post(
            '/api/product/order', ['id' => 1, 'quantity' => 2], ['Authorization' => 'Bearer ' . $resultToken['data']['access_token']]
        );

        $response->assertStatus(422);
        $response->assertJson([
            "message" => "Failed to order this product due to unavailability of the stock",
            "errors" => ["quantity" => "Cannot order product greater than stocks!"]
        ]);
    }
}
