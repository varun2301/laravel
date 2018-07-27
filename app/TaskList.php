<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskList extends Model
{
    protected $fillable = [
        'project_id','tasklist_id' ,'tasklist_name', 'tasklist_slug','header_id','flag',
    ];

    public function project() {
     	return $this->belongsTo('App\Project','project_id');
    }

    public function details(){
    	return $this->hasMany('App\Header_detail');
    }
}
