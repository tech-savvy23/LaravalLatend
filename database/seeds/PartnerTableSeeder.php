<?php

use Carbon\Carbon;
use App\Models\Partner;
use Illuminate\Database\Seeder;
use Bitfumes\Multiauth\Model\Permission;

class PartnerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Partner::class)->create(['email' => 'sarthak@example.com', 'email_verified_at' => Carbon::now()->subWeek(), 'active' => 1]);
        factory(Partner::class)->create(['email' => 'contractor@gmail.com', 'email_verified_at' => Carbon::now()->subWeek(), 'type' => 'contractor', 'active' => 1]);
        factory(Partner::class, 50)->create(['active' => 1]);
        $models                = ['Auditor', 'Contractor', 'Customer', 'Report', 'Space', 'SpaceType', 'Booking', 'Checklist', 'Coupon'];
        $tasks                 = ['Create', 'Read', 'Update', 'Delete'];
        foreach ($tasks as $task) {
            foreach ($models as $model) {
                $name       = "{$task}{$model}";
                factory(Permission::class)->create(['name' => $name, 'parent' => $model]);
            }
        }
    }
}
