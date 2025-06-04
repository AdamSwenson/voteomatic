<?php

namespace App\Models;

use App\Exceptions\ElectionPermalockException;
use App\Models\Assignment;
use App\Models\Motion;
use App\Models\ResourceLink;
use Database\Seeders\CSUNElectionSeeder;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

/**
 * Class Meeting
 *
 * Covers both regular meetings and elections
 *
 *
 * ===================== Election specific =====================
 * The Election lifecycle:
 * (1) setup
 * Event is created
 *       - is_voting_available = false
 *      - is_complete = false
 *      - info->is_results_available = false
 *  Chair sees:
 *      - Setup pages
 *  Users see:
 *      - Premature access card
 *
 * (2) nominations
 * Authorized users nominate candidates
 *  Chair sees:
 *      - Setup and nomination pages
 *  Users see:
 *      - Nomination pages
 *
 * (3) voting
 *  Voting is opened to all users
 *      - is_voting_available = true
 *      - is_complete = false
 *      - info->is_results_available = false
 *  Chair sees:
 *      - All
 *  Users see:
 *      - Vote card
 *      - Verify vote card
 *
 * (4) closed
 * Voting is closed
 *  At this point, no one may cast votes. The chair/election admin may view the results
 *  but regular users cannot see the results.
 *      - is_voting_available = false
 *      - is_complete = true
 *      - info->is_results_available = false
 *  Chair sees:
 *      - Results
 *  Users see:
 *      - Voting closed card
 *
 * (5) results
 * Results are released
 *  Any user may view the results. (Potentially: Results may be made available on public page)
 *      - is_voting_available = false
 *      - is_complete = true
 *      - info->is_results_available = true
 *  Chair
 *      - All
 *  Users see:
 *      - Results
 *      - Vote verify
 *
 *
 * @package App\Models
 */
class Meeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'name',
        'info',
        'phase',

        // ---------------- Election specific
        //dev deprecated after VOT-177
        /** If an election, whether the chair has closed voting */
        'is_complete',

        'is_election',

        //dev deprecated after VOT-177
        /** If an election, determines whether any user can vote */
        'is_voting_available',
        /** The names of informational fields about candidates which should be shown to voters */
        'info->candidateFields',

        //dev deprecated after VOT-177
        'info->is_results_available',

        /** Whether the election can be reopened for voting */
        'is_permalocked'
    ];

    protected $casts = [
        'is_election' => 'boolean',
        'is_voting_available' => 'boolean',
        'is_complete' => 'boolean',
        'info' => AsArrayObject::class,
    ];

    protected $appends = ['election_phase'];

    /* =======================
        Authentication and Authorization
     ======================= */
    /**
     * @return User
     */
    public function getOwner()
    {
        return User::find($this->owner_id);
    }

    /**
     * Adds the provided user as an owner of the
     * meeting.
     *
     * @param User $user
     */
    public function setOwner(User $user)
    {
        $this->owner_id = $user->id;
        $this->save();
    }

    /**
     * Returns true if the user is the meeting's owner. False otherwise
     * @param User $user
     * @return bool
     */
    public function isOwner(User $user)
    {
        return $user->is($this->getOwner());
    }


    /**
     * Checks whether the user is on the meeting roster.
     * This is a helpful shortcut for various authorization tasks
     *
     * @param User $user
     */
    public function isPartOfMeeting(User $user)
    {
        return !is_null($this->users()->where('id', $user->id)->first());
    }

    /**
     * Adds the user to the meeting roster
     * @param User $user
     */
    public function addUserToMeeting(User $user)
    {
        $this->users()->attach($user);
        $this->push();
    }



    /* =======================
        Election specific
     ======================= */

    /**
     * If an election, returns a string which represents where
     * in the election lifecycle we are. This is used by the client
     * to determine what to show the user
     *
     * The Election lifecycle:
     * (1) setup
     * Event is created
     *       - is_voting_available = false
     *      - is_complete = false
     *      - info->is_results_available = false
     *  Chair sees:
     *      - Setup pages
     *  Users see:
     *      - Premature access card
     *
     * (2) nominations
     * Authorized users nominate candidates
     *  Chair sees:
     *      - Setup and nomination pages
     *  Users see:
     *      - Nomination pages
     *
     * (3) voting
     *  Voting is opened to all users
     *      - is_voting_available = true
     *      - is_complete = false
     *      - info->is_results_available = false
     *  Chair sees:
     *      - All
     *  Users see:
     *      - Vote card
     *      - Verify vote card
     *
     * (4) closed
     * Voting is closed
     *  At this point, no one may cast votes. The chair/election admin may view the results
     *  but regular users cannot see the results.
     *      - is_voting_available = false
     *      - is_complete = true
     *      - info->is_results_available = false
     *  Chair sees:
     *      - Results
     *  Users see:
     *      - Voting closed card
     *
     * (5) results
     * Results are released
     *  Any user may view the results. (Potentially: Results may be made available on public page)
     *      - is_voting_available = false
     *      - is_complete = true
     *      - info->is_results_available = true
     *  Chair
     *      - All
     *  Users see:
     *      - Results
     *      - Vote verify
     *
     */
    public function getElectionPhaseAttribute()
    {
        if (!$this->is_election) return null;
        return $this->phase;
        //dev remove when ready
//        try {
//
//            if (!$this->is_voting_available && !$this->is_complete && !$this->info['is_results_available']) return 'setup';
//
//            //dev nominations
//
//            if ($this->is_voting_available && !$this->is_complete && !$this->info['is_results_available']) return 'voting';
//
//            if (!$this->is_voting_available && $this->is_complete && !$this->info['is_results_available']) return 'closed';
//
//            if (!$this->is_voting_available && $this->is_complete && $this->info['is_results_available']) return 'results';
//        } catch (\ErrorException $e) {
//            Log::info("Old style meeting object lacks phase fields \n" . $e);
//            return null;
//        }
    }

    /**
     * Makes it possible for voters to vote
     */
    public function openVoting()
    {
        //Added VOT-286
        $settings = $this->getMasterSettingStore();
        if(! is_null($settings)){
            //check whether the meeting could be
            if($settings->getSetting('permalock_election') === true && $this->is_permalocked === true){
                throw new ElectionPermalockException();
            }
        }


        $this->phase = 'voting';

        //dev Remove after VOT-177
//        $this->is_voting_available = true;
//        if ($this->is_complete === true) $this->is_complete = false;
//        //ensure that no one can see the results
//        if (array_key_exists('is_results_available', $this->info) && $this->info['is_results_available'] === true) {
//            $this->info['is_results_available'] = false;
//        }

        $this->save();
    }

    /**
     * Removes the ability for anyone to vote in the election
     * and indicates that the election is complete
     */
    public function closeVoting()
    {
        $this->phase = 'closed';

//        //dev Remove after VOT-177
//        $this->is_voting_available = false;
//        $this->is_complete = true;
//
//        //set availability of results since the key may not yet exist
//        $info = $this->info;
//        $info['is_results_available'] = false;
//        $this->info = $info;
//        $this->info['is_results_available'] = false;

        $this->save();
    }

    /**
     * Make it possible for any user to see the results of
     * the election
     */
    public function releaseElectionResults()
    {
        $this->phase = 'results';

        //dev This is probably problematic since results and closed are 2 separate phases
        // it is causing trouble with the tests but doesn't seem to actually be used.
        $this->closeVoting(); //just in case someone jumps straight to releasing

        //dev Remove after VOT-177
//        $this->info['is_results_available'] = true;

        $this->save();
    }

    /**
     * Prevent any non-chair/admin user from seeing the results
     * of the election
     */
    public function hideElectionResults()
    {
        $this->phase = 'closed';

        //dev Remove after VOT-177
//        $this->info['is_results_available'] = false;

        $this->save();
    }

    /* =======================
    Settings
    ======================= */
    /**
     * Returns the master settings store.
     * VOT-272: NOT SURE WHY DEPRECATED OR WHAT TO USE INSTEAD
     * @deprecated
     * @return mixed
     */
    public function getMasterSettingStore()
    {
        return SettingStore::where('meeting_id', $this->id)
            ->where('is_meeting_master', true)
            ->first();

    }


    /* =======================
        Relationships
     ======================= */
    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }


    /**
     * All the motions introduced at the meeting
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function motions()
    {
        return $this->hasMany(Motion::class);
    }

    public function resourceLink()
    {
        return $this->hasOne(ResourceLink::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Returns all associated settings stores
     * Changed to hasMany from hasOne in VOT-272
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function settingStore()
    {
        return $this->hasMany(SettingStore::class);
    }



// ----------------------- start attic
//These are from an initial attempt at the motion tree based on
// the gradeomatic structures. May end up using later if need much richer
// tree. But presently not used.

    /**
     * Adds an Motion to the meeting either with the meeting itself
     * or another motion as the parent.
     * Returns the Assignment object representing the location of the
     * motion in the heirarchy.
     *
     * todo dev unused
     * @deprecated
     * @param Motion $motion
     * @param $parentId
     * @param $depth
     * @return
     */
    public function assignMotion(Motion $motion, $parentId, $depth)
    {
        //we need the meeting id of the parent
        //to link them, so get the parent meeting
        //This is okay as long as we can presume that
        //if we haven't processed the parent yet.
        // it will be updated when we get to it.
        $parentAssign = Assignment::firstOrCreate(
            [
                'meeting_id' => $this->id,
                'motion_id' => $parentId
            ]);

        //Now we can make the actual meeting entry
        $assignment = Assignment::firstOrCreate([
            'meeting_id' => $this->id,
            'motion_id' => $motion->id,
        ]);

        //and finally associate it into the tree.
        $parentAssign->addChild($assignment, $depth);

        return $assignment;
    }

    /**
     * Returns the Assignment representing the meeting
     * which serves as the root for the motion tree
     * @deprecated
     */
    public function getAssignmentRoot()
    {
        return $this->assignments()
            ->where('meeting_id', $this->id)
            ->where('parent_id', '=', null)
            ->where('motion_id', '=', null)
            ->first();


//        return Assignment::where('meeting_id', $this->id)
//            ->where('parent_id', null)
////            ->where('motion_id', $this->id)
//            ->first();
    }


    /**
     * Creates an entry in the assignments table
     * with this meeting's id as motion_id and meeting_id
     * parent_id will be null
     * @return bool
     */
    public function initializeAssignmentRoot()
    {
        if ($this->getAssignmentRoot()) return true;
        $assignment = Assignment::create([
//            'motion_id' => $this->id,
            'motion_id' => null,
            'meeting_id' => $this->id,
            'parent_id' => null
        ]);

        $this->assignments()->save($assignment);
    }

    /**
     * If was associated with assignments, deletes the association
     * and creates a new meeting
     * If was none preexisting, creates new
     */
    public function resetAssignments()
    {
        //delete all motions from meeting table with this
        //meeting id
        Assignment::where('meeting_id', $this->id)->delete();
        //create a new meeting
        $this->initializeAssignmentRoot();
    }

    //----------------------- end


}
