<?php

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class)->create(['email'=> 'sarthak@example.com', 'email_verified_at'=>Carbon::now()->subMonth(), 'active' => 1]);
        factory(User::class, 50)->create();
    }
}
