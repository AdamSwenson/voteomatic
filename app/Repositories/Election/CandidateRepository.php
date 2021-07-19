<?php


namespace App\Repositories\Election;


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

        //Check for duplicates
//        $existingPerson = $this->checkForDuplication($first_name, $last_name, $info);

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
     * Used when creating write in candidates to see if there already
     * is a candidate in the database who matches exactly.
     *
     * dev
     * Not using this yet, because need to understand all sorts of potential issue
     * e.g., what if the person has the same name as an official candidate
     *
     * @param $first_name
     * @param $last_name
     * @param $info
     */
    public function checkForDuplication( $first_name, $last_name, $info){
        //Check whether the write in candidate duplicates an existing candidate
        $possibleDuplicates = Person::where('first_name', $first_name)
            ->where('last_name', $last_name)
            ->where('info', $info)
            ->first();

        return $possibleDuplicates;
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
