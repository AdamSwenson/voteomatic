<?php

namespace App\Repositories;

use App\Models\User;

interface IMeetingRepository
{
    /**
     * Creates a new meeting along with the LTI
     * credentials used to access it
     * @param $consumerKey
     * @param array $attrs
     * @return mixed
     */
    public function createWithResourceLink($consumerKey, $attrs = []);

    /**
     * Creates a meeting with the user as owner and member
     *
     * If an empty meeting already exists, returns that one.
     *
     * @param User $user
     */
    public function createMeetingForUser(User $user);

    /**
     * If a user clicks create new meeting but doesn't fill in
     * the name or do anything else, we will want to reuse that
     * object when they try to create another one. That will prevent having
     * to deal with a bunch of title-less meetings.
     *
     * This is handled here since there are potentially a bunch of criteria
     * we want to apply to determine which meetings are empty.
     *
     * dev Do we want to check that no one else is associated with the meeting?
     *
     * dev This requires them to be both a member and owner. Why not just owner?
     *
     * dev Do we want to return the collection or just one? (There could be more than one if the name and date were deleted )
     *
     * @param User $user
     */
    public function getEmptyMeetingsForUser(User $user);
}
