<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPhaseToMeetingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::table('meetings', function (Blueprint $table) {
            Schema::table('meetings', function (Blueprint $table) {
                $table->text('phase')->nullable();
                 });

//            Schema::table('meetings', function (Blueprint $table) {
//                $table->dropColumn('is_voting_available');
//                $table->dropColumn('is_complete');
//            });
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('meetings', function (Blueprint $table) {
            $table->dropColumn('phase');

        });
    }
}
