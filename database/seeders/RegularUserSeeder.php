<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class RegularUserSeeder extends Seeder
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
                'is_admin' => false,
                'first_name' => 'Test',
                'last_name' => 'Regular User',
                'email' => env('DEV_USER_REGULAR_EMAIL'),
                'password' => env('DEV_USER_REGULAR_PASSWORD'),
            ];

            User::create($props);

        }catch(\Exception $e){
            Log::error($e);
        }
        catch(QueryException $e2)
        {
            Log::error($e2);
        }
    }
}
