<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use PDOException;

class AdminUserSeeder extends Seeder
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
                'first_name' => 'Admin',
                'last_name' => 'User',
                'email' => env('DEV_USER_ADMIN_EMAIL'),
                'password' => env('DEV_USER_ADMIN_PASSWORD'),
                'is_admin' => true
            ];

            User::create($props);

        }
        catch(PDOException $e1){
            Log::error($e1);
        }
        catch(QueryException $e2)
        {
            Log::error($e2);
        }

        catch(Exception $e){
            Log::error($e);
        }
    }
}
