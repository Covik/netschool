<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    public $timestamps = false;

    protected $softDelete = false;

    public function classes() {
        return $this->hasMany('App\TheClass', 'course', 'course');
    }
}
