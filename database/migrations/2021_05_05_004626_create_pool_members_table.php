<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePoolMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pool_members', function (Blueprint $table) {
            $table->id();

            /** The name of the person this model represents */
            $table->text('first_name')->nullable();

            $table->text('last_name')->nullable();

            /** Any additional data that accompanies them. E.g., a bio or link to a profile */
            $table->text('info')->nullable();

            /** The motion representing the election for a office they are eligible to be nominated for */
            $table->integer('motion_id')->nullable();

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
        Schema::dropIfExists('pool_members');
    }
}
