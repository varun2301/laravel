<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $fillable = [ 'header_detail_id','user_id', 'zoho_log_id' ,'log_time', 'start_date_time','end_date_time','bill_status','approval_status','project_id'];


    public function header_detail() {
     	return $this->belongsTo('App\Header_detail');
    }

    public function user() {
     	return $this->belongsTo('App\User');
    }

    public function getUserDetail()
    {
        return $this->belongsToMany('App\User');
    }

    
    /**
     * getting log and its related data based on parameters of log
     *
     * @param  $condition  array
     * @return \Illuminate\Http\Response
    */
    public function getUserLog($condition = array())
    {
        $logQuery = $this->query();
        //dd($condition);
        if((isset($condition['start_date'])) && ($condition['start_date']))
            $logQuery->where('start_date_time', '>=',$condition['start_date']);

        if((isset($condition['end_date'])) && ($condition['end_date']))
            $logQuery->where('end_date_time', '>=',$condition['end_date']);

        $logQuery->with(['header_detail' => function($headerDeatil){
                        $headerDeatil->with(['task_list' => function($taskList){
                            $taskList->with(['project' => function($projectDetail){
                                $projectDetail->orderBy('project_name','desc');
                            }]);
                        }]);
                    }])
                    ->with('user')
                    ->latest();

        if(($condition['records']) && ($condition['records'] == 'all'))
            $response = $logQuery->get()->toArray();
        else
            $response = $logQuery->paginate(10);

        dd($response);
        return $response;            
    }
}
