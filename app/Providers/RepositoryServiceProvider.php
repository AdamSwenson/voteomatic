<?php

namespace App\Providers;

use App\Repositories\Assignment\AssignmentRepository;
use App\Repositories\Assignment\IAssignmentRepository;
use App\Repositories\Assignment\IReviewAssignmentRepository;
use App\Repositories\Assignment\ReviewAssignmentRepository;
use App\Repositories\Exam\IExamRepository;
use App\Repositories\ILTIRepository;
use App\Repositories\IMeetingRepository;
use App\Repositories\IMotionRepository;
use App\Repositories\IMotionStackRepository;
use App\Repositories\IUserRepository;
use App\Repositories\IVoterEligibilityRepository;
use App\Repositories\LTIRepository;
use App\Repositories\MeetingRepository;
use App\Repositories\MotionRepository;
use App\Repositories\MotionStackRepository;
use App\Repositories\UserRepository;
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
        $this->app->bind(IAssignmentRepository::class, AssignmentRepository::class);

        $this->app->bind(ILTIRepository::class, LTIRepository::class);

        $this->app->bind(IMeetingRepository::class, MeetingRepository::class);

        $this->app->bind(IMotionRepository::class, MotionRepository::class);

        $this->app->bind(IMotionStackRepository::class, MotionStackRepository::class);

        $this->app->bind(IUserRepository::class, UserRepository::class);

        $this->app->bind(IVoterEligibilityRepository::class, VoterEligibilityRepository::class);

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
