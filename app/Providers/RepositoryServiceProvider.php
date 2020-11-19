<?php

namespace App\Providers;

use App\Repositories\Assignment\AssignmentRepository;
use App\Repositories\Assignment\IAssignmentRepository;
use App\Repositories\Assignment\IReviewAssignmentRepository;
use App\Repositories\Assignment\ReviewAssignmentRepository;
use App\Repositories\Exam\IExamRepository;
use App\Repositories\IMotionStackRepository;
use App\Repositories\IVoterEligibilityRepository;
use App\Repositories\MotionStackRepository;
use App\Repositories\VoterEligibilityRepository;
use Illuminate\Support\ServiceProvider;

/**
 * Class RepositoryServiceProvider
 *
 *
 * Handles the registration of repositories which are in charge of
 * any complicated operations on the database layer.
 *
 * @package App\Providers
 */
class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

        $this->app->bind(IVoterEligibilityRepository::class, VoterEligibilityRepository::class);
        $this->app->bind(IMotionStackRepository::class, MotionStackRepository::class);
        $this->app->bind(IAssignmentRepository::class, AssignmentRepository::class);

//        $this->app->bind(IAssignmentRepository::class, AssignmentRepository::class);

//        $this->app->bind(IReviewAssignmentRepository::class, ReviewAssignmentRepository::class);
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
