<?php

namespace Tests\Unit;

use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Models\Partner;
use Tests\TestCase;
use App\Models\Media;
use App\Models\PartnerDevice;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PartnerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */

    /** @test */
    public function format_of_user_devices_in_array()
    {
        $partner            = $this->create_partner();
        factory(PartnerDevice::class, 5)->create(['partner_id' => $partner->id]);
        $this->assertIsArray($partner->partnerDevicesToken());
    }

    /** @test */
    public function partner_has_one_media()
    {
        $partner = $this->create_partner();
        $media   = factory(Media::class)->create(['model_type'=>get_class($partner), 'model_id' => $partner->id]);
        $this->assertInstanceOf(Media::class, $partner->media);
    }

    /** @test */
    public function partner_can_upload_his_profile_and_delete_old_one()
    {
        Storage::fake();
        $image                   = \Illuminate\Http\Testing\File::image('image.jpg');
        $image                   = base64_encode(file_get_contents($image));
        $bookingreport['images'] = ["data:image/png;base64,{$image}", "data:image/png;base64,{$image}"];

        $partner         = $this->create_partner();
        $partner->uploadProfilePic($image);
        $oldImage = $partner->media->name;
        Storage::disk('public')->assertExists('images/' . $oldImage);

        $partner->uploadProfilePic($image);
        Storage::disk('public')->assertExists('images/' . $partner->fresh()->media->name);
        Storage::disk('public')->assertMissing('images/' . $oldImage);

        $this->assertDatabaseHas('media', ['model_id' => $partner->id]);
    }


}
