<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            //todo remove completely once updated other stuff
            $table->string('name')->nullable();

            $table->string('first_name');
            $table->string('last_name');

            $table->text('user_id_hash')->nullable();
            $table->integer('sis_id')->nullable();

            $table->boolean('is_admin')->default(false);

            $table->rememberToken();

            $table->timestamps();


/* ---------------------------

todo everything below was added by laravel/jetstream and should be removed if we aren't going to provide a non canvas entry point

---------------------------
*/
            //todo verify that removing uniqueness didn't cause problems. ideally email should be completely removed
//            $table->string('email')->unique();
            $table->string('email');
            $table->timestamp('email_verified_at')->nullable();

            $table->string('password');

            $table->foreignId('current_team_id')->nullable();
            $table->text('profile_photo_path')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
