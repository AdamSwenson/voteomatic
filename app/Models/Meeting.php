<?php

namespace App\Models;

use App\Models\Assignment;
use App\Models\ResourceLink;
use App\Models\Motion;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    use HasFactory;

    protected $fillable= ['date','name'];


    public function getOwner(){
        return User::find($this->owner_id);
    }

// ----------------------- start
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
     *
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
    public function motions(){
    return $this->hasMany(Motion::class);
    }

    public function resourceLink(){
        return $this->hasOne(ResourceLink::class);
    }

    public function users(){
        return $this->belongsToMany(User::class);
    }
}
