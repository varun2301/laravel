<?php

namespace App\Http\Controllers;

use DB;
use App\Header;
use App\Portal;
use App\Project;
use App\ZohoDetail;
use App\User;
use App\TaskList;
use App\Header_detail;
use App\Log;
use App\Report;
use Illuminate\Http\Request;
use App\Mail\Mail;
use App\Traits\Helper;
use App\Http\Transformers\GeneralTransformer as general_transformer;

class CronController extends Controller
{	
	use Helper;
	use general_transformer;
	public function __construct(Portal $portal, Project $project, User $user, TaskList $tasklist, ZohoDetail $zohodetail, Header_detail $header_detail, Log $log)
    {
        $this->client_id 		= \Config::get('zoho.CLIENT_ID');
        $this->secret_key 		= \Config::get('zoho.CLIENT_SECRET_KEY');
        $this->access_code 		= \Config::get('zoho.CLIENT_ACCESS_CODE');
        
        $this->portal 			= $portal;
        $this->project 			= $project;
        $this->user 			= $user;
        $this->tasklist 		= $tasklist;
        $this->zohodetail 		= $zohodetail;
        $this->header_detail 	= $header_detail;
        $this->log              = $log;
    }

    /**
     * displaying url to hit in browser and get access code
     *
     * @return display url in browser to get zoho access code
    */
    public function index()
    {
    	try
    	{
	    	$client_id    = $this->client_id;
	    	$secret_key   = $this->secret_key;
	    	$redirect_url = 'http://hestalabs.com/testZoho';

	    	$url = "https://accounts.zoho.com/oauth/v2/auth?scope=ZohoProjects.portals.READ,ZohoProjects.projects.ALL,ZohoProjects.activities.READ,ZohoProjects.status.READ,ZohoProjects.status.CREATE,ZohoProjects.milestones.ALL,ZohoProjects.tasklists.ALL,ZohoProjects.tasks.ALL,ZohoProjects.timesheets.ALL,ZohoProjects.bugs.ALL,ZohoProjects.events.ALL,ZohoProjects.forums.ALL,ZohoProjects.users.ALL&client_id=".$client_id."&response_type=code&access_type=offline&prompt=consent&redirect_uri=".$redirect_url;

	    	echo $url;
	    }
    	catch (\Exception $e) {
    		dd($e);	
    	}	
    }

    /**
     * generating access token for accessing api's
     *
     * @return ajax response
    */
    public function getAccessToken()
    {
    	try
    	{
    		$response = array();
    		$client_id    = $this->client_id;
	    	$secret_key   = $this->secret_key;
	    	$access_code   = $this->access_code;
	    	//echo $access_code;die;
	    	$url = "https://accounts.zoho.com/oauth/v2/token?code=".$access_code."&redirect_uri=http://hestalabs.com/testZoho&client_id=".$client_id."&client_secret=".$secret_key."&grant_type=authorization_code";
			
			$headers = array(
	    		"accept: */*",
			    "accept-language: en-US,en;q=0.8",
			    "content-type: application/json",
	    	);
			
			$data = array();
			$data = $this->curlRequest($url,'POST',$headers);
			//dd($data);
					
			if (isset($data->access_token)) 
			{
		    	$accessToken = $data->access_token;
				$refreshToken = $data->refresh_token;
				
				//$detail= new ZohoDetail;        
			    $this->zohodetail->access_token = $accessToken;
			    $this->zohodetail->refresh_token= $refreshToken;
			    $this->zohodetail->gen_time = date('Y-m-d h:i:s');
			    $this->zohodetail->save();
			    $response['status'] = 'success';
			} else {
				//dump($data);    
				$response['status'] = 'error';
			}

			if($request->ajax()) 
				echo json_encode($response);
				
    	}
    	catch (\Exception $e) {
    		dd($e);	
    	}
    }

