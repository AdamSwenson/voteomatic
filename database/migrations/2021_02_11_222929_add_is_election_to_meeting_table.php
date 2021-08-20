<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsElectionToMeetingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('meetings', function (Blueprint $table) {
            /** Whether the meeting is actually an election. Duh */
            $table->boolean('is_election')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        if (Schema::hasColumn('meetings', 'is_election')) {

            Schema::table('meetings', function (Blueprint $table) {
                $table->dropColumn('is_election');
            });
        }

    }
}
