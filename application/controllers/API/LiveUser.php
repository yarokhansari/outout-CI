<?php
error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

require_once "RtcTokenBuilder.php";

class LiveUser extends REST_Controller {

  function index(){
	  
	  $alldevices = $this->model_name->select_data_by_condition('hoo_devices',array('accessToken' => $this->input->post('accessToken')), '*' ,'' , '' ,'', '', array());
	  if( !empty($alldevices) ){
		  
	  
		$userid = $this->input->post('userid');
		$fcmToken = $this->input->post('fcmtoken');
		$livevalue = $this->input->post('livevalue');
		$liveurl = $this->input->post('liveurl');
		
		/* Get first name and last name of user */

		$usercondition = array( 'is_delete' => '0','id' => $userid );
		
		$userdetails = $this->model_name->select_data_by_condition("hoo_users", $usercondition, 'first_name,last_name' , '', '', '', '', array());
		
		$fullname = $userdetails[0]['first_name'] . " " . $userdetails[0]['last_name'];
		
		$condition = "from_user_id = $userid AND is_delete = '0' AND status = '1'";

		$userfriends = $this->model_name->select_data_by_condition("hoo_friend_request", $condition, '*' , '', '', '', '', array());
		
		$userdevicedetails = $this->model_name->select_data_by_condition("hoo_devices", array('user_id' => $userid), '*' , '', '', '', '', array());
		
		$appID = '92841c5e791c4ebc8e34eb16a13ca5f3';
		$appCertificate = 'a37afc0c7e144699b36e475c5f106d57';
		
		$subscribertoken = RtcTokenBuilder::buildTokenWithUid($appID, $appCertificate, $userdevicedetails[0]['channelName'] , 0 , 2 , 0);
		
		if( $livevalue == '1' ){
			
			if( !empty( $userfriends ) ){
				
				foreach( $userfriends as $friends ){
					
					/* Get Friends FCMToken */
					
					$condition = array( 'user_id' => $friends['to_user_id'] );
			
					$devicedetails = $this->model_name->select_data_by_condition("hoo_devices", $condition, '*' , '', '', '', '', array());
					
					$notificationData = array(
						  "title"=> "$fullname is live now!",
						  "body" => "Click here to join $liveurl",
						  "mutable_content"=> true,
						  "sound"=> "Tri-tone"
						 
					);

					$data = array(
						"liveurl" => $liveurl,
						'channelName' => $userdevicedetails[0]['channelName'],
						'agoraToken' => $userdevicedetails[0]['agoraToken'],
						'subscribertoken' => $subscribertoken,
						'notification_type' => '4',
						'isBroadCaster' => false,
						'hostUserId' => $userid
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
						"description" => "$fullname is live now",
						"type" => '4',
						"created_at" => date('Y-m-d h:i:s')
					);
					$notifications = $this->model_name->insert_data_getid($notification, "hoo_notifications");
					
					
				}
				
				header('Content-Type: application/json');
				$success = array('status' => 'success', 'errorcode' => '0', 'msg' => 'Notification Send' );
				echo json_encode($success);
				exit;
				
			}else{
				
			   header('Content-Type: application/json');
			   $error = array('status' => 'failed', 'errorcode' => '2', 'msg' => 'No friends exists for this particular user');
			   echo json_encode($error);
			   exit;
			}
			
			
			
		} else {
			
			/* Remove Agora Token for user id */
			if( !empty( $userfriends ) ){
				
				foreach( $userfriends as $friends ){
					
					$condition = array( 'user_id' => $friends['to_user_id'] );
			
					$devicedetails = $this->model_name->select_data_by_condition("hoo_devices", $condition, '*' , '', '', '', '', array());
					
					$notificationData = array(
						  "title"=> "Go Live has ended!!",
						  "body" => "$fullname has ended session",
						  "mutable_content"=> true,
						  "sound"=> "Tri-tone"
					);

					$data = array(
						/*'channelName' => $userdevicedetails[0]['channelName'],
						'agoraToken' => $userdevicedetails[0]['agoraToken'],
						'subscribertoken' => $subscribertoken,*/
						'notification_type' => '6',
						'isBroadCaster' => false
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
						"description" => "$fullname has ended session",
						"type" => '4',
						"created_at" => date('Y-m-d h:i:s')
					);
					$notifications = $this->model_name->insert_data_getid($notification, "hoo_notifications");
					
					$updateToken = array(
						'channelName' => '',
						'agoraToken' => '',
						'updated_at' => date('Y-m-d h:i:s')
					);
					
					$updateTokenData = $this->model_name->update_data($updateToken, "hoo_devices",'id', $alldevices[0]['id']);
					
				}
			}
		
		   header('Content-Type: application/json');
		   $success = array('status' => 'Success', 'errorcode' => '0', 'msg' => 'You have end the call');
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