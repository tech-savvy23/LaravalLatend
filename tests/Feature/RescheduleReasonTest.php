<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class RescheduleReasonTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function api_can_give_all_reschedule_reasons()
    {
        $this->create_reschedule_reason();
        $this->getJson(route('reschedule.reasons.index'))->assertOk()->assertJsonStructure(['data']);
    }
   
    /** @test */
    public function add_reschedule_empty_reasons_through_api_so_it_will_generate_error()
    {
        $this->withExceptionHandling();

        $data = [
            'reason' => ''
        ];
        $response = $this->postJson(route('reschedule.reasons.store'), $data);
        $response->assertStatus(422);
        $response->assertJson([
            'errors' => [
                'reason' => ['The reason field is required.']
            ]
        ]);
        
    }

    /** @test */
    public function add_reschedule_reasons_through_api_with_reason()
    {
        $data = [
            'reason' => 'Hello world'
        ];
        $response = $this->postJson(route('reschedule.reasons.store'), $data);
        $response->assertStatus(201);
        $response->assertJson([
            'data' => $data
        ]);

        $this->assertDatabaseHas('reschedule_reasons', $data);
        
    }

    /** @test */
    public function update_reschedule_empty_reasons_through_api_so_it_will_generate_error()
    {
        $this->withExceptionHandling();
        $reschedule_reason = $this->create_reschedule_reason([
            'reason' => 'Hello world'
        ]);

        $data = [
            'reason' => ''
        ];
        $response = $this->patchJson(route('reschedule.reasons.update', [$reschedule_reason->id]), $data);
        $response->assertStatus(422);
        $response->assertJson([
            'errors' => [
                'reason' => ['The reason field is required.']
            ]
        ]);

    }

     /** @test */
     public function update_reschedule_reasons_through_api_with_reason()
     {
        $reschedule_reason = $this->create_reschedule_reason([
            'reason' => 'Hello world'
        ]);

        $data = [
            'reason' => 'Hello world 2'
        ];

        $response = $this->patchJson(route('reschedule.reasons.update', [$reschedule_reason->id]), $data);
        $response->assertStatus(201);
         $response->assertJson([
             'data' => $data
         ]);
 
         $this->assertDatabaseHas('reschedule_reasons', $data);
         
     }

      /** @test */
      public function delete_reschedule_reasons_through_api()
      {
        $reschedule_reason = $this->create_reschedule_reason([
            'reason' => 'Hello world'
        ]);
 
 
        $response = $this->deleteJson(route('reschedule.reasons.delete', [$reschedule_reason->id]));
        $response->assertStatus(204);
        $this->assertDatabaseMissing('reschedule_reasons', [
            'id' => $reschedule_reason->id
        ]);
          
      }
}
