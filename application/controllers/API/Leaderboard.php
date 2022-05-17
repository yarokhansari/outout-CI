<?php

error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class Leaderboard extends REST_Controller {

  function index(){

	  $alldevices = $this->model_name->select_data_by_condition('hoo_devices',array('accessToken' => $this->input->post('accessToken') ), '*' ,'' , '' ,'', '', array());
	  if( !empty($alldevices) ){
		  
		$userid = $this->input->post('userid');
	
		$join_str[0] = array(
			'table' => 'hoo_user_points',
			'join_table_id' => 'hoo_user_points.user_id',
			'from_table_id' => 'hoo_users.id',
			'type' => '',
		);
		
		$condition = array('hoo_users.is_delete' => '0', 'hoo_users.id !=' => $userid );
		
		$columns = 'hoo_users.id,hoo_users.first_name,hoo_users.last_name,SUM(hoo_user_points.points) as points,hoo_users.email,hoo_users.profile_image';
		
		$group_by = 'hoo_users.id';
	
		$otherinfo = $this->model_name->select_data_by_allcondition('hoo_users', $condition, $columns, 'points', 'desc', '9', '', $join_str,$group_by);
		
		/* User Object */
		
		$join_str[0] = array(
			'table' => 'hoo_user_points',
			'join_table_id' => 'hoo_user_points.user_id',
			'from_table_id' => 'hoo_users.id',
			'type' => '',
		);
		
		$condition = array('hoo_users.is_delete' => '0' , 'hoo_users.id' => $userid);
		
		$columns = 'hoo_users.id,hoo_users.first_name,hoo_users.last_name,SUM(hoo_user_points.points) as points,hoo_users.email,hoo_users.profile_image';
		
		$userinfo =  $this->model_name->select_data_by_allcondition('hoo_users', $condition, $columns, 'points', 'desc', '', '', $join_str,'');
		
		$info = array_merge_recursive($otherinfo, $userinfo);
		
		if( !empty($info) ){
			
		   header('Content-Type: application/json');
		   $success = array('status' => 'success', 'errorcode' => '0', 'msg' => 'Leaderboard Data','data' => $info);
		   echo json_encode($success);
		   exit;
		
		}else{
			
		   header('Content-Type: application/json');
		   $error = array('status' => 'failure', 'errorcode' => '2', 'msg' => 'There are no users with points');
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