    /*
		generating refresh access token based on once generated refresh token
    */
    public function getRefreshAccessToken()
    {
    	try 
    	{
	    	$detail = $this->getToken('all');

	    	$start  = strtotime($detail->gen_time);
			$end 	= strtotime(\Carbon\Carbon::now()); // Current time and date
			//$diff  	= date_diff( $start, $end );
			$diff = $end - $start;
			//echo $diff;die;
			if($diff > 3550) 
			{
				$url='https://accounts.zoho.com/oauth/v2/token?refresh_token='.$detail->refresh_token.'&client_id='.$this->client_id.'&client_secret='.$this->secret_key.'&grant_type=refresh_token';
	    	
				$headers = array(
		    		"content-type: application/json",
		    		"Authorization: Zoho-oauthtoken ".$detail->access_token,
		    	);

				$data = array();
				$data = $this->curlRequest($url,'POST',$headers);
				//dd($data);
				if(isset($data->access_token)) {
					$finalData = array(
						'access_token' 	=> $data->access_token,
						'gen_time' 		=> date('Y-m-d h-i-s'),
					);
					$this->zohodetail->where('refresh_token', $detail->refresh_token)->update($finalData);
				} else {
				    dd($data);
				}	
			} else {
				echo "Access Token has not expired yet";
			}	
		} 
    	catch (\Exception $e) {
    		dd($e);	
    	}	
    }

	/**
     * fetching details of a particular portal
     *
     * @param  \Illuminate\Http\Request  $request
     * @return ajax response
    */	
    public function getPortal(Request $request)
    {
    	try 
    	{
    		$response = array();
    		$access_token = $this->getToken();

	    	$url = 'https://projectsapi.zoho.com/restapi/portals/';

			$headers = array(
	    		"content-type: application/json",
	    		"Authorization: Zoho-oauthtoken ".$access_token,
	    	);

			$data = array();
			$data = $this->curlRequest($url,'GET',$headers);
			if(isset($data->error->code) == 6500){
				echo "Access Token Expired";
			} elseif ($data->portals[0]) 
			{
				$portal_id   = $data->portals[0]->id;
				$portal_name = $data->portals[0]->name;

				//checking whether portal data exists then update else insert
				$recordExist = $this->portal->where('portal_id',$portal_id)->count();
				if(($recordExist) > 0) 
				{
					$finalData = array(
						'portal_name' 	=> $portal_name,
						'portal_name'	=> $portal_name,	
					);
					$recordExistValue = $this->portal->where('portal_id',$portal_id)->first();
					$this->portal->where('portal_id', $recordExistValue->portal_id)->update($finalData);
					$response['status'] = 'success';
				} else {	
					$this->portal->portal_id = $portal_id;
			        $this->portal->portal_name= $portal_name;
			        $this->portal->portal_slug= $portal_name;
			        $this->portal->save();
			        $response['status'] = 'success';
				}
			} else {
				$response['status'] = 'error';
			    echo "false";
			}

			if($request->ajax()) 
				echo json_encode($response);		
    	} 
    	catch (\Exception $e) {
    		dd($e);	
    	}
    }

    /*
		fetching projects of a particular portal and saving them
    */
    public function projects()
    {
    	try 
    	{	
    		$cron_executed = $this->zohodetail->pluck('cron_executed')->first();
			if($cron_executed == NULL) {
				$portal_id = $this->getPortalId();
				$url = 'https://projectsapi.zoho.com/restapi/portal/'.$portal_id.'/projects/';

				$data = array();
				$data = $this->curlRequest($url,'GET',$this->curlHeader());
				if(isset($data->error->code) == 6500){
					echo "Access Token Expired";
				} elseif (isset($data->projects)) 
				{
					$projects = $data->projects;
					foreach($projects as $projectDetail)
					{
						/*saving project data into project table*/
						$finalData = $this->projectTransform($projectDetail);
						Project::updateOrCreate(array('zoho_project_id'=>$projectDetail->id),$finalData);
				    }
				    $this->users();
				} else {
				    echo "No projects found";
				}
			}
		} 
    	catch (\Exception $e) {
    		dd($e);	
    	}		
    }

    /*
		get all users of a particular portal and saving them
    */
    public function users()
    { 
    	try 
    	{
	    	$portal_id = $this->getPortalId();
			$url = 'https://projectsapi.zoho.com/restapi/portal/'.$portal_id.'/users/?usertype=all';	    
			$data = array();
			$users = $this->curlRequest($url,'GET',$this->curlHeader());
			if (isset($users->error->code) == 6500) {
				echo "Access Token Expired";
			} elseif (isset($users)) {
				foreach($users as $key => $userDetail){
					foreach ($userDetail as $value) {
				       $finalData = $this->userTransform($value);
					   User::updateOrCreate(array('zoho_user_id'=>$value->id),$finalData);
					}
			    } 
			    $this->projectUsers();
			} else {
				echo "No User Found";
	    	}
	    } 
    	catch (\Exception $e) {
    		dd($e);	
    	}	
	}

	/*
		getting association of each user with project
	*/
	public function projectUsers()
    {
    	try 
    	{
	    	$portal_id  = $this->getPortalId();
			$projects   = $this->getAllProject(); //fetching all projects from database
			if (isset($projects)) 
			{
				foreach($projects as $project)
				{
					$url = 'https://projectsapi.zoho.com/restapi/portal/'.$portal_id.'/projects/'.$project->zoho_project_id.'/users/';

			    	$data = array();
					$data = $this->curlRequest($url,'GET',$this->curlHeader());
					
					if(isset($data->error->code) == 6500){
						echo "Access Token Expired";
					} elseif (isset($data->users) && ($data)) 
					{
						//saving record of association of each user with project into db
					    $projectUsers = $data->users;
						foreach ($projectUsers as $value) 
						{
							$user 		= $this->user->where('zoho_user_id', $value->id)->first();
							$userId 	= $user->id;

							$recordExist = $this->project->where('id', $project->id)
											->whereHas('users', function ($q) use ($userId) {
								        		$q->where('user_id', $userId);
								    		})
								    		->count();
							if (($recordExist) > 0) 
							{	
							} else {	
								$project = $this->project->where('id', $project->id)->first();
								$project->users()->attach($userId);
							}
						}
					} else {
						echo "false";
			    	}
		    	}	
		    	$this->taskLists('internal');
		    	$this->taskLists('external');
			} else {
				echo 'No Projects';
			}
		} 
    	catch (\Exception $e) {
    		dd($e);	
    	}
	}

	/**
     * getting all task list related to project
     *
     * @param  $flag_value string 
    */	
	public function taskLists($flag_value)
	{ 
		try 
    	{
	    	$portal_id = $this->getPortalId();
			$projects   = $this->getAllProject(); //fetching all projects from database
			if (isset($projects)) 
			{
				foreach($projects as $project) {
					$url = 'https://projectsapi.zoho.com/restapi/portal/'.$portal_id.'/projects/'.$project->zoho_project_id.'/tasklists/?flag='.$flag_value;

					$data = array();
					$data = $this->curlRequest($url, 'GET', $this->curlHeader());
					
					if (isset($data->error->code) == 6500) {
						echo "Access Token Expired";
					} elseif (isset($data->tasklists) && ($data)) {
				    	$taskLists = $data->tasklists;
						foreach ($taskLists as $value) {
							$finalData = $this->taskListTransform($value, $project->id, $flag_value);
							TaskList::updateOrCreate(array('tasklist_id'=>$value->id), $finalData);
						}
					} else {
						echo "No Task List found";
			    	}
				}

				if ($flag_value == 'external')
					$this->tasks();

			} else {
				echo 'No Projects';
			}	
		} 
    	catch (\Exception $e) {
    		dd($e);	
    	}
	}

