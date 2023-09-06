<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\BookingAllottee;
use App\Models\Partner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BookingDeviceTest extends TestCase 
{
    use RefreshDatabase;
    /** @test */
    public function api_for_add_booking_device_in_database()
    {
        
        $partner = factory(Partner::class)->create(['type' => Partner::TYPE_AUDITOR]);
        $this->actingAs($partner, 'partner');
        
        $booking = $this->create_booking(['status'=>Booking::AUDITOR_ACCEPTED]);
        
        $this->create_booking_service(['booking_id' => $booking->id]);
        $this->create_booking_space(['booking_id' => $booking->id]);

         factory(BookingAllottee::class)->create(['booking_id'=>$booking->id, 'allottee_id'=>$partner->id, 'allottee_type'=>get_class($partner)]);
        
        $checklist  = $this->create_checklist();
        $type       = $this->create_checklisttype(['checklist_id' => $checklist->id]);

       

        $data = [
            'booking_id' => $booking->id,
            'checklist_type_id' => $type->id,
            'title' => 'Multiple 1',
            'value' => [ 'value1' => 123, 'value2' => 12345],
            'checklist_id' => $checklist->id,
            'result' => 'hello world'
        ];
        
        $res = $this->postJson(route('lead-booking-device'),$data);
        $res->assertStatus(201)->assertJson([
            'data' => $data
        ]);
   
    }


    /** @test */
    public function api_for_delete_booking_device_in_database()
    {
        
        $partner = factory(Partner::class)->create(['type' => Partner::TYPE_AUDITOR]);
        $this->actingAs($partner, 'partner');
        
        $booking = $this->create_booking(['status'=>Booking::AUDITOR_ACCEPTED]);
        
        $this->create_booking_service(['booking_id' => $booking->id]);
        $this->create_booking_space(['booking_id' => $booking->id]);

        factory(BookingAllottee::class)->create(['booking_id'=>$booking->id, 'allottee_id'=>$partner->id, 'allottee_type'=>get_class($partner)]);
        
        $checklist  = $this->create_checklist();
        $type       = $this->create_checklisttype(['checklist_id' => $checklist->id]);

        $booking_device = $this->createBookingDevice([
            'booking_id' => $booking->id,
            'checklist_type_id' => $type->id,
            'title' => 'testing',
            'checklist_id' => $checklist->id
        ]);

        $this->assertDatabaseHas('booking_devices', ['id' => $booking_device->id]);

        $res = $this->deleteJson(route('delete-booking-device',[$booking_device->id]));
        $res->assertStatus(204);

        $this->assertDatabaseMissing('booking_devices', ['id' => $booking_device->id]);
    }


    /** @test */
    public function api_for_update_booking_device_data_in_database()
    {
        
    $partner = factory(Partner::class)->create(['type' => Partner::TYPE_AUDITOR]);
    $this->actingAs($partner, 'partner');
    
    $booking = $this->create_booking(['status'=>Booking::AUDITOR_ACCEPTED]);
    
    $this->create_booking_service(['booking_id' => $booking->id]);
    $this->create_booking_space(['booking_id' => $booking->id]);

    factory(BookingAllottee::class)->create(['booking_id'=>$booking->id, 'allottee_id'=>$partner->id, 'allottee_type'=>get_class($partner)]);
    
    $checklist  = $this->create_checklist();
    $type       = $this->create_checklisttype(['checklist_id' => $checklist->id]);

    $booking_device = $this->createBookingDevice([
        'booking_id' => $booking->id,
        'checklist_type_id' => $type->id,
        'title' => 'Multiple 1',
        'value' => [ 'value1' => 123, 'value2' => 12345],
        'checklist_id' => $checklist->id,
        'result' => 'hello world'
    ]);

        $data = [
        'title' => 'Multiple 2',
        'value' => [ 'value1' => 432, 'value2' => 12215],
        'result' => 'hello world too'
        ];

        $res = $this->patchJson(route('update-booking-device',[$booking_device->id]), $data);

        $res->assertStatus(200)->assertJson([
            'data' => [
            'title' => 'Multiple 2',
            'value' => [ 'value1' => 432, 'value2' => 12215],
            'result' => 'hello world too'
            ]
        ]);

    }

    /** @test */
    public function api_for_get_all_booking_device_data_from_database()
    {
        
        $partner = factory(Partner::class)->create(['type' => Partner::TYPE_AUDITOR]);
        $this->actingAs($partner, 'partner');
        
        $booking = $this->create_booking(['status'=>Booking::AUDITOR_ACCEPTED]);
        
        $this->create_booking_service(['booking_id' => $booking->id]);
        $this->create_booking_space(['booking_id' => $booking->id]);

        factory(BookingAllottee::class)->create(['booking_id'=>$booking->id, 'allottee_id'=>$partner->id, 'allottee_type'=>get_class($partner)]);
        
        $checklist  = $this->create_checklist();
        $type       = $this->create_checklisttype(['checklist_id' => $checklist->id]);


        $booking_device = $this->createBookingDevice([
            'booking_id' => $booking->id,
            'checklist_type_id' => $type->id,
            'title' => 'Multiple 1',
            'value' => [ 'value1' => 123, 'value2' => 12345],
            'checklist_id' => $checklist->id,
            'result' => 'hello world'
        ]);

        
        $res = $this->getJson(route('get-booking-devices',[$booking_device->id,$type->id]));
        $res->assertStatus(200);
    }


    /** @test */

    public function add_booking_device_image_in_media_table()
    {
        $partner = factory(Partner::class)->create(['type' => Partner::TYPE_AUDITOR]);
        $this->actingAs($partner, 'partner');
        
        $booking = $this->create_booking(['status'=>Booking::AUDITOR_ACCEPTED]);
        
        $this->create_booking_service(['booking_id' => $booking->id]);
        $this->create_booking_space(['booking_id' => $booking->id]);

        factory(BookingAllottee::class)->create(['booking_id'=>$booking->id, 'allottee_id'=>$partner->id, 'allottee_type'=>get_class($partner)]);
        
        $checklist  = $this->create_checklist();
        $type       = $this->create_checklisttype(['checklist_id' => $checklist->id]);

        $booking_device = $this->createBookingDevice([
            'booking_id' => $booking->id,
            'checklist_type_id' => $type->id,
            'title' => 'Multiple 1',
            'value' => [ 'value1' => 123, 'value2' => 12345],
            'checklist_id' => $checklist->id,
            'result' => 'hello world'
        ]);

        //Create Image and store in database through api
        $image    = \Illuminate\Http\Testing\File::image('image.jpg');
        $image    = base64_encode(file_get_contents($image));
        $response      = $this->postJson(route('add-booking-device-image', $booking_device->id), ['image' => $image])->assertStatus(201)->json();
        Storage::disk('public')->assertExists("images/{$response['name']}");
        $this->assertDatabaseHas('media', ['model_type' => get_class($booking_device)]);
    }

    /** @test */

    public function delete_booking_device_image_in_media_table()
    {
        $partner = factory(Partner::class)->create(['type' => Partner::TYPE_AUDITOR]);
        $this->actingAs($partner, 'partner');
        
        $booking = $this->create_booking(['status'=>Booking::AUDITOR_ACCEPTED]);
        
        $this->create_booking_service(['booking_id' => $booking->id]);
        $this->create_booking_space(['booking_id' => $booking->id]);

        factory(BookingAllottee::class)->create(['booking_id'=>$booking->id, 'allottee_id'=>$partner->id, 'allottee_type'=>get_class($partner)]);
        
        $checklist  = $this->create_checklist();
        $type       = $this->create_checklisttype(['checklist_id' => $checklist->id]);

        $booking_device = $this->createBookingDevice([
            'booking_id' => $booking->id,
            'checklist_type_id' => $type->id,
            'title' => 'Multiple 1',
            'value' => [ 'value1' => 123, 'value2' => 12345],
            'checklist_id' => $checklist->id,
            'result' => 'hello world'
        ]);

        //Create Image and store in database through api
        $image    = \Illuminate\Http\Testing\File::image('image.jpg');
        $image    = base64_encode(file_get_contents($image));
        $response      = $this->postJson(route('add-booking-device-image', $booking_device->id), ['image' => $image])->assertStatus(201)->json();
        Storage::disk('public')->assertExists("images/{$response['name']}");
        $this->assertDatabaseHas('media', ['model_type' => get_class($booking_device)]);

        $imageName = $response['name'];


        //Delete Image from database and storage through api  

        $response      = $this->deleteJson(route('delete-booking-device-image', $booking_device->id))->assertStatus(204);
        Storage::disk('public')->assertMissing("images/{$imageName}");
        $this->assertDatabaseMissing('media', ['model_type' => get_class($booking_device)]);

    }
    
}