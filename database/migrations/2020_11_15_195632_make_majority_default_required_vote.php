<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeMajorityDefaultRequiredVote extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('motions', function (Blueprint $table) {

            /** Majority (0.5), 2/3 (0.75), etc */
            $table->float('requires')
                ->default(.5)
                ->change();

        });



    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('motions', function (Blueprint $table) {
            /** Majority (0.5), 2/3 (0.75), etc */
            $table->float('requires')->nullable()->change();

        });
    }
}
