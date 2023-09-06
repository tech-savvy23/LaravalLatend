<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CommonTableSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(SpaceTableSeeder::class);
        $this->call(ServiceTableSeeder::class);
        $this->call(PartnerTableSeeder::class);
        $this->call(AreaTableSeeder::class);
        $this->call(CoupanTableSeeder::class);
        Artisan::call('multiauth:install');
        $this->call(ReportSeeder::class);
        $this->call(PartnerPriceSeeder::class);
        $this->call(RescheduleReasonSeeder::class);

    }
}
