<?php
/*
Author: Rutul Parikh
API Name: ListVIPAccounts
Parameter: apiKey
Description: API will list out all accounts based on type.
*/

error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class ListVIPAccounts extends REST_Controller {
	
    function index() {
	  
	  $alldevices = $this->model_name->select_data_by_condition('hoo_devices',array('accessToken' => $this->input->post('accessToken') ), '*' ,'' , '' ,'', '', array());
	  if( !empty($alldevices) ){
		  
		  $userid = $this->input->post('userid');
		  
		  $condition = "(from_user_id = $userid OR to_user_id = $userid) AND status = '1' AND is_delete = '0'";
		  
		  $friends = $this->model_name->select_data_by_condition('hoo_friend_request', $condition , '*' ,'' , '' ,'', '', array());
		  
		  $friendid = [];
		  
		  if( !empty($friends) ){
			  
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
		  
			  $condition = "id IN ($friendid) AND is_vip = '1' AND is_delete = '0'";
		  
			  $users = $this->model_name->select_data_by_condition('hoo_users', $condition , 'id,first_name,last_name,username,email,profile_image' ,'' , '' ,'', '', array());
			  
			  
			  header('Content-Type: application/json');
			  $success = array('status' => 'Success', 'errorcode' => '0', 'msg' => 'VIP Friends','data' => $users);
			  echo json_encode($success);
			  exit;
			  
			  
		  }else{
		  
			  $response = array(
				'status' => 'Failed', 
				'errorcode' => '2',
				'msg' => 'No VIP friends are there for this particular user'
			  );
			  
		  }
		  
		  
			  
	  
	  }else{
		  
		  $response = array(
            'status' => 'Failed', 
            'errorcode' => '2',
            'msg' => 'Access Token is incorrect'
          );
		  
	  }
	  
	  header('Content-Type: application/json');
      echo json_encode($response);
      exit;


  }
}

?>