<?php


namespace App\Repositories\Election;


use App\Http\Requests\CandidateRequest;
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
