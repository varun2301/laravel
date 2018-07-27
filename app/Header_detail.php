<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Header_detail extends Model
{
    protected $fillable = [
        'zoho_unique_id','header_id', 'title','description','owner_id','task_list_id','work','priority','status','start_date','end_date','parent_id','child',
    ];

    public function task_list() {
     	return $this->belongsTo('App\TaskList');
    }

    public function logs() {
     	return $this->hasMany('App\Log');
    }
}
