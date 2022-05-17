<?php

error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class AllUsers extends REST_Controller {

  function index(){

	  $alldevices = $this->model_name->select_data_by_condition('hoo_devices',array('accessToken' => $this->input->post('accessToken') ), '*' ,'' , '' ,'', '', array());
	  if( !empty($alldevices) ){
		  
		
		$userid = $this->input->post('userid');
		$search = $this->input->post('search');
		
		if( $search != "" ){
			
			$condition = 'hoo_users.is_delete = "0" AND (hoo_users.first_name LIKE "%'.$search.'%" OR hoo_users.last_name LIKE "%'.$search.'%") AND hoo_users.id != ' . $userid;
		} else {
			$condition = 'hoo_users.is_delete = "0" AND hoo_users.id != ' . $userid;
		}
	
		$columns = 'hoo_users.id,hoo_users.first_name,hoo_users.last_name,hoo_users.dob,hoo_users.gender,hoo_users.phone_number,hoo_users.email,hoo_users.username,hoo_users.city,hoo_users.profile_image,hoo_users.account_type,hoo_users.is_vip';
	
		$userinfo = $this->model_name->select_data_by_condition('hoo_users', $condition, $columns, '', '', '', '', $join_str);
	
		foreach( $userinfo as $info ){
			
			
			$condition = array('hoo_friend_request.to_user_id' => $info['id'] , 'hoo_friend_request.from_user_id' => $userid, 'hoo_friend_request.is_delete' => '0' );
			
			$columns = 'status,is_follow';
			
			$friends = $this->model_name->select_data_by_condition('hoo_friend_request', $condition, $columns, '', '', '', '', array());
			
			
			if( !empty( $friends ) ){
				$user['user_id'] = $info['id'];
				$user['full_name'] = $info['first_name'] . " " . $info['last_name'];
				if( $info['dob'] != "" ){
					$user['dob'] = $info['dob'];
				}else{
					$user['dob'] = "";
				}
				$user['gender'] = $info['gender'];
				if( $info['phone_number'] != "" ){
					$user['phone_number'] = $info['phone_number'];
				}else{
					$user['phone_number'] = "";
				}
				$user['email'] = $info['email'];
				$user['username'] = $info['username'];
				if( $info['city'] != "" ){
					$user['city'] = $info['city'];
				}else{
					$user['city'] = "";
				}
				if( $info['profile_image'] != "" ){
					$user['profile_image'] = $info['profile_image'];
				}else{
					$user['profile_image'] = "";
				}
				$user['account_type'] = $info['account_type'];
				if( $info['is_vip'] != "" ){
					$user['is_vip'] = $info['is_vip'];
				}else{
					$user['is_vip'] = "";
				}

				$user['status'] = $friends[0]['status'];
				$user['is_follow'] = $friends[0]['is_follow'];
			} else{
				$user['user_id'] = $info['id'];
				$user['full_name'] = $info['first_name'] . " " . $info['last_name'];
				if( $info['dob'] != "" ){
					$user['dob'] = $info['dob'];
				}else{
					$user['dob'] = "";
				}
				$user['gender'] = $info['gender'];
				if( $info['phone_number'] != "" ){
					$user['phone_number'] = $info['phone_number'];
				}else{
					$user['phone_number'] = "";
				}
				$user['email'] = $info['email'];
				$user['username'] = $info['username'];
				if( $info['city'] != "" ){
					$user['city'] = $info['city'];
				}else{
					$user['city'] = "";
				}
				if( $info['profile_image'] != "" ){
					$user['profile_image'] = $info['profile_image'];
				}else{
					$user['profile_image'] = "";
				}
				$users['account_type'] = $info['account_type'];
				if( $info['is_vip'] != "" ){
					$user['is_vip'] = $info['is_vip'];
				}else{
					$user['is_vip'] = "";
				}
				$user['status'] = '0';
				$user['is_follow'] = '0';
			}
			$details[] = $user;
		}
		
		if( !empty($details) ){
			
		   header('Content-Type: application/json');
		   $success = array('status' => 'success', 'errorcode' => '0', 'msg' => 'All Users','data' => $details);
		   echo json_encode($success);
		   exit;
		
		}else{
			
		   header('Content-Type: application/json');
		   $error = array('status' => 'success', 'errorcode' => '0', 'msg' => 'There are no users');
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