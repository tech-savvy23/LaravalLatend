<?php

use App\Models\Service;
use App\Models\SubService;
use Illuminate\Database\Seeder;

class ServiceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Service::class, 3)
            ->create()
            ->each(function ($service) {
                factory(SubService::class, 5)->create([
                    'service_id' => $service->id,
                ]);
            });
    }
}
