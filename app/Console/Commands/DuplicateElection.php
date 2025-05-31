<?php

namespace App\Console\Commands;

use App\Models\Meeting;
use App\Repositories\Election\ElectionRepository;
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
     *
     * @return int
     */
    public function handle()
    {
        $meeting = Meeting::find($this->argument('meeting'));

        $er = new ElectionRepository();
        $newElection = $er->duplicateElection($meeting);
        $this->line("Election $meeting->id $meeting->name duplicated");
        $this->line("New election id: $newElection->id");

//
////        try {
//            $meeting = Meeting::find($this->argument('meeting'));
//            $this->line($meeting);
//
//            \App\Jobs\DuplicateElection::dispatch($meeting);
//
//            $this->line("Meeting $meeting->id $meeting->name duplicated");
//
            return 0;
//        } catch (\Exception $e) {
//            $this->error($e->getMessage());
//        }
    }
}
