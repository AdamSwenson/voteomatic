<?php

namespace App\Console\Commands;

use App\Models\Meeting;
use App\Models\User;
use Illuminate\Console\Command;

class AddUserToMeeting extends Command
{
    /**
     * Utility for manually adding a user to a meeting
     * Mostly used for development / testing
     *
     *
     * dev Add chair/admin options
     * {--chair : Create the user as a chair };
 * @var string
     */
    protected $signature = 'users:addMeeting
    {userId : Id of user }
    {meetingId : Id of meeting }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adds a user to a meeting ';

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
        $meeting = Meeting::find($this->argument('meetingId'));
        $user = User::find($this->argument('userId'));

        $meeting->addUserToMeeting($user);

        return 0;
    }
}
