<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProfessorSubject extends Model
{
    public $fillable = ['professor_id', 'subject_id'];

    public $timestamps = false;

    protected $softDeletes = false;

    public function professor() {
        return $this->belongsTo('App\User', 'professor_id');
    }

    public function subject() {
        return $this->belongsTo('App\Subject');
    }

    public function classes() {
        return $this->belongsToMany('App\TheClass', 'professor_class_subject', 'class_id', 'cs_id');
    }
}
