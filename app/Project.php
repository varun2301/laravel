<?php

namespace App;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_name', 'project_slug', 'project_desc','owner_name','group_name','zoho_project_id'
    ];
    
    public function users()
    {
        return $this->belongsToMany('App\User');
    }

    public function list(){
        return $this->hasMany('App\TaskList');
    }
}
