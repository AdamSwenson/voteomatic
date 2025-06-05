<?php

namespace App\Console\Commands;

use App\Models\Meeting;
use App\Repositories\Election\IElectionAdminRepository;
use Illuminate\Console\Command;

class PurgeAndPermanentlyLock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'election:permalock {meeting}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Purges records of who voted and prevents voting from being reopened';

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
        $meeting = Meeting::find($this->argument('meeting'));

        if ($this->confirm('This will permanently delete voter records. It cannot be undone. Do you wish to continue?')) {
            $electionAdminRepo = app()->make(IElectionAdminRepository::class);
            $r = $electionAdminRepo->purgeAndPermanentlyLockElection($meeting);

//            \App\Jobs\PurgeAndPermanentlyLock::dispatch($meeting);
            $this->line("Permanently purged voter records and locked meeting $meeting->id");
        }
    }
}
