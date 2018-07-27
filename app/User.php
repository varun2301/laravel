<?php

namespace App;
use App\Project;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    //public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'zoho_user_id','name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function projects()
    {
        return $this->hasMany('App\Project');
    }

    public function project_user()
    {
        return $this->hasMany('App\Project', 'project_user');
    }

    public function getProject()
    {
        return $this->belongsToMany('App\Project', 'project_user', 'user_id', 'project_id');
    }
}
