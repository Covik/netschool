<?php

namespace App;

use Faker\Provider\DateTime;
use Illuminate\Database\Eloquent\Model;
use App\Course;

class TheClass extends Model
{
    public $timestamps = false;

    protected $softDelete = false;

    public $table = 'classes';

    public function course() {
        return $this->belongsTo('App\Course');
    }

    public function students() {
        return $this->hasMany('App\User', 'class_id');
    }

    public function ps() {
        return $this->belongsToMany('App\ProfessorSubject', 'professor_class_subject', 'class_id', 'ps_id');
    }

    public function getNameAttribute() {
        return $this->getClassNumber().'.'.$this->label;
    }

    public function getClassNumber() {
        $year = date("Y") - $this->year;

        if($year < 1) $year = 1;
        if($year > $this->course->duration) $year = $this->course->duration;

        return $year;
    }

    public function files() {
        return $this->hasMany('App\File', 'class_id');
    }
}
