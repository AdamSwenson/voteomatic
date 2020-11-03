<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMotionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('motions', function (Blueprint $table) {
            /** The thing being voted upon */
            $table->text('content')->nullable();

            /** Helper text to display */
            $table->text('description')->nullable();

            /** Majority (0.5), 2/3 (0.75), etc */
            $table->float('requires')->nullable();

            /** Main, procedural, amendment, etc */
            $table->text('type')->nullable();

            /** Whether the motion has been voted upon  */
            $table->boolean('is_complete')->default(false);

            /** Whether this motion is the one pending before the body */
            $table->boolean('is_current')->default(false);

            $table->integer('meeting_id')->nullable();


            $table->id();
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
        Schema::dropIfExists('motions');
    }
}
