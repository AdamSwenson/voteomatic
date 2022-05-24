<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Makes a user who will be used by guests to access
 * the public pages
 */
class PublicAccessUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {

            $props = [
                'is_admin' => false,
                'first_name' => 'Guest',
                'last_name' => 'User',
                'email' => env('PUBLIC_ACCESS_USER_EMAIL'),
                'password' => Str::random(30),
            ];

            User::create($props);

        } catch (\Exception $e) {
            Log::error($e);
        } catch (QueryException $e2) {
            Log::error($e2);
        }


    }
}
