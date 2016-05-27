<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProfessorClassSubject extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('professor_subjects', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('professor_id', false)->unsigned();
            $table->integer('subject_id', false)->unsigned();

            $table->foreign('professor_id')->references('id')->on('users');
            $table->foreign('subject_id')->references('id')->on('subjects');
        });

        Schema::create('professor_class_subject', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('class_id', false)->unsigned();
            $table->integer('ps_id', false)->unsigned();

            $table->foreign('class_id')->references('id')->on('classes');
            $table->foreign('ps_id')->references('id')->on('professor_subjects');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('professor_class_subject');
    }
}
