<?php
namespace App\Models;

use App\Models\AssignmentClosure;
use App\Models\Meeting;
use Franzose\ClosureTable\Models\Entity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Assignment
 * Represents relationships between motions and
 * their position in the overall meeting
 *
 * @package App
 */
class Assignment extends Entity
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'assignments';

    /**
     * ClosureTable model instance.
     *
     *
     */
    protected $closure = AssignmentClosure::class; //'App\Models\AssignmentClosure';



    protected $fillable = [
        'motion_id',
        'parent_id',
        'meeting_id' ,
        'position',
        'depth'
    ];





    /* =======================
        Relationships
     ======================= */
    public function meeting(){
        return $this->belongsTo(Meeting::class );
    }

    /**
     * todo Check whether this works given that the root activity's  task_id is an activity not a task.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function motion(){
        return $this->belongsTo(Motion::class );

    }



}
