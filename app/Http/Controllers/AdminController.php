<?php

namespace App\Http\Controllers;

use App\User;
use App\Project;
use App\Portal;
use App\ZohoDetail;
use App\Header;
use App\Header_detail;
use App\Log;
use App\Traits\Helper;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    use Helper;
    public function __construct(Project $project, User $user, Portal $portal, ZohoDetail $zohodetail, Header $header, Header_detail $header_detail, Log $log)
    {
        $this->middleware('auth');
        $this->project          = $project;
        $this->user             = $user;
        $this->portal           = $portal;
        $this->zohodetail       = $zohodetail;
        $this->header           = $header;
        $this->header_detail    = $header_detail;
        $this->log              = $log;
    }
    
    public function index()
    {
        try
        {
            $showButton = true;
            $portalGen  = false;

            $checkRecord = $this->zohodetail->count();
            if(($checkRecord) > 0)
            {
                $checkRecord = $this->zohodetail->first();
                $showButton = false;
                $portalGen  = true;
            } 

            $protalRecord = $this->portal->count();
            if(($protalRecord) > 0)
            {
                $protalRecord = $this->portal->first();
                $portalGen  = false;
            }   

            
            $userCount = $this->user->count();  //count no of users in system
            $projectCount = $this->project->count();    //count no of projects in system
            $headerDeatil = $this->header->where('header_name','LIKE',"%tasks%")->first();  //count no of tasks in system
            $header_id = $headerDeatil->id;
            $taskCount = $this->header_detail->where('header_id',$header_id)->count();

             $bugId = $this->header->where('header_name','LIKE',"%bugs%")->first();  //count no of bugs in system
            $bugCount = $this->header_detail->where('header_id',$bugId->id)->count();

            return view('admin/home',compact('showButton','portalGen','userCount','projectCount','taskCount','bugCount'));
        } catch (\Exception $e) {
            dd($e); 
        }
    }

    public function getUser(Request $request)
    {
        $project_id = $request->project_id;
        $project_user = $this->user
                        ->whereHas('getProject', function($q) use($project_id){
                            $q->where('project_id',$project_id);
                        })
                        ->get();
        $allProjectUser = $project_user->toArray();
        echo json_encode($allProjectUser);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function logDetail()
    {
        $projectRecord   = $this->project::all();
        $projects = array();
        $projects[''] = "Select Project";
        foreach($projectRecord as $project) {
            $projects[$project->id] = $project->project_name;
        }
        return view('admin/log',compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function employee_list()
    {
        $userRecord   = $this->user::all();
        $users = array();
        $users[''] = "Select Employee";
        foreach($userRecord as $user) {
            $users[$user->id] = $user->name;
        }
        return view('admin/employee',compact('users'));
    }

    public function getTimeLog(Request $request)
    {   
        $response = array();
        $user_id    = $request->user_id;
        $start_date = date('Y-m-d', strtotime($request->start_date)) . ' 00:00:00';
        $end_date   = date('Y-m-d', strtotime($request->end_date)) . ' 23:59:59';

        $response = $this->user
                        ->with(['getProject' => function($tasklist) use($start_date,$end_date,$user_id){
                                $tasklist->with(['list' => function($task) use($start_date,$end_date,$user_id){
                                    $task->with(['details' => function($log) use($start_date,$end_date,$user_id){
                                        $log->with(['logs' => function($logs) use($start_date,$end_date,$user_id){
                                            $logs->where('start_date_time','>=',$start_date)
                                            ->where('end_date_time','<=',$end_date)
                                            ->where('user_id',$user_id);
                                        }]);
                                    }]);
                                }]);
                        }])
                    ->where('id',$user_id)
                    ->get()
                    ->toArray();
                    //->toSql();

        //echo json_encode($response);
        $view = \View::make('admin.employee-log-list',compact('response'));
        $data = $view->render();
        return $data;
    }

    public function getTimeLog1(Request $request)
    {   
        $response = array();
        $response = $this->user
                        ->with(['getProject' => function($tasklist){
                                $tasklist->with(['list' => function($task){

                                    $task->with(['details' => function($log){
                                        $log->with(['logs' => function($logs){
                                            $logs->where('start_date_time', '>=','2018-07-11')
                                                ->where('end_date_time', '<=','2018-07-13')
                                                ->where('user_id',46);
                                        }]);
                                    }]);
                                }]);
                        }])
                    ->where('id',46)
                    ->get()
                    ->toArray();
                    //->toSql();

        dd($response[0]['get_project'][0]['list']);
    }

    /**
     * List all logs of all employees with pagination.
     *
     * @return \Illuminate\Http\Response
    */   
    public function userLog()
    {
        try
        {
            $response = array();
            $response = $this->log
                        ->with(['header_detail' => function($query){
                        $query->with(['task_list' => function($que){
                            $que->with(['project' => function($q){
                                $q->orderBy('project_name','desc');
                            }]);
                        }]);
                        }])
                        ->with('user')
                        ->latest()
                        ->paginate(10);
            /*$condition = array(
                'records' => 'paginate'
            );         

            $response = $this->log->getUserLog($condition);*/                    
            
            return view('admin.user-log',compact('response'));
        } catch (\Exception $e) {
            dd($e); 
        }   
    }

    /**
     * Download records of employee based on passed date range in xls,xlsx or csv format.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return downloaded file in xls,xlsx or csv format
    */
    public function generateSheet(Request $request)
    {
        try
        {
            $response = array();
            $type = $request->type;
            
            $date = $request->date;
            $date = explode("-",$date);
            
            $weekStartDate = date("Y-m-d", strtotime($date[0])) . ' 00:00:00';
            $weekEndDate = date("Y-m-d", strtotime($date[1])) . ' 23:59:59';

            /*$condition = array(
                'start_date'    => $weekStartDate,
                'end_date'      => $weekEndDate,   
                'records'       => 'all'
            );         

            $response = $this->log->getUserLog($condition);*/

            $response = $this->log
                        ->with(['header_detail' => function($query){
                        $query->with(['task_list' => function($que){
                            $que->with(['project' => function($q){
                                $q->orderBy('project_name','desc');
                            }]);
                        }]);
                        }])
                        ->with('user')
                        ->where('start_date_time', '>=',$weekStartDate)
                        ->where('end_date_time', '<=',$weekEndDate)
                        ->get()
                        ->toArray();

            $data = array();
            if(count($response) > 0) {
                foreach ($response as $log) {
                    if(isset($log['user']) && count($log['user']) > 0) {
                        $temp['Employee Name'] = (isset($log['user']['name']) ? $log['user']['name'] : '');
                    }

                    if(isset($log['header_detail']) && count($log['header_detail']) > 0) {
                        if(isset($log['header_detail']['task_list']) && count($log['header_detail']['task_list']) > 0) {    
                            if(isset($log['header_detail']['task_list']['project']) && count($log['header_detail']['task_list']['project']) > 0) {
                                $temp['Project Name'] = (isset($log['header_detail']['task_list']['project']['project_name']) ? $log['header_detail']['task_list']['project']['project_name'] : '');
                            }
                        }
                        
                        $temp['Task Name'] = (isset($log['header_detail']['title']) ? ($log['header_detail']['title']) : '');
                        $temp['Planned Time'] = (isset($log['header_detail']['work']) ? ($log['header_detail']['work']) : 0);
                    }

                    $temp['Log Time'] = (isset($log['log_time']) ? ($this->convertToHoursMins($log['log_time'], '%02d:%02d')) : '');

                    $data[] = $temp;
                }
            } else {
                $data[] = '';
            }

            return \Excel::create('report_data', function($excel) use ($data) {
                $excel->sheet('sheet name', function($sheet) use ($data)
                {
                    $sheet->fromArray($data);
                });
            })->download($type);
        } catch (\Exception $e) {
            dd($e); 
        }

    }
}
