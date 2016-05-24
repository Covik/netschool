<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    public $timestamps = false;

    public function classes() {
        return $this->hasMany('App\TheClass', 'course', 'course');
    }
}
