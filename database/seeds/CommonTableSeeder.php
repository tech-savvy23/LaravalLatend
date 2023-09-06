<?php

use Illuminate\Database\Seeder;

class CommonTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Api Keys
        factory(\App\Models\Common\ApiKey::class, 3)->create();
    }
}
