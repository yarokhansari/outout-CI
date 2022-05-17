<?php

require(APPPATH . '/libraries/REST_Controller.php');

class AddUsersInGroup extends REST_Controller{

  function index(){

	 $alldevices = $this->model_name->select_data_by_condition('hoo_devices',array('accessToken' => $this->input->post('accessToken') ), '*' ,'' , '' ,'', '', array());
         if( !empty($alldevices) ){
			 
			
			 $data['userid'] = $this->input->post('userid');
			 $data['groupid'] = $this->input->post('groupid');
			 $addusers = $this->input->post('addusers');
			 
			 
			 /* Check addusers are present in chat list or not */
			 
			 if( $data['groupid'] != 0 ){
				 
				 if( !empty( $addusers ) ){
				 
				 $friendcondition = 'adduserid IN ('. $addusers .') AND userid = ' . $data['userid'] . ' AND groupid = ' . $data['groupid'] . '';
				 
				 $friendsinfo = $this->model_name->select_data_by_condition("hoo_users_chat_group", $friendcondition , '*', '', '', '', '', array());

				 
								 				 
				 if( empty( $friendsinfo ) ){
					 
					 $condition = 'to_user_id IN ('.$addusers.') AND from_user_id = '. $data['userid'] . ' AND status = "1"';
					 
					 $info = $this->model_name->select_data_by_condition("hoo_friend_request", $condition , 'to_user_id', '', '', '', '', array());

					 		 
					 if( empty( $info ) ){
						 
						 $condition = 'to_user_id IN ('.$data['userid'].') AND from_user_id = '. $addusers . ' AND status = "1"';
				 
						 $infodetails = $this->model_name->select_data_by_condition("hoo_friend_request", $condition , '*', '', '', '', '', array());
						 
						 if( !empty( $infodetails ) ){
							 
							 foreach( $infodetails as $usersdetails ){
						 
								$adduserinGroup = array(
									  "userid" => $data['userid'],
									  "groupid" => $data['groupid'],
									  "adduserid" => $usersdetails['from_user_id'],
									  "type" => '1',
									  "created_at" => date('Y-m-d h:i:s'),
								);
								$submit_user_group = $this->model_name->insert_data_getid($adduserinGroup, "hoo_users_chat_group");
								
								 
							 }
							 
						 }
						 
					 
					 }else{
						 
						 foreach( $info as $usersdetails ){
						 
							$adduserinGroup = array(
								  "userid" => $data['userid'],
								  "groupid" => $data['groupid'],
								  "adduserid" => $usersdetails['to_user_id'],
								  "type" => '1',
								  "created_at" => date('Y-m-d h:i:s'),
							);
							
							$submit_user_group = $this->model_name->insert_data_getid($adduserinGroup, "hoo_users_chat_group");
							
							 
						 }
						 
						 
					 }
					 
					 header('Content-Type: application/json');
					 $success = array('status' => 'Success', 'errorcode' => '0', 'msg' => 'Friends are added in group successfully');
					 echo json_encode($success);
					 exit;
					 
					 
				 }else {
					 
					 header('Content-Type: application/json');
					 $error = array('status' => 'Failed', 'errorcode' => '2', 'msg' => 'Friends already exists in group');
					 echo json_encode($error);
					 exit;
					 
				 }				 
				 
				 
				 
			 }else{
				 
				 header('Content-Type: application/json');
				 $error = array('status' => 'Failed', 'errorcode' => '2', 'msg' => 'Please add friends in group');
				 echo json_encode($error);
				 exit;
				 
			 }
				 
				 
		  }else{

			/* Add individual chats */
			
			$individualcondition = 'adduserid IN ('. $addusers .') AND userid = ' . $data['userid'] . ' AND groupid = 0';
			
			$individualcondition = $this->model_name->select_data_by_condition("hoo_users_chat_group", $individualcondition , '*', '', '', '', '', array());
			
			if( empty( $individualcondition ) ){
				
				$adduserinGroup = array(
				  "userid" => $data['userid'],
				  "groupid" => 0,
				  "adduserid" => $addusers,
				  "type" => '0',
				  "created_at" => date('Y-m-d h:i:s'),
				);
				
				$submit_user_group = $this->model_name->insert_data_getid($adduserinGroup, "hoo_users_chat_group");
				
			}else {
				
				 header('Content-Type: application/json');
				 $error = array('status' => 'Failed', 'errorcode' => '2', 'msg' => 'Friend is already exists in chat');
				 echo json_encode($error);
				 exit;
			}
			
			
			
			header('Content-Type: application/json');
			$success = array('status' => 'Success', 'errorcode' => '0', 'msg' => 'You can start chatting with your friend');
			echo json_encode($success);
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


?>