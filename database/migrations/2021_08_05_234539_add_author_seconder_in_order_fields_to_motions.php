<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAuthorSeconderInOrderFieldsToMotions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('motions', function (Blueprint $table) {
            /** The user who created the motion */
            $table->integer('author_id')->nullable();
            /** The user who seconded the motion */
            $table->integer('seconder_id')->nullable();
            /** The user who ruled on whether the motion is in order */
            $table->integer('approver_id')->nullable();

            $table->boolean('is_in_order')->nullable();
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
            $table->dropColumn('author_id');
            $table->dropColumn('seconder_id');
            $table->dropColumn('is_in_order');
            $table->dropColumn('approver_id');
        });
    }
}
