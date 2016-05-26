<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 60);
            $table->string('slug', 70);
        });

        Schema::create('courses_subjects', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('course_id', false)->unsigned();
            $table->integer('subject_id', false)->unsigned();
            $table->tinyInteger('course_year');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('subjects');
        Schema::drop('courses_subjects');
    }
}
