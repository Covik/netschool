<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    public $timestamps = false;

    protected $softDeletes = false;

    public function courses() {
        return $this->belongsToMany('App\Course', 'courses_subjects');
    }
}
