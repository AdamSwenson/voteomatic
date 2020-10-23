<?php

namespace Database\Seeders;

use App\Models\Meeting;
use App\Models\Motion;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory(10)->create();
        Meeting::factory(2)->create();
        Motion::factory(3)->create();
    }
}
