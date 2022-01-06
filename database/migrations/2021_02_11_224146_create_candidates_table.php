<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCandidatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();

//            /** The name of the person this model represents */
//            $table->text('first_name')->nullable();
//
//            /** The name of the person this model represents */
//            $table->text('last_name')->nullable();
//
//            /** Any additional data that accompanies them. E.g., a bio or link to a profile */
//            $table->text('info')->nullable();

            /** The motion representing the election for a office */
            $table->integer('motion_id');

            /** The person who has been nominated to the office  */
            $table->integer('person_id');

            /** Whether the person became a candidate through being written in on a ballot */
            $table->boolean('is_write_in')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('candidates');
    }
}
