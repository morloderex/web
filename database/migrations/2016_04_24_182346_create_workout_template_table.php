<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkoutTemplateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workout_template', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('exercise_id')->unsigned();
            $table->foreign('exercise_id')->references('id')->on('exercises');
            $table->integer('sets');
            $table->integer('min_reps');
            $table->integer('max_reps');
            $table->float('min_weight');
            $table->float('max_weight');
            $table->string('pause')->nullable();
            $table->string('execution')->nullable();
            $table->string('flow')->nullable();
            $table->string('thoughts')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('workout_template');
    }
}
