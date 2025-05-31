<?php

namespace App\Jobs;

use App\Models\Meeting;
use App\Repositories\Election\IElectionRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * Creates a copy of the current election with all the same motions,
 *  users, and other information except for votes
 */
class DuplicateElection implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Meeting $meeting;

    /**
     * @return void
     */
    public function __construct(Meeting $meeting)
    {
//        Log::info($meeting);
        $this->meeting = $meeting;

    }

    /**
     * Execute the job.
     * @return void
     */
    public function handle()
    {
//dev This does not work at least when called from a command
        Log::info("Duplicating meeting id $this->meeting->id");
//throw new \Exception($this->meeting);
        $electionRepo = app()->make(IElectionRepository::class);
        $electionRepo->duplicateElection($this->meeting);
        Log::info("xxx --- xxx");
    }
}
