<?php
/*
Author: Rutul Parikh
API Name: ListAccounts
Parameter: apiKey
Description: API will list out all accounts based on type.
*/

require 'BaseApi.php';

class ListAccounts extends BaseApi {
	
    function index() {
      $response = array();
	  
	  $type = $this->input->post('type');
	  
	  $userid = $this->input->post('userid');
	  
	  $condition = "from_user_id = $userid AND status = '1' AND is_delete = '0'";
		  
	  $friends = $this->model_name->select_data_by_condition('hoo_friend_request', $condition , '*' ,'' , '' ,'', '', array());
	  
	  if( !empty( $friends ) ){
		  
		  $friendid = [];
	  
		  foreach( $friends as $friend ){
			  
			  $fromid = $friend['from_user_id'];
			  $toid = $friend['to_user_id'];
			
			  if($fromid != $userid) {
				  $friendid[] = $friend['from_user_id'];
			  }else{
				  $friendid[] = $friend['to_user_id'];
			  }
			  
		  }
		  
		  $friendid = implode(',',array_unique($friendid)); 
			
		  $condition = "hoo_users.id IN ($friendid) AND hoo_users.account_type = '$type' AND hoo_users.is_delete = '0'";
		  
		  $join_str[0] = array(
				'table' => 'hoo_category',
				'join_table_id' => 'hoo_category.id',
				'from_table_id' => 'hoo_users.catid',
				'type' => '',
		  );
		  
		  $info = $this->model_name->select_data_by_condition('hoo_users', $condition, 'hoo_users.id,hoo_users.first_name,hoo_users.last_name,hoo_users.username,hoo_users.email,hoo_users.profile_image,hoo_users.username,hoo_users.city,hoo_users.dob,hoo_users.gender,hoo_users.account_type,hoo_users.is_vip,hoo_category.name as catname', '', '', '', '', $join_str);
		  
		  if(!empty($info)) {
			  
				foreach( $info as $userinfo ){
					
					$user['user_id'] = $userinfo['id'];
					$user['full_name'] = $userinfo['first_name'] . " " . $userinfo['last_name'];
					if( $userinfo['dob'] != "" ){
						$user['dob'] = $userinfo['dob'];
					}else{
						$user['dob'] = "";
					}
					$user['gender'] = $userinfo['gender'];
					if( $userinfo['phone_number'] != "" ){
						$user['phone_number'] = $userinfo['phone_number'];
					}else{
						$user['phone_number'] = "";
					}
					$user['email'] = $userinfo['email'];
					$user['username'] = $userinfo['username'];
					if( $userinfo['city'] != "" ){
						$user['city'] = $userinfo['city'];
					}else{
						$user['city'] = "";
					}
					if( $userinfo['profile_image'] != "" ){
						$user['profile_image'] = $userinfo['profile_image'];
					}else{
						$user['profile_image'] = "";
					}
					$user['account_type'] = $userinfo['account_type'];
					if( $userinfo['is_vip'] != "" ){
						$user['is_vip'] = $userinfo['is_vip'];
					}else{
						$user['is_vip'] = "";
					}
					if( $userinfo['catname'] != "" ){
						$user['catname'] = $userinfo['catname'];
					}else{
						$user['catname'] = "";
					}
					
					
					$details[] = $user;
					
				}
			  
				$response = array(
					'status' => 'success', 
					'errorcode' => '0', 
					'msg' => 'List Accounts',
					'data' => $details
				);
			}
			else {
				$response = array(
					'status' => 'success', 
					'errorcode' => '0',
					'msg' => 'There are no accounts.'
				);
			}
		  
	  } else {
		  
		  $response = array(
			'status' => 'success', 
			'errorcode' => '0',
			'msg' => 'There are no accounts.'
		  );
		  
	  }
	 

      header('Content-Type: application/json');
      echo json_encode($response);
      exit;

  }
}

?>