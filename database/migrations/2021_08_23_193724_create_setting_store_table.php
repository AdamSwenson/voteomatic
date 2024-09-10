<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingStoreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setting_stores', function (Blueprint $table) {
            $table->id();

            /** The user who can alter the settings  */
            $table->integer('user_id')->nullable();

            /** The meeting the settings apply to */
            $table->integer('meeting_id')->nullable();

            $table->integer('is_meeting_master')->nullable();

            /** Whether the settings apply to everyone (chairs included) */
            $table->boolean('is_universal')->nullable();

            /** Whether the settings cover all members */
            $table->boolean('applies_to_all_members')->nullable();

            /** The actual settings */
            $table->json('settings')->nullable();


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
        Schema::dropIfExists('settings');
    }
}
