<?php

namespace App\Providers;

use App\Models\Election\Candidate;
use App\Models\Election\PoolMember;
use App\Policies\CandidatePolicy;
use App\Policies\PoolMemberPolicy;
use App\Policies\MeetingPolicy;
use App\Policies\PersonPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        Meeting::class => MeetingPolicy::class,
        Candidate::class =>CandidatePolicy::class,
        Person::class => PersonPolicy::class,
        PoolMember::class=> PoolMemberPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