	/*
		getting all tasks for a tasklist of project
	*/
	public function tasks()
	{ 
		try 
    	{
	    	$portal_id = $this->getPortalId();
			$header_id =$this->getHeaderId('task');	//getting header id based on tasks
			$projects   = $this->getAllProject(); //fetching all projects from database
			$access_token = $this->getToken();
			if (isset($projects)) {
				foreach($projects as $project)
				{

					$tasklists = TaskList::where('project_id',$project->id)->get()->toArray();
					foreach ($tasklists as $tasklist) {
						$tasklist_id = $tasklist['id'];
						$zohoTaskListId = $tasklist['tasklist_id'];
						$url = 'https://projectsapi.zoho.com/restapi/portal/'.$portal_id.'/projects/'.$project->zoho_project_id.'/tasklists/'.$zohoTaskListId.'/tasks/';
						
						$data = array();
						$data = $this->curlRequest($url,'GET',$this->curlHeader());
						//dump($data);
						if (isset($data->error->code) == 6500) {
							echo "Access Token Expired";
						} elseif (isset($data->tasks) && ($data)) {	
							$taskLists = $data->tasks;
							foreach ($taskLists as $value) 
							{	
								//dump($value);
								$finalData = $this->taskTransform($value, $tasklist_id, $header_id);
								Header_detail::updateOrCreate(array('zoho_unique_id'=>$value->id),$finalData);
							}
						} else {
							echo "No Task found";
				    	}
					}
				}
				$this->bugs();
				
			} else {
				echo 'No Projects';
			}	
		} 
    	catch (\Exception $e) {
    		dd($e);	
    	}
	}


	/*
		getting bug of particular project
	*/
	public function bugs()
	{ 
		try 
		{
	    	$portal_id = $this->getPortalId();
			$header_id =$this->getHeaderId('bugs'); //getting header id based on tasks
			$projects   = $this->getAllProject(); //fetching all projects from database
			$access_token = $this->getToken();
			if(isset($projects)) 
			{
				foreach($projects as $project)
				{
					$tasklist 		= TaskList::where('project_id',$project->id)->first();
					$tasklist_id 	= $tasklist->id;
					$zohoTaskListId = $tasklist->tasklist_id;

					$url = 'https://projectsapi.zoho.com/restapi/portal/'.$portal_id.'/projects/'.$project->zoho_project_id.'/bugs/';
					
					$data = array();
					$data = $this->curlRequest($url,'GET',$this->curlHeader());
					if(isset($data->error->code) == 6500){
						echo "Access Token Expired";
					} elseif (isset($data->bugs) && ($data)) {	
						foreach ($data->bugs as $value) {
							if(isset($value->reporter_id)){
								$user = User::where('zoho_user_id',$value->reporter_id)->first();
							}
							$finalData = $this->taskTransform($value, $tasklist_id, $header_id);
							Header_detail::updateOrCreate(array('zoho_unique_id'=>$value->id),$finalData);
						}
					
					} else {
						echo "No Bugs for this Project.";
			    	}
				}
				$this->zohodetail->where('access_token',$access_token)->update(['cron_executed'=>1]);
			} else {
				echo 'No Projects';
			}	
		} 
		catch (\Exception $e) {
			dd($e);	
		}
	}


	/*
		getting all sub tasks for a task of project
	*/
	public function subTasks()
	{ 
		try 
    	{	
    		$cron_executed = $this->zohodetail->pluck('cron_executed')->first();
			if(!empty($cron_executed)) {
		    	$portal_id = $this->getPortalId();
				$header_id = $this->getHeaderId('tasks'); //getting header id based on tasks			
				$projects   = $this->getAllProject(); //fetching all projects from database
				
				if(isset($projects)) 
				{
					foreach($projects as $project)
					{
						$tasklists = TaskList::where('project_id',$project->id)->get()->toArray();
						foreach ($tasklists as $tasklist) {
							$tasklist_id = $tasklist['id'];
							$zohoTaskListId = $tasklist['tasklist_id'];
							$tasks = Header_detail::where(['task_list_id'=>$tasklist_id,'child'=>1])->get()->toArray();

							if(!empty($tasks) && $tasks != null){
								foreach ($tasks as $task) {
									$url = 'https://projectsapi.zoho.com/restapi/portal/'.$portal_id.'/projects/'.$project->zoho_project_id.'/tasks/'.$task['zoho_unique_id'].'/subtasks/';
									
									$data = array();
									$data = $this->curlRequest($url,'GET',$this->curlHeader());
									
									if (isset($data->error->code) == 6500){
										echo "Access Token Expired";
									} elseif (isset($data->tasks) && ($data)){
										foreach ($data->tasks as $value) {
											$finalData = $this->taskTransform($value, $tasklist_id, $header_id);
											Header_detail::updateOrCreate(array('zoho_unique_id'=>$value->id),$finalData);
										}
									}
								}
							}
							
						}
					}
					//$viewDateType = array('01-01-2018','02-01-2018','03-01-2018','04-01-2018');
					$viewDateType = array('05-01-2018','06-01-2018','07-01-2018');
					foreach ($viewDateType as $date) {
						$this->logs('all','month','All',$date,'task');
						//$this->logs('all','month','All',$date,'general');
						$this->logs('all','month','All',$date,'bug');
					}
				} else {
					echo 'No Projects';
				}	
			} 
		}
    	catch (\Exception $e) {
    		dd($e);	
    	}
	}

