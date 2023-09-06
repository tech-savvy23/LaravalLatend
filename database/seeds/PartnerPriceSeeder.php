<?php

use App\Models\PartnerPrice;
use Illuminate\Database\Seeder;

class PartnerPriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(PartnerPrice::class, 3)->create();
    }
}
