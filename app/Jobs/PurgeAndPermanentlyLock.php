<?php

namespace App\Jobs;

use App\Models\Meeting;
use App\Repositories\Election\ElectionAdminRepository;
use App\Repositories\Election\IElectionAdminRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * For maximal anonymity, purges record of who voted (but not votes)
 * and prevents voting from being reopened.
 */
class PurgeAndPermanentlyLock implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Meeting $meeting)
    {
        $this->meeting = $meeting;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $electionAdminRepo = app()->make(IElectionAdminRepository::class);
        $electionAdminRepo->purgeAndPermanentlyLockElection($this->meeting);
    }
}
