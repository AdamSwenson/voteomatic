<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


/**
 * Class CreateConsumersTable
 * These are apps which connect to PWB.
 * For now, there will only be one
 */
class CreateLTIConsumersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('l_t_i_consumers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('name')->nullable();

            //For oauth access
            $table->string('consumer_key')->nullable();
            $table->string('secret_key')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('consumers');
    }
}
