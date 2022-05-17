<?php
/*
Author: Yarokh Ansari
API Name: Check In
Parameter: apiKey
Description: Will check in
*/

error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class Checkin extends REST_Controller {
	
	function index() {
      $alldevices = $this->model_name->select_data_by_condition('hoo_devices',array('accessToken' => $this->input->post('accessToken') ), '*' ,'' , '' ,'', '', array());
	  if( !empty($alldevices) ){
        	$from_user=$this->input->post('from_user');
            $to_user=$this->input->post('to_user');
        	$check=$this->input->post('checkin');

        		$checkme = array(
				"from_user" => $from_user,
				"to_user"=>$to_user,
				"checkin"=>$check,
				"created_at" => date('Y-m-d h:i:s')
			);
			$checkedin = $this->model_name->insert_data_getid($checkme, "hoo_checkin");
			$userdetails = $this->model_name->select_data_by_condition("hoo_users", array('id' => $to_user, 'is_delete' => '0'), '*' , '', '', '', '', array());
			$fullname = $userdetails[0]['first_name'] . " " . $userdetails[0]['last_name'];  
		  header('Content-Type: application/json');
		  if($check==1)
		  {
		  $success = array('status' => 'Success', 'errorcode' => '0', 'msg' => 'You Have checked In Successfully');

		$notification = array(
            "user_id" => $from_user,
            "media_id" => $to_user,
            "description" => "$fullname just checked In",
            "created_at" => date('Y-m-d h:i:s')
        );
		$notifications = $this->model_name->insert_data_getid($notification, "hoo_notifications");  
		   } else

		   {
			   	$notification = array(
            "user_id" => $from_user,
            "media_id" => $to_user,
            "description" => "$fullname just checked Out",
            "created_at" => date('Y-m-d h:i:s')
        );
		  $success = array('status' => 'Success', 'errorcode' => '0', 'msg' => 'You Have checked Out Successfully');	
		  echo json_encode($success);
		  exit;	  
		   }
	}
    else{
		  
		header('Content-Type: application/json');
		$error = array('status' => 'failure', 'errorcode' => '2', 'msg' => 'Access Token is incorrect');
		echo json_encode($error);
		exit;
		  
	  }

}
}