<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try{

            $props = [
                'first_name' => 'Admin',
                'last_name' => 'User',
                'email' => env('DEV_ADMIN_EMAIL'),
                'password' => env('DEV_ADMIN_PASS')
                ];

            User::create($props);


        }catch(Exception $e){
            Log::error($e);
        }
        //
    }
}
