<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMaxWinnersToMotionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('motions', function (Blueprint $table) {
            /** For elections, this defines how many people can be elected to the office */
            $table->integer('max_winners')->nullable();
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
            if(Schema::hasColumn('motions', 'max_winners')){
                Schema::dropColumns('motions', 'max_winners');
            }
        });
    }
}
