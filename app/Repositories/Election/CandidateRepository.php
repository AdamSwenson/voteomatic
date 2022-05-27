<?php


namespace App\Repositories\Election;


use App\Exceptions\BallotStuffingAttempt;
use App\Exceptions\WriteInDuplicatesOfficial;
use App\Http\Requests\CandidateRequest;
use App\Http\Requests\Election\WriteInCandidateRequest;
use App\Models\Election\Candidate;
use App\Models\Election\Person;
use App\Models\Election\PoolMember;
use App\Models\Motion;

class CandidateRepository implements ICandidateRepository
{

    /**
     * @param string $first_name
     * @param string $last_name
     * @param null|array $info
     */
    public function createPerson($first_name = '', $last_name = '', $info = null)
    {
        return Person::create([
            'first_name' => $first_name,
            'last_name' => $last_name,
            'info' => $info,
        ]);
    }


    /**
     * Makes the person eligible to be nominated for the office
     *
     * @param Motion $motion
     * @param Person $person
     * @return mixed
     */
    public function addPersonToPool(Motion $motion, Person $person)
    {

        return PoolMember::create([
            'motion_id' => $motion->id,
            'person_id' => $person->id
        ]);
//        //NB, id here is the pool member's id
//        if ($request->has('id')) {
//            //Check if the pool member has been added as a candidate yet
//            $candidate = Candidate::where('pool_member_id', $request->id)
//                ->where('motion_id', $motion->id)
//                ->first();
//
//            if (is_null($candidate)) {
//        $candidate = Person::create([
//            'first_name' => $request->first_name,
//            'last_name' => $request->last_name,
//            'info' => $request->info,
////            'pool_member_id' => $request->id,
////            'motion_id' => $motion->id
//        ]);
//            }
//        }


//        $candidate->motion()->associate($motion);

//dd($motion);
//        return $candidate;
    }


    /**
     * Makes a person into a nominee for a given office
     *
     * @param Motion $motion
     * @param Person $person
     * @param null $is_write_in
     * @return mixed
     */
    public function addCandidateToBallot(Motion $motion, Person $person, $is_write_in = null)
    {
        return Candidate::create([
            'motion_id' => $motion->id,
            'person_id' => $person->id,
            'is_write_in' => $is_write_in
        ]);
//
//
////        //NB, id here is the pool member's id
////        if ($request->has('id')) {
////            //Check if the pool member has been added as a candidate yet
////            $candidate = Candidate::where('pool_member_id', $request->id)
////                ->where('motion_id', $motion->id)
////                ->first();
////
////            if (is_null($candidate)) {
//        $candidate = Candidate::create([
//            'first_name' => $request->first_name,
//            'last_name' => $request->last_name,
//            'info' => $request->info,
//            'pool_member_id' => $request->id,
//            'motion_id' => $motion->id
//        ]);
//            }
//        }


//        $candidate->motion()->associate($motion);

//dd($motion);
//        return $candidate;
    }

    /**
     * Creates a write-in candidate
     *
     * @param Motion $motion
     * @param string $first_name
     * @param string $last_name
     * @param null $info
     * @return mixed
     */
    public function addWriteInCandidate(Motion $motion, $first_name='', $last_name='', $info=null ){

        //Check for duplicates again (we did this in middleware, to block duplication
        // of official candidates. However, multiple people could write someone in, so
        //we will just reuse that candidate and check for sneakiness when the vote is recorded
        $candidate = $this->checkForDuplication($first_name, $last_name, $info, $motion);

        if(! is_null($candidate)) return $candidate;

        //There wasn't a duplicate, so we create a new candidate

        //First create a person
        $person = Person::create([
            'first_name' => $first_name,
            'last_name' => $last_name,
            'info' => $info,
        ]);

        //Now make them a candidate
        return Candidate::create([
            'motion_id' => $motion->id,
            'person_id' => $person->id,
            'is_write_in' => true
        ]);

    }

    /**
     * Utility method for comparing the info arrays of two person
     * objects
     * @param Person $checked
     * @param Person $reference
     * @return bool
     */
    public function doesInfoMatch(Person $checked, Person $reference){
        $checkedInfo = $checked->info;
        $referenceInfo = $reference->info;

        return $checkedInfo == $referenceInfo;
    }


    /**
     * Used when creating write in candidates to see if there already
     * is a candidate in the database who matches exactly.
     *
     * If there is and they were also written in, we will return the
     * candidate so they may be reused (we will check when the vote is cast
     * to ensure they haven't done this to vote twice).
     *
     * If they duplicate an official candidate, we will reject the attempt
     *
     *
     * @param $first_name
     * @param $last_name
     * @param $info
     * @param Motion $motion
     * @return mixed
     * @throws BallotStuffingAttempt
     */
    public function checkForDuplication( $first_name, $last_name, $info, Motion $motion){

        //Get any existing people with the same name
        $possibleDuplicates = Person::where('first_name', $first_name)
            ->where('last_name', $last_name)->get();

        if(! is_null( $motion->meeting->info)) {
            //dev This fixes the issue in VOT-181. Add check for array key existing to deal with cases where no fields have been
            // specified. NB, since info casts to an ArrayObject
            // we cannot use array_key_exists
            $candidateFields =  $motion->meeting->info->offsetExists('candidateFields')  ? $motion->meeting->info['candidateFields'] : null;

            if (!is_null($candidateFields) && sizeof($candidateFields) > 0 && sizeof($info) > 0) {
                $dups = [];
                //we need to check the info array. Laravel doesn't
                //let us do that in the earlier query
                foreach ($possibleDuplicates as $person) {
                    if ($person->info == $info) {
                        $dups[] = $person;
                    }
                }
                $possibleDuplicates = collect($dups);
            }
        }

        if( $possibleDuplicates->count() > 0){
            //We now know that we have some instances of the same person.
            //But that could be fine --they could be candidates in different
            //elections or for different offices.
            //So now we need to see if any of them are already candidates
            //for this office
            foreach($possibleDuplicates as $person){
                $c = Candidate::where('person_id', $person->id)
                    ->where('motion_id', $motion->id)
                    ->first();
                if(! is_null($c)){
                    //If the candidate was a write in, we will return them
                    //so they can be reused (this could be valid if someone else had
                    //also written the same person in).
                    if($c->is_write_in){
                        return $c;
                    }
                    //If the written in person is the same as someone who is an
                    //official candidate, we will reject.
                    // While there could be edge cases depending on
                    //what fields exist in the candidate's info (e.g., if there aren't enough
                    //properties to differentiate 2 people with the same name in the same department),
                    //we're going to call this invalid
                    throw new WriteInDuplicatesOfficial($motion);
                }
            }

        }

        //No duplicates found
        return null;
    }

    /**
     * Get all official candidates for the office
     *
     * @param Motion $motion
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOfficialCandidatesForOffice(Motion $motion){
        return $motion->candidates()->official()->get();
//
//        $out = [];
//
//        foreach($candidates as $candidate){
//            $out[] = $this->makeCandidateResponse($candidate);
//        }
//
//        return $out;

    }



    public function removeCandidateFromBallot(Candidate $candidate)
    {
        return $candidate->delete();
    }

    public function removeCandidateFromPool(PoolMember $poolMember)
    {
        return $poolMember->delete();
    }


}
