<?php

namespace Tests\Feature;

use App\ColorCode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ColorCodeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */

    public function if_values_are_correct_then_stored()
    {
        $data = [
            'name' => 'red',
            'code' => '#fffffff',
        ];
        $response = $this->postJson(route('color-codes.store'), $data);
        $response->assertStatus(Response::HTTP_CREATED);

    }

    /**
     * @test
     */

    public function update_value_of_color_table()
    {
        $colorCode = factory(ColorCode::class)->create(['name' => 'red', 'code' => '#ffffff']);
        $data = [
            'name' => 'RoyalBlue',
            'code' => '#2d16b1',
        ];
        $response = $this->patchJson(route('color-codes.update', $colorCode->id), $data);
        $response->assertStatus(Response::HTTP_OK);
        $this->assertDatabaseHas('color_codes', $data);

    }

    /**
     * @test
     */

    public function delete_row_from_color_table()
    {
        $colorCode = factory(ColorCode::class)->create(['name' => 'red', 'code' => '#ffffff']);
        $this->assertDatabaseHas('color_codes', [
            'id' => $colorCode->id
        ]);
        $response = $this->deleteJson(route('color-codes.destroy', $colorCode->id));
        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertDatabaseMissing('color_codes', [
            'id' => $colorCode->id
        ]);

    }

    /** @test */
    public function get_colors_table()
    {
        factory(ColorCode::class, 5)->create();
        $response = $this->getJson(route('color-codes.index'));
        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals(5, count($response->decodeResponseJson()['data']));

    }
}
