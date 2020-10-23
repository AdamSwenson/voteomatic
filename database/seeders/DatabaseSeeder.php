<?php

namespace Database\Seeders;

use App\Models\Meeting;
use App\Models\Motion;
use App\Models\ResourceLink;
use App\Models\User;
use Database\Factories\ResourceLinkFactory;
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

        ResourceLink::factory(['resource_link_id' => "4f7d7beaced17c12e252c18b003c5200176a81b0"])->create();
    }
}