	/*
		getting logs of particular task or subtask of project
	*/
	public function logs($users_list = 'all',$view_type = 'month',$bill_status = 'all',$date = array(),$component_type = 'task')
	{ 
		try 
    	{
	    	$portal_id = $this->getPortalId();
			$header_id = $this->getHeaderId('logs'); //getting header id based on tasks
			$projects   = $this->getAllProject(); //fetching all projects from database
			$access_token = $this->getToken();

			if(isset($projects)) 
			{
				foreach($projects as $project)
				{
					$url = 'https://projectsapi.zoho.com/restapi/portal/'.$portal_id.'/projects/'.$project->zoho_project_id.'/logs/?users_list='.$users_list.'&view_type='.$view_type.'&bill_status='.$bill_status.'&date='.$date.'&component_type='.$component_type;

					$data = array();
					$data = $this->curlRequest($url,'GET',$this->curlHeader());
					//dump($data);
					
					if (isset($data->error->code) == 6500){
						echo "Access Token Expired";
						//break;
					} elseif (isset($data->timelogs) && ($data)) {	
						foreach ($data->timelogs as $value) 
						{
							if(is_array($value)){
								foreach ($value as $val) {
									$this->filterResult($val,$component_type,$project->id);
								}
							}
						}
					} else {
						echo "No Log Record Found".'<br>';
			    	}
			    	
				}
				$this->zohodetail->where('access_token',$access_token)->update(['cron_executed'=>NULL]); 
			} else {
				echo 'No Projects'.'<br>';
			}	
		} 
    	catch (\Exception $e) {
    		dd($e);	
    	}
	}

	/*
		saving log data into db
	*/
	public function filterResult($data,$type,$projectId)
	{	
		try 
    	{
			if($type == 'task')
				$allData = $data->tasklogs;
			if($type == 'bug')
				$allData = $data->buglogs;

			foreach ($allData as $data) {
				if($type == 'task'){
					$id 	= $data->task->id;
					$name 	= $data->task->name;
				}

				if($type == 'bug'){
					$id 	= $data->bug->id;
					$name 	= $data->bug->title;
				}

				if(isset($id)){
					$headerDetailId = Header_detail::where('title',$name)->first();
					if($headerDetailId != NULL){
						$header_deatil_id = $headerDetailId->id;
					}
				}
				
				if(isset($data->owner_id)){
					$user=User::where('zoho_user_id',$data->owner_id)->first();
				}

				if(isset($data->notes)){
					$start1 	   = "start time -";
					$end1 		   = " end";
					$start_date_time = $this->getBetween($data->notes,$start1,$end1);

					$start2 	   = "end time ";
					$end2 		   = " time spent";
					$end_date_time = $this->getBetween($data->notes,$start2,$end2);
				}

				if(isset($header_deatil_id)) {
					$finalData = array(
						'header_detail_id' 	=> (isset($header_deatil_id) ? $header_deatil_id : NULL),
						'project_id' 		=> (($projectId) ? $projectId : NULL),
						'user_id'			=> (isset($user->id) ? $user->id : NULL),
						'zoho_log_id'		=> (isset($data->id) ? $data->id : NULL),
						'log_time' 			=> (isset($data->total_minutes) ? $data->total_minutes : NULL),
						//((DateTime::createFromFormat('d/m/Y h:i A','31/05/2018 09:04 PM'))->format('Y-m-d H:i:s'));
						'start_date_time'	=> (($start_date_time) ? ((\DateTime::createFromFormat('d/m/Y h:i A',$start_date_time))->format('Y-m-d H:i:s')) : NULL),
						'end_date_time' 	=> (($end_date_time) ? ((\DateTime::createFromFormat('d/m/Y h:i A',$end_date_time))->format('Y-m-d H:i:s')) : NULL),
						'bill_status'		=> (isset($data->bill_status) ? $data->bill_status : NULL),
						'approval_status'	=> (isset($data->approval_status) ? $data->approval_status : NULL),
					);
					//dd($finalData);
					Log::updateOrCreate(array('zoho_log_id'=>$data->id),$finalData);
				}
			}
		} 
    	catch (\Exception $e) {
    		dd($e);	
    	}	
	}

