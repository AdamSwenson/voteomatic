<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeopleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('people', function (Blueprint $table) {
            $table->id();

            /** The name of the person this model represents */
            $table->text('first_name')->nullable();

            /** The name of the person this model represents */
            $table->text('last_name')->nullable();

            /** Any additional data that accompanies them. E.g., a bio or link to a profile */
            $table->json('info')->nullable();

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
        Schema::dropIfExists('people');
    }
}
