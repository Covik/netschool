<?php

namespace App;

use Faker\Provider\DateTime;
use Illuminate\Database\Eloquent\Model;
use App\Course;

class TheClass extends Model
{
    public $timestamps = false;

    public $table = 'classes';

    public function course() {
        return $this->belongsTo('App\Course');
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
}
