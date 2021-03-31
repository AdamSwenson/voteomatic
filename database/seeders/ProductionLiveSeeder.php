<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;


/**
 * Seeds the database with things needed for production use
 *
 * Class ProductionLiveSeeder
 * @package Database\Seeders
 */
class ProductionLiveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        //we make 1 admin user since
        //everyone else logging in will be
        //non-admins
        $this->call([AdminUserSeeder::class]);


        $this->call([

            //Resource link etc for LTI access
            LTIDevCredsSeeder::class
        ]);

    }
}
