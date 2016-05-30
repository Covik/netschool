<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $softDeletes = false;

    public function canModify() {
        $user = \Auth::user();
        $psCan = $this->theClass->ps()->where('professor_id', '=', $user->id)->where('subject_id', '=', $this->subject->id)->first();
        return $user->isAdmin() || $this->user_id == $user->id || ($user->isProfessor() && $psCan !== null);
    }

    public function getDownload() {
        return route('file-download', [$this->id, $this->filename]);
    }

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function theClass() {
        return $this->belongsTo('App\TheClass', 'class_id');
    }

    public function subject() {
        return $this->belongsTo('App\Subject');
    }
    
    public function scopeClassAndYear($query, TheClass $class) {
        return $query->where('class_id', '=', $class->id)->where('class_year', '=', $class->getClassNumber());
    }
}
