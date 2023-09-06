<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Address;
use App\Models\Booking;
use App\Models\Common\Otp;
use App\Models\User\UserDevice;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function user_belongs_to_address()
    {
        $user    = $this->create_user();
        $this->create_address(['user_id' => $user->id]);
        $this->assertInstanceOf(Address::class, $user->address[0]);
    }

    /** @test */
    public function user_has_many_bookings()
    {
        $user     = $this->create_user();
        $bookings = $this->create_booking(['user_id' => $user->id]);
        $this->assertInstanceOf(Booking::class, $user->bookings[0]);
    }

    /** @test */
    public function it_has_many_otp()
    {
        $user            = $this->create_user();
        $otp             = factory(Otp::class)->create(['for_id' => $user->id, 'for_type'=>get_class($user)]);
        $this->assertInstanceOf(Otp::class, $user->otp[0]);
    }

    /** @test */
    public function it_cancapitalize_first_name_value()
    {
        $user            = $this->create_user(['first_name' => 'sarthak', 'last_name' => 'shrivastava']);
        $this->assertEquals('Sarthak', $user->first_name);
        $this->assertEquals('Shrivastava', $user->last_name);
    }

    /** @test */
    public function format_of_user_devices_in_array()
    {
        $user            = $this->create_user();
        factory(UserDevice::class, 5)->create(['user_id' => $user->id]);
        $this->assertIsArray($user->userDevicesToken());
    }

    /** @test */
    public function user_can_upload_his_profile_and_delete_old_if_there_is()
    {
        Storage::fake();
        $image  = \Illuminate\Http\Testing\File::image('image.jpg');
        $image  = base64_encode(file_get_contents($image));

        $bookingreport['images'] = ["data:image/png;base64,{$image}", "data:image/png;base64,{$image}"];

        $user = $this->create_user();
        $user->uploadProfilePic($image);

        $oldImage = $user->image;
        Storage::disk('public')->assertExists('images/' . $oldImage);

        $user->uploadProfilePic($image);
        Storage::disk('public')->assertExists('images/' . $user->fresh()->image);
        Storage::disk('public')->assertMissing('images/' . $oldImage);

        $this->assertDatabaseHas('users', ['image' => $user->image]);
    }
}
