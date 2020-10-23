<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateRecordedVoteRecordsTable
 * This keeps track of who has voted on a given motion
 *
 */
class CreateRecordedVoteRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recorded_vote_records', function (Blueprint $table) {
            $table->id();

            //no timestamps to avoid correlation with vote table
//            $table->timestamps();

            $table->integer('user_id');
            $table->integer('motion_id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recorded_vote_records');
    }
}
