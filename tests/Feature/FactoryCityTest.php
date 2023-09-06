<?php


namespace Tests\Feature;


use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class FactoryCityTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function store_factory_data()
    {
        $space = $this->create_space();
        $this->withoutExceptionHandling();
        $data = [
            'space_id' => $space->id,
            'city_id' => '1',
            'value' => '123',
        ];
        $response = $this->postJson(route('factory-city.store'), $data);
        $response->assertStatus(Response::HTTP_CREATED);
    }

}
