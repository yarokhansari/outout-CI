<?php

error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class Notification extends REST_Controller {

  function index(){

	  $alldevices = $this->model_name->select_data_by_condition('hoo_devices',array('accessToken' => $this->input->post('accessToken') ), '*' ,'' , '' ,'', '', array());
	  if( !empty($alldevices) ){
		  
		$userid = $this->input->post('userid');
		$fcmToken = $this->input->post('fcmtoken');
		
		$columns = "SUM(points) as TotalPoints";
		
		$condition = array('user_id' => $userid, 'is_delete' => '0');
		
		$userpoints = $this->model_name->select_data_by_condition("hoo_user_points", $condition, $columns , '', '', '', '', array());
		
		if( $userpoints >= 20 ){
			
			$notificationData = array(
				  "title"=> "Gift Notification",
				  "body" => "You will receive the gift on your email address",
				  "mutable_content"=> true,
				  "sound"=> "Tri-tone"
			); 
			
			$notification = $this->model_name->sendNotification(
				'fZHIBZxqTCiW4PUVjBmHRK:APA91bFy51X1qNplgGT15GCzcAjiOqHJhpiH_G4oQAXJSTNzbijhEYJiHaBOm5VSN7OBkQ9zv_OJiANNnya_qEi0pz7h3bjLUFK_D3IVkFNFEg2qzdKpOEAhLHp1ZGCwF4NodzIDoNZ1',
				$notificationData,
				$notificationData
			);
			
		
			$submitData = array(
			  "user_id" => $userid,
			  "created_at" => date('Y-m-d h:i:s'),
			);

			$giftNotification = $this->model_name->insert_data_getid($submitData, "hoo_gift_notification");
			
			$notification = array(
				"user_id" => $userid,
				"media_id" => "",
				"description" => 'You will receive the gift on your email address',
				"created_at" => date('Y-m-d h:i:s')
			);
			$notifications = $this->model_name->insert_data_getid($notification, "hoo_notifications");
			
			if( $giftNotification ){
				
			   header('Content-Type: application/json');
			   $success = array('status' => 'success', 'errorcode' => '0', 'msg' => 'You will receive the gift on your email address' );
			   echo json_encode($success);
			   exit;
			
			}else{
				
			   header('Content-Type: application/json');
			   $error = array('status' => 'failed', 'errorcode' => '2', 'msg' => 'Access Token is incorrect');
			   echo json_encode($error);
			   exit;
				
			}
			
		}
		
		
			
	  }else{
		  
		header('Content-Type: application/json');
		$error = array('status' => 'Failed', 'errorcode' => '2', 'msg' => 'Access Token is incorrect');
		echo json_encode($error);
		exit;
		  
	  }

		
  }

}