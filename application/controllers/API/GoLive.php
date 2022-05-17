<?php
error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');



class GoLive extends REST_Controller {

  function index(){

	  $alldevices = $this->model_name->select_data_by_condition('hoo_devices',array('accessToken' => $this->input->post('accessToken') ), '*' ,'' , '' ,'', '', array());
	  if( !empty($alldevices) ){
		  
	  
		$userid = $this->input->post('userid');
		$friends = $this->input->post('friends');
		$liveurl = $this->input->post('liveurl');
		
		
		/* Get first name and last name of user */
		
		$usercondition = array( 'is_delete' => '0','id' => $userid );
		
		$userdetails = $this->model_name->select_data_by_condition("hoo_users", $usercondition, 'first_name,last_name' , '', '', '', '', array());
		
		$fullname = $userdetails[0]['first_name'] . " " . $userdetails[0]['last_name'];
		
		$friends_array = explode(",", $friends);

		
		$result = count($friends_array);
		
		if( $result <= 3 ){
			
			$condition = "from_user_id IN ($friends) OR to_user_id IN ($friends) AND is_delete = '0' AND status = '1'";
			
			$userfriends = $this->model_name->select_data_by_condition("hoo_friend_request", $condition, '*' , '', '', '', '', array());
			
		
			if( !empty( $userfriends ) ){
				
				foreach( $userfriends as $friends ){
					
				
					/* Get Friends FCMToken */
					
					$condition = array( 'user_id' => $friends['to_user_id'] );
			
					$devicedetails = $this->model_name->select_data_by_condition("hoo_devices", $condition, '*' , '', '', '', '', array());
					
					$notificationData = array(
						  "title"=> "User Online",
						  "body" => "Your OutOut FRIEND $fullname wants to connect you on $liveurl",
						  "mutable_content"=> true,
						  "sound"=> "Tri-tone"
						 
					);

					$data = array(
						   "liveurl" => $liveurl
					);
					
					$notification = $this->model_name->sendNotification(
						$devicedetails[0]['fcmToken'],
						$data,
						$notificationData
					);
					
					/* Store notification */
					
					$notification = array(
						"user_id" => $friends['to_user_id'],
						"media_id" => "",
						"description" => "Your OutOut FRIEND $fullname is live now",
						"type" => '4',
						"created_at" => date('Y-m-d h:i:s')
					);
					
					$notifications = $this->model_name->insert_data_getid($notification, "hoo_notifications");
								
					
				}
				
				header('Content-Type: application/json');
				$success = array('status' => 'success', 'errorcode' => '0', 'msg' => 'Notification Send',' data' => $tokendata );
				echo json_encode($success);
				exit;
				
				
			}else{
				
			   header('Content-Type: application/json');
			   $error = array('status' => 'failed', 'errorcode' => '2', 'msg' => 'No friends exists for this particular user');
			   echo json_encode($error);
			   exit;
			}
				
				
		}else{
		  
			header('Content-Type: application/json');
			$error = array('status' => 'Failed', 'errorcode' => '2', 'msg' => 'You cannot add more than 3 friends at a time');
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