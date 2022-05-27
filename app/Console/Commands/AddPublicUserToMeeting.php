<?php

namespace App\Console\Commands;

use App\Models\Meeting;
use App\Models\User;
use Illuminate\Console\Command;

class AddPublicUserToMeeting extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:public {meeting}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Makes the public user a member of the meeting';
    public Meeting $meeting;

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
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->meeting = Meeting::find($this->argument('meeting'));
        $email = config('auth.public_user_email');
        $user = User::where('email', $email)->first();
//        $meeting = Meeting::find($this->argument('meetingId'));
        $this->meeting->addUserToMeeting($user);

        $this->info('Added public guest user to meeting: ' . $this->meeting->name);

        return 0;
    }
}
