<?php
namespace App\Http\Transformers;

use App\User;
use App\Header_detail;
use Illuminate\Http\Request;

trait GeneralTransformer
{
    
	public function projectTransform($data)
    {	
		return $finalData = array(
			'project_name' 	=> (isset($data->name) ? $data->name : ''),
			'project_slug'	=> (isset($data->name) ? str_slug($data->name) : ''),
			'project_desc' 	=> (isset($data->description) ? $data->description : ''),
			'owner_name'	=> (isset($data->owner_name) ? $data->owner_name : ''),
			'group_name' 	=> (isset($data->group_name) ? $data->group_name : ''),
			'project_start_date'=> (isset($data->created_date_format) ? $data->created_date_format : ''),
		);
    }

    public function userTransform($data)
    {
		return $finalData = array(
			'name' 		=> (isset($data->name) ? $data->name : ''),
			'email'		=> (isset($data->email) ? $data->email : ''),
			'password' 	=> '',
		);
    }

    public function taskListTransform($data, $project_id, $flag_value)
    {
    	return $finalData = array(
			'project_id' 	=> (isset($project_id) ? $project_id : ''),
			'tasklist_id' 	=> (isset($data->id) ? $data->id : ''),
			'tasklist_name'	=> (isset($data->name) ? $data->name : ''),
			'tasklist_slug' => (isset($data->name) ? str_slug($data->name) : ''),
			'flag'			=> $flag_value,
		);
    }

    public function taskTransform($value, $tasklist_id, $header_id)
    {	
		$child = NULL;
		if(isset($value->subtasks) && ($value->subtasks)){
			$child = 1;
		}
		if(isset($value->parenttask_id)){
			$parent_task_id = Header_detail::where('zoho_unique_id',$value->parenttask_id)->first();
		}
		if(isset($value->created_by)){
			$user = User::where('zoho_user_id',$value->created_by)->first();
		}

		return $finalData = array(
			'zoho_unique_id'=> (isset($value->id) ? $value->id : ''),
			'header_id'		=> (isset($header_id) ? $header_id : ''),
			'title' 		=> (isset($value->name) ? $value->name : ''),
			'description'	=> (isset($value->description) ? $value->description : ''),
			'task_list_id' 	=> (isset($tasklist_id) ? $tasklist_id : ''),
			'work'			=> (isset($value->work) ? $value->work : ''),	
			'priority'		=> (isset($value->priority) ? $value->priority : ''),	
			'status'		=> (isset($value->status->name) ? $value->status->name : ''),	
			'start_date'	=> (isset($value->created_time_format) ? $value->created_time_format : ''),	
			'end_date'		=> (isset($value->end_date_format) ? $value->end_date_format : ''),	
			'child'			=> $child,
			'parent_id'		=> (isset($parent_task_id) ? $parent_task_id->id : NULL), 	
			'owner_id'		=> (isset($user->id) ? $user->id : NULL),	
		);
    }

    /*public function subTaskTransform($value)
    {
		if(isset($value->created_by)){
			$user = User::where('zoho_user_id',$value->created_by)->first();
		}
		if(isset($value->parenttask_id)){
			$parent_task_id = Header_detail::where('zoho_unique_id',$value->parenttask_id)->first();
		}

		return $finalData = array(
			'zoho_unique_id' => (isset($value->id) ? $value->id : ''),
			'header_id'		=> (isset($header_id) ? $header_id : ''),
			'title' 		=> (isset($value->name) ? $value->name : ''),
			'description'	=> (isset($value->description) ? $value->description : ''),
			'task_list_id' 	=> (isset($tasklist_id) ? $tasklist_id : ''),
			'work'			=> (isset($value->work) ? $value->work : ''),
			'priority'		=> (isset($value->priority) ? $value->priority : ''),	
			'status'		=> (isset($value->status->name) ? $value->status->name : ''),
			'parent_id'		=> (isset($parent_task_id) ? $parent_task_id->id : ''), 
			'start_date'	=> (isset($value->created_time_format) ? $value->created_time_format : ''),	
			'end_date'		=> (isset($value->end_date_format) ? $value->end_date_format : ''),	
			'owner_id'		=> (isset($user->id) ? $user->id : ''),	
		);
    }*/
}
?>