	/*
		sending weekly email
	*/
	public function sendWeeklyMail()
    {
    	try 
    	{
	    	$startDate = \Carbon\Carbon::today()->startOfWeek();
	    	$passedStartDate = $startDate;
	    	$weekStartDate = $startDate->format('Y-m-d');
	    	
	    	$startDate = \Carbon\Carbon::today()->startOfWeek();
	    	$weekEndDate = $startDate->addDay(5)->format('Y-m-d');
	    	$passedEndDate = $startDate->subDay(1);
	    	
	    	$result = $this->log
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
	    								
	  		//dd($result);
	        $data = array();
	        if(count($result) > 0) {
	        	foreach ($result as $log) {
	        		$temp['log_id'] = (isset($log['id']) ? $log['id'] : '');
	        		$temp['log_time'] = (isset($log['log_time']) ? ($this->convertToHoursMins($log['log_time'], '%02d:%02d')) : '');

	        		if(isset($log['header_detail']) && count($log['header_detail']) > 0) {
	        			$temp['planned_time'] = (isset($log['header_detail']['work']) ? ($log['header_detail']['work']) : 0);
	        			$temp['task_name'] = (isset($log['header_detail']['title']) ? ($log['header_detail']['title']) : '');

	    				if(isset($log['header_detail']['task_list']) && count($log['header_detail']['task_list']) > 0) {	

	        				if(isset($log['header_detail']['task_list']['project']) && count($log['header_detail']['task_list']['project']) > 0) {
		        				
		        				$temp['project_name'] = (isset($log['header_detail']['task_list']['project']['project_name']) ? $log['header_detail']['task_list']['project']['project_name'] : '');
			        		}
	        			
		        		}
	        			
	        		}

	        		if(isset($log['user']) && count($log['user']) > 0) {
	        			$temp['employee_name'] = (isset($log['user']['name']) ? $log['user']['name'] : '');
	        		}

	        		$data['main_data'][] = $temp;

	        		/*$report = new Report();
	        		$report->employee_name 	= $temp['employee_name'];
	        		$report->project_name 	= $temp['project_name'];
	        		$report->planned_time 	= $temp['planned_time'];
	        		$report->log_time 		= $temp['log_time'];
	        		$report->save();*/
	        	}
	        } else {
	        	$data['main_data'][] = '';
	        }
	        
	        
	        $content = 'Hello Sir ,<br/><br/>Here is the list of users with projects and time.'; 

	        $data['start_date'] = $passedStartDate->format('d M,Y');
			$data['end_date'] = $passedEndDate->format('d M,Y');
	        $data['main_content'] = $content; 
	        //dd($data);

	        //$email = "varun.hestabit@gmail.com";
	        $WeeklyEmailTO = env('WEEKLY_EMAIL_TO');
	        $WeeklyEmailCC = explode(',',env('WEEKLY_EMAIL_CC'));
	        //dump($WeeklyEmailCC);die;   
	        
	        \Mail::to($WeeklyEmailTO)->cc($WeeklyEmailCC)->send(new Mail($data));
	    } 
    	catch (\Exception $e) {
    		dd($e);	
    	}
    }
}