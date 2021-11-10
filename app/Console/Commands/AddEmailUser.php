<?php

namespace App\Console\Commands;

use App\Models\Meeting;
use App\Models\User;
use Database\Seeders\FakeFullMeetingSeeder;
use Illuminate\Console\Command;
use Faker\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AddEmailUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:add
    {firstName : First name of user }
    {lastName : Last name of user Apostrophes will be problematic }
    {email : Unique email address }
    {password=null : If not specified, will generate and display password}
    {--admin : Create the user as an administrator }
    {--demo : Create the user and a demonstration meeting which they own}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adds a user with the ability to log in via email';

    protected $numWords = 5;

    protected $numChars = 25;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Returns the password argument. If the argument
     * is blank, creates a random password
     */
    public function getPassword(){
        $p = $this->argument('password');

        if(is_null($p) || $p === 'null'){
//            $p = Str::random($this->numChars);
            //create it
            $faker = Factory::create();
            $words = $faker->bs;
            $words = $words . ' ' . $faker->bs;
//            $words = $faker->realText();
            $words = explode(' ', $words);
shuffle($words);
            $p = implode('-', array_slice($words, 0, $this->numWords));

        }
        return $p;
    }

    public function addDemoMeeting(User $user){
        $meeting = Meeting::factory()->create();
        $meeting->setOwner($user);
        $meeting->addUserToMeeting($user);

        $this->info(secure_url('main', $meeting->id));

        $seeder = new FakeFullMeetingSeeder();
        $seeder->run($meeting, $user);
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //need to capture this since will have the encrypted string
        //if pull from user
        $password = $this->getPassword();

        $u = User::create([
            'first_name' => Str::ucfirst($this->argument('firstName')),
            'last_name' => Str::ucfirst($this->argument('lastName')),
            'email' => $this->argument('email'),
            'password'=> Hash::make($password)
        ]);

        if($this->option('admin')){
            //Make them an administrative user
            $u->is_admin = true;
            $u->save();
        }



        $this->info('Created user: ');
        $this->info($u->first_name . ' ' . $u->last_name);
        $this->info($u->email);
        $this->info($password);
        $this->newLine();

        //Set up demo meeting for them
        if($this->option('demo')){
            $this->addDemoMeeting($u);
        }


        return 0;
    }
}
