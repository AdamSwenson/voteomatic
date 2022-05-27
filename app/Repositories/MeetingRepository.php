<?php


namespace App\Repositories;


use App\Models\LTIConsumer;
use App\Models\Meeting;
use App\Models\User;

class MeetingRepository implements IMeetingRepository
{


    /**
     * @var ILTIRepository|mixed
     */
    public $ltiRepo;

    public function __construct()
    {

        $this->ltiRepo = app()->make(ILTIRepository::class);
    }


    /**
     * Creates a new meeting along with the LTI
     * credentials used to access it
     * @param $consumerKey
     * @param array $attrs
     * @return mixed
     */
    public function createWithResourceLink($consumerKey, $attrs = [])
    {
        $meeting = Meeting::create($attrs);

        $consumer = LTIConsumer::where('consumer_key', $consumerKey)->firstOrFail();

        $link = $this->ltiRepo->createResourceLinkEntry($consumer, $meeting, $consumer->resourceLink->id, $meeting->name);

        return $meeting;

    }

    /**
     * Creates a meeting with the user as owner and member
     *
     * If an empty meeting already exists, returns that one.
     *
     * @param User $user
     */
    public function createMeetingForUser(User $user)
    {
        $emptyMeetings = $this->getEmptyMeetingsForUser($user);

        if (sizeof($emptyMeetings) > 0) {
            return $emptyMeetings->first();
        }

        $meeting = Meeting::create();
        $meeting->addUserToMeeting($user);
        $meeting->setOwner($user);
        //in case the empty meeting was an election
        $meeting->is_election = false;
        $meeting->save();
        return $meeting;

    }

    /**
     * Creates an election with the user as owner and member
     *
     * If an empty election already exists, returns that one.
     * NB, if the empty event was a meeting (not election), sets it as election
     * @param User $user
     */
    public function createElectionForUser(User $user)
    {
        $emptyMeetings = $this->getEmptyMeetingsForUser($user);

        if (sizeof($emptyMeetings) > 0) {
            return $emptyMeetings->first();
        }

        $meeting = Meeting::create();
        $meeting->addUserToMeeting($user);
        $meeting->setOwner($user);
        $meeting->is_election = true;
        //set phase to prevent non admins from accessing
        $meeting->phase = 'setup';

        //dev To remove after VOT-177
        //Prevent users from voting by default
        $meeting->is_voting_available = false;

        $meeting->save();
        return $meeting;

    }



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
    public function getEmptyMeetingsForUser(User $user)
    {
        $out = [];

        //NB, if they created a meeting with votes and voters but deleted the name
        //and owner id, those meetings will be returned.

        // dev what about empty string instead of null?
        $meetings = $user->meetings()
            ->where('name', null)
            ->where('date', null)
            ->where('owner_id', $user->id)
            ->get();


        foreach ($meetings as $meeting) {

            if (sizeof($meeting->motions) === 0 && sizeof($meeting->users) <= 1) {
                $out[] = $meeting;
            }
        }

        return collect($out);

    }

}
