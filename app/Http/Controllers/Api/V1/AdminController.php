<?php
namespace App\Http\Controllers\Api\V1;

use App\User;
use App\Project;
use App\Portal;
use App\ZohoDetail;
use App\Header;
use App\Header_detail;
use App\Log;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function __construct(Project $project, User $user, Portal $portal, ZohoDetail $zohodetail, Header $header, Header_detail $header_detail, Log $log)
    {
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
            $data = array();
            $userCount = $this->user->count();  //count no of users in system
            $projectCount = $this->project->count();    //count no of projects in system
            $headerDeatil = $this->header->where('header_name','LIKE',"%tasks%")->first();  //count no of tasks in system
            $header_id = $headerDeatil->id;
            $taskCount = $this->header_detail->where('header_id',$header_id)->count();

             $bugId = $this->header->where('header_name','LIKE',"%bugs%")->first();  //count no of bugs in system
            $bugCount = $this->header_detail->where('header_id',$bugId->id)->count();

            $data['userCount'] = $userCount;
            $data['projectCount'] = $projectCount;
            $data['taskCount'] = $taskCount;
            $data['bugCount'] = $bugCount;

            echo json_encode($data);
            
        } catch (\Exception $e) {
            dd($e); 
        }
    }

}