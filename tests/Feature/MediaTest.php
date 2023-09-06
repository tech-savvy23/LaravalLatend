<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Media;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class MediaTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function api_can_delete_image()
    {
        // Storage::fake();
        $image    = \Illuminate\Http\Testing\File::image('image.jpg');
        $image    = base64_encode(file_get_contents($image));
        $filename = 'abc.jpg';
        Storage::disk('public')->put($filename, "data:image/png;base64,{$image}");
        Media::create(['name'=>$filename, 'model_id' => 1, 'model_type' => 'App\Models\BookingReport']);

        $this->postJson(route('media.delete'), ['name' => $filename])->assertStatus(204);
        Storage::disk('public')->assertMissing($filename);
        $this->assertDatabaseMissing('media', ['name' => $filename]);
    }
}
