<?php
 
require(APPPATH . '/libraries/REST_Controller.php');

class DeleteUsersInGroup extends REST_Controller{

  function index(){
	  
	  $alldevices = $this->model_name->select_data_by_condition('hoo_devices',array('accessToken' => $this->input->post('accessToken') ), '*' ,'' , '' ,'', '', array());
	  if( !empty($alldevices) ){
		  
		  $groupid = $this->input->post('groupid');
		  $addusers = $this->input->post('addusers');
		  
		  if( !empty( $addusers ) ){
			  
			    $condition = 'adduserid IN ('.$addusers.') AND groupid = '. $groupid;
								 
				$info = $this->model_name->select_data_by_condition("hoo_users_chat_group", $condition , '*', '', '', '', '', array());
				
				if( !empty( $info ) ){
					
					foreach( $info as $user ){
						
						$delete_friend = array(
							"is_delete" => '1',
						);
						$this->model_name->update_data($delete_friend, 'hoo_users_chat_group', 'id', $user['id']);
						
					}
					
					header('Content-Type: application/json');
				    $error = array('status' => 'Success', 'errorcode' => '0', 'msg' => 'Friends are deleted successfully');
				    echo json_encode($error);
				    exit;
					
				}else{
					
				   header('Content-Type: application/json');
				   $error = array('status' => 'Failed', 'errorcode' => '1', 'msg' => 'There is no such friends belongs to this group');
				   echo json_encode($error);
				   exit;
					
				}
				
			  
		  }else{
			  
			   header('Content-Type: application/json');
			   $error = array('status' => 'Failed', 'errorcode' => '2', 'msg' => 'Please select friends in group');
			   echo json_encode($error);
			   exit;
			  
		  }
		  
		  
		  
	  }else{
		  
		 header('Content-Type: application/json');
		 $error = array('status' => 'Failed', 'errorcode' => '2', 'msg' => 'Access Token is incorrect');
		 echo json_encode($error);
		 exit;
		 
	  }

  }
}