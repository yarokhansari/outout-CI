<?php

error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class Wallet extends REST_Controller {

  function index(){

	  $alldevices = $this->model_name->select_data_by_condition('hoo_devices',array('accessToken' => $this->input->post('accessToken') ), '*' ,'' , '' ,'', '', array());
	  if( !empty($alldevices) ){
		  
		$userid = $this->input->post('userid');
		
		$condition = 'is_delete = "0" AND id = ' . $userid;
		
		$columns = 'id,first_name,last_name,wallet';
	
		$userinfo = $this->model_name->select_data_by_condition('hoo_users', $condition, $columns, '', '', '', '', $join_str);
		
		if( !empty($userinfo) ){
			
			foreach( $userinfo as $user ){
				
				$details['user_id'] = $user['id'];
				$details['full_name'] = $user['first_name'] . " " . $user['last_name'];
				$details['wallet'] = $user['wallet'];
				
			}
			
			header('Content-Type: application/json');
		    $success = array('status' => 'success', 'errorcode' => '0', 'msg' => 'Current balance details','data' => $details);
		    echo json_encode($success);
		    exit;
			
		} else {
			
			header('Content-Type: application/json');
		    $error = array('status' => 'failure', 'errorcode' => '1', 'msg' => 'There is no such user exists');
		    echo json_encode($error);
		    exit;
			
		}
		
			
	  }else{
		  
		header('Content-Type: application/json');
		$error = array('status' => 'failure', 'errorcode' => '2', 'msg' => 'Access Token is incorrect');
		echo json_encode($error);
		exit;
		  
	  }

		
  }

}