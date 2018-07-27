<?php
namespace App\Traits;

use Illuminate\Http\Request;
use App\Portal;
use App\Header;
use App\ZohoDetail;
use App\Project;
use DB;

trait Helper
{
	public function getLastQuery() 
    {
    	DB::enableQueryLog();
    	dd(DB::getQueryLog());
    }

    public function curlHeader()
    {
        $access_token = $this->getToken();
        return $headers = array(
            "content-type: application/json",
            "Authorization: Zoho-oauthtoken ".$access_token,
        );
    }
	/*
		executing curl based on params passed
    */
    public function curlRequest($url = '',$request_type = "GET",$headers = array())
    {
    	$curl = curl_init();

		curl_setopt_array($curl, array(
		    CURLOPT_URL => $url,
		    CURLOPT_RETURNTRANSFER => true,
		    CURLOPT_CUSTOMREQUEST => $request_type,
		    CURLOPT_HTTPHEADER => $headers
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		    echo "cURL Error #:" . $err;
		} else {
			return json_decode($response);
		}   
    }

    /*
		fetching portal id from db
    */
    public function getPortalId() 
    {
    	$portal_id = '633531303'; //Hardcoding Hestabit portal id
    	
    	$portalRecord = Portal::count();
    	if(($portalRecord) > 0) {
            $portal = Portal::first();
    		$portal_id = $portal->portal_id;
    	}

    	return $portal_id;
    }

    /*
		fetching access/refresh token from db based on condition
    */
    public function getToken($type = 'access') 
    {
    	$token = '';
		$zohoRecord = ZohoDetail::count();
		  	
		if(($zohoRecord) > 0) {
            $zohoCredentials = ZohoDetail::first();
			if($type == 'access')
    			$token = $zohoCredentials->access_token;
    		elseif($type == 'refresh')
    			$token = $zohoCredentials->refresh_token;
    		elseif($type == 'all')
    			return $zohoCredentials;
    	}

    	return $token;
    }

    /*
		fetching all project from db
    */
    public function getAllProject() 
    {
        $projects = array();
    	$projects   = Project::count();
    	if($projects > 0) {
            $projects   = Project::all();
    		return $projects;
    	}
    }

    /*
     get log start and end time   
    */
    public function getBetween($content,$start,$end){
        $r = explode($start, $content);
        if (isset($r[1])){
            $r = explode($end, $r[1]);
            return $r[0];
        }
        return '';
    }

    public function getHeaderId($header_name)
    {
        $headerDeatil = Header::where('header_name','LIKE',"%".$header_name."%")->first();
        return json_decode($headerDeatil)->id;
    }

    public function convertToHoursMins($time, $format = '%02d:%02d') 
    {
        if ($time < 1) {
            return;
        }
        $hours = floor($time / 60);
        $minutes = ($time % 60);
        return sprintf($format, $hours, $minutes);
    }

}
