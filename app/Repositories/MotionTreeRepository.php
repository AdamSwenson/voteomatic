<?php


namespace App\Repositories;


use App\Models\Meeting;
use App\Models\Motion;

/**
 * dev I think this was my initial attempt at handling the stack. Probably deprecated.
 *
 * @deprecated
 *
 * Class MotionTreeRepository
 * @package App\Repositories
 */
class MotionTreeRepository
{


    /**
     * Each set of siblings will be LIFO ordered,
     * such that the newest has the highest index.
     * @param Meeting $meeting
     */
    public function loadMotionTree(Meeting $meeting)
    {
        $out = [];

        $topLevel = Motion::where('meeting_id', $meeting->id)
            ->whereNull('applies_to')
            ->orderBy('id', 'asc')
            ->get();


        function r($motion, &$out)
        {
            $subs = $motion->getSubsidiaryMotions();
            if (sizeOf($subs) > 0) {
                foreach ($subs as $s) {
                    //push the new parent into the out array
                    $out[] = $s;
                    //and call the function recursively
                    r($s, $out);
                }
            }
        }

        foreach ($topLevel as $t) {
            r($t, $out);
        }

        return $out;

    }


}
