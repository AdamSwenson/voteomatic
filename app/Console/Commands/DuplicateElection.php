<?php

namespace App\Console\Commands;

use App\Models\Meeting;
use App\Repositories\Election\ElectionRepository;
use App\Repositories\Election\IElectionRepository;
use Illuminate\Console\Command;

class DuplicateElection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'election:duplicate {meeting}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Duplicates the offices, propositions, candidates and voters for an election';

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
     * NB, not using \App\Jobs\DuplicateElection::dispatch($meeting) because
     * we wouldn't be able to display the new meeting id;
     * @return int
     */
    public function handle()
    {
        $meeting = Meeting::find($this->argument('meeting'));

        $electionRepo = app()->make(IElectionRepository::class);
        $newElection = $electionRepo->duplicateElection($meeting);
        $this->line("Election $meeting->id $meeting->name duplicated");
        $this->line("New election id: $newElection->id");

        return 0;
    }
}
