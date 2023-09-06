<?php

use App\Models\Space;
use App\Models\SpaceType;
use Illuminate\Database\Seeder;

class SpaceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Space::class)
            ->create(['name'=>'HomeSpace'])
            ->each(function ($space) {
                factory(SpaceType::class)->create([
                    'space_id'  => $space->id,
                    'thumbnail' => '/images/icon/home/bangala.png',
                    'name'      => 'Bangala',
                ]);
                factory(SpaceType::class)->create([
                    'space_id'  => $space->id,
                    'thumbnail' => '/images/icon/home/tower.png',
                    'name'      => 'Tower',
                ]);
                factory(SpaceType::class)->create([
                    'space_id'  => $space->id,
                    'thumbnail' => '/images/icon/home/home.png',
                    'name'      => 'Home',
                ]);
            });

        factory(Space::class)
            ->create(['name'=>'Workspace'])
            ->each(function ($space) {
                factory(SpaceType::class)->create([
                    'space_id'  => $space->id,
                    'thumbnail' => '/images/icon/workspace/coaching.png',
                    'name'      => 'Coaching',
                ]);
                factory(SpaceType::class)->create([
                    'space_id'  => $space->id,
                    'thumbnail' => '/images/icon/workspace/hospital.png',
                    'name'      => 'Hospital',
                ]);
                factory(SpaceType::class)->create([
                    'space_id'  => $space->id,
                    'thumbnail' => '/images/icon/workspace/office.png',
                    'name'      => 'Office',
                ]);
                factory(SpaceType::class)->create([
                    'space_id'  => $space->id,
                    'thumbnail' => '/images/icon/workspace/school.png',
                    'name'      => 'School',
                ]);
                factory(SpaceType::class)->create([
                    'space_id'  => $space->id,
                    'thumbnail' => '/images/icon/workspace/shop.png',
                    'name'      => 'Shop',
                ]);
                factory(SpaceType::class)->create([
                    'space_id'  => $space->id,
                    'thumbnail' => '/images/icon/workspace/super-market.png',
                    'name'      => 'Super Market',
                ]);
            });
    }
}
