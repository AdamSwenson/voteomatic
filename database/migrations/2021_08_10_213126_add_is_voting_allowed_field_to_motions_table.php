<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsVotingAllowedFieldToMotionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('motions', function (Blueprint $table) {
            $table->boolean('is_voting_allowed')->nullable();
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
            $table->dropColumn('is_voting_allowed');
        });
    }
}
