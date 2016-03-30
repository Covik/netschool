<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'class'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $softDelete = false;

    public function isAdmin() {
        return $this->role == config('roles.admin');
    }

    public function isProfessor() {
        return $this->role == config('roles.professor');
    }

    public function isStudent() {
        return $this->role == config('roles.student');
    }
}
