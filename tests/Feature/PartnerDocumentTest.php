<?php

namespace Tests\Feature;

use App\Models\Partner;
use App\PartnerDocument;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PartnerDocumentTest extends TestCase
{
    use RefreshDatabase;
   
    /**
     * @test
     */

    public function partner_get_all_documents()
    {
        $partner = $this->create_partner(['type'=> Partner::TYPE_AUDITOR]);
        $this->actingAs($partner);
        $partner_documents = factory(PartnerDocument::class,3)->create();
        $this->assertCount(3, $partner_documents);

    }

    /**
     * @test
     */

    public function partner_can_store_documents()
    {
        $partner = $this->create_partner(['type'=> Partner::TYPE_AUDITOR]);
        $this->actingAs($partner, 'partner');
        $response = $this->patch(route('partner.update.documents'),[
            'pan' => '123456789',
            'gst' => '123456789',
            'bank' => '123456789'
        ]);
        $response->assertStatus(201);
        $response->assertJson(['data' => [
            'pan' => '123456789',
            'gst' => '123456789',
            'bank' => '123456789'
        ]]);


    }

    /**
     * @test
     */

    public function partner_can_update_documents()
    {
        $this->withExceptionHandling();
        $partner = $this->create_partner(['type'=> Partner::TYPE_AUDITOR]);
        factory(PartnerDocument::class)->create(['partner_id' => $partner->id,
        'pan' => '123456789',
        'gst' => '123456789',
        'bank' => '123456789'
        ]);
        $this->actingAs($partner, 'partner');


        $response = $this->patch(route('partner.update.documents'),[
            'pan' => '12345678',
            'gst' => '12345678',
            'bank' => '12345678'
        ]);
        $response->assertStatus(201);
        $response->assertJson(['data' => [
            'pan' => '12345678',
            'gst' => '12345678',
            'bank' => '12345678'
        ]]);

    }
}
