<?php

namespace Tests\Feature;

use Tests\TestCase;

class ProductServiceTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_a_user_can_get_all_products(): void
    {
        $response = $this->get('/api/products', [
            'Accept' => 'application/json',
        ]);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'price',
                ],
            ],
            'links' => [
                'first',
                'last',
                'prev',
                'next',
            ],
            'meta' => [
                'current_page',
                'from',
                'last_page',
                'links',
                'path',
                'per_page',
                'to',
                'total',
            ],
        ]);

        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     */
    public function test_a_user_can_get_weather_suggestions(): void
    {
        $response = $this->post('/api/products/weather', [
            'weather' => [
                'city' => 'Algiers',
            ],
        ], [
            'Accept' => 'application/json',
        ]);

        $response->assertJsonStructure([
            'status',
            'data' => [
                'products' => [
                    '*' => [
                        'id',
                        'name',
                        'price',
                    ],
                ],
                'weather' => [
                    'city',
                    'is',
                    'date',
                ],
            ],
        ]);

        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     */
    public function test_a_user_get_proper_errors_on_empty_requests(): void
    {
        $response = $this->post('/api/products/weather', [

        ], [
            'Accept' => 'application/json',
        ]);

        $response->assertJsonStructure([
            'message',
            'errors' => [
                'weather.city',
            ],
        ]);

        $response->assertJson([
            'message' => 'The weather.city field is required.',
            'errors' => [
                'weather.city' => [
                    'The weather.city field is required.',
                ],
            ],
        ]);

        $response->assertStatus(422);
    }

    /**
     * A basic feature test example.
     */
    public function test_a_user_get_proper_errors_on_city_does_not_exist(): void
    {
        $response = $this->post('/api/products/weather', [
            'weather' => [
                'city' => 'Zazrfefdsfdf',
            ],
        ], [
            'Accept' => 'application/json',
        ]);

        $response->assertJsonFragment([
            'message' => 'There is errors on the request',
        ]);

        $response->assertJsonFragment([
            'errors' => [
                'Failed to fetch weather data: No matching location found.',
            ],
        ]);

        $response->assertStatus(422);
    }
}
