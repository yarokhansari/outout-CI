<?php
error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

require_once "RtcTokenBuilder.php";

class JoinToGoLive extends REST_Controller {

  function index(){
	  
	  $alldevices = $this->model_name->select_data_by_condition('hoo_devices',array('accessToken' => $this->input->post('accessToken')), '*' ,'' , '' ,'', '', array());
	  if( !empty($alldevices) ){
		  
	  
		$userid = $this->input->post('userid');
		$friendid = $this->input->post('friendid');
		
		$appID = '92841c5e791c4ebc8e34eb16a13ca5f3';
		$appCertificate = 'a37afc0c7e144699b36e475c5f106d57';
		
	  	$publishertoken = RtcTokenBuilder::buildTokenWithUid($appID, $appCertificate, $alldevices[0]['channelName'] , 0 , 1 , 0);
		
		$condition = array( 'user_id' => $friendid );
			
		$devicedetails = $this->model_name->select_data_by_condition("hoo_devices", $condition, '*' , '', '', '', '', array());
		
		$userdetails = $this->model_name->select_data_by_condition("hoo_users", array('id' => $userid, 'is_delete' => '0'), '*' , '', '', '', '', array());
		
		$frienddetails = $this->model_name->select_data_by_condition("hoo_users", array('id' => $friendid, 'is_delete' => '0'), '*' , '', '', '', '', array());
		
		if( !empty( $devicedetails ) ){
			
			$fullname = $userdetails[0]['first_name'] . " " . $userdetails[0]['last_name'];
			
			$notificationData = array(
			  "title"=> "$fullname is requesting to join!",
			  "body" => "Click here to go live with your friend",
			  "mutable_content"=> true,
			  "sound"=> "Tri-tone"
			 
			);

			$data = array(
				'channelName' => $alldevices[0]['channelName'],
				'agoraToken' => $alldevices[0]['agoraToken'],
				'subscribertoken' => $publishertoken,
				'notification_type' => '4',
				'isBroadCaster' => true,
				'hostUserId' => $userid
			);
			
			$notification = $this->model_name->sendNotification(
				$devicedetails[0]['fcmToken'],
				$data,
				$notificationData
			);
			
			/* Store notification */
			
			$notification = array(
				"user_id" => $friendid,
				"media_id" => "",
				"description" => "$fullname is live now",
				"type" => '4',
				"created_at" => date('Y-m-d h:i:s')
			);
			$notifications = $this->model_name->insert_data_getid($notification, "hoo_notifications");
			
			header('Content-Type: application/json');
			$success = array('status' => 'success', 'errorcode' => '0', 'msg' => 'Notification Send' );
			echo json_encode($success);
			exit;
			
			
		}else{
			
			header('Content-Type: application/json');
			$error = array('status' => 'Failed', 'errorcode' => '2', 'msg' => 'Notification is not send');
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