<?php

namespace App\Repositories;

use App\Http\Requests\LTIRequest;
use App\Models\Meeting;

interface IUserRepository
{
    /**
     * Creates or looks up the user and logs them
     * in.
     *  //todo refactor this whole process to fit the laravel authentication patterns and utilities
     * @param LTIRequest $request
     * @param Meeting $meeting
     * @return
     */
    public function getUserFromRequest(LTIRequest $request, Meeting $meeting);
}
