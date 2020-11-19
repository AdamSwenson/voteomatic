<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssignmentsTableMigration extends Migration
{
    public function up()
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->increments('id');

            /** Assignments describe the structure of an the motion tree in a meeting. This defines which meeting */
            $table->integer('meeting_id')->nullable();

            /** The motion whose place in the structure is being defined */
            $table->integer('motion_id')->nullable();

            /** The id of the motion which is the parent of the identified motion. Null if root */
            $table->integer('parent_id')->unsigned()->nullable();


            $table->integer('position', false, true);

            $table->softDeletes();

            $table->foreign('parent_id')
                ->references('id')
                ->on('assignments')
                ->onDelete('set null');


        });

        Schema::create('assignment_closure', function (Blueprint $table) {
            $table->increments('closure_id');

            $table->integer('ancestor', false, true);
            $table->integer('descendant', false, true);
            $table->integer('depth', false, true);

            $table->foreign('ancestor')
                ->references('id')
                ->on('assignments')
                ->onDelete('cascade');

            $table->foreign('descendant')
                ->references('id')
                ->on('assignments')
                ->onDelete('cascade');

        });
    }

    public function down()
    {
        Schema::dropIfExists('assignment_closure');
        Schema::dropIfExists('assignments');
    }
}
