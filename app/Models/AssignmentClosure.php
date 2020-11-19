<?php
namespace App\Models;

use Franzose\ClosureTable\Models\ClosureTable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssignmentClosure extends ClosureTable
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'assignment_closure';
}
