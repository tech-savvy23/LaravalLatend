<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Service;
use App\Models\SubService;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class Sub_serviceTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_belongs_to_service()
    {
        $service      = factory(Service::class)->create();
        $SubService   = factory(SubService::class)->create(['service_id' => $service->id]);
        $this->assertInstanceOf(Service::class, $SubService->service);
    }
}
