<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;


/**
 * This is to be run whenever the production demonstration branch is
 * updated. It creates all necessary users and objects.
 *
 * Class ProductionDemoSeeder
 * @package Database\Seeders
 */
class ProductionDemoSeeder extends Seeder
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
            LTIDemoCredsSeeder::class
        ]);

    }
}
