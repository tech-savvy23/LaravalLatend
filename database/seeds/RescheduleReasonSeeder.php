<?php

use Illuminate\Database\Seeder;

class RescheduleReasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\RescheduleReason::class, 3)->create();

    }
}
