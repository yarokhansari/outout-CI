<?php
/* API Name: Create Broadcast Message
   Parameter: accessToken,apiKey,user_id,message
   Description: API create broadcast message
 */

require 'BaseApi.php';

class CreateBroadcastMessage extends BaseApi {

  function index() {
        $response = array();
        if ($this->validateAccessToken($this->input->post('accessToken')) || 4 == 4) {
			
            $user_id = $this->input->post('user_id');
            $message = $this->input->post('message');
			$created_date = date('Y-m-d h:i:s');
			$offer=$this->input->post('offer');
			$from=$this->input->post('from_date');
			$to=$this->input->post('to_date');
			$image_base64 = base64_decode($this->input->post('image'));
			$file = $this->config->item('upload_path_image') . uniqid() . '.jpg';
			file_put_contents($file, $image_base64);
			$image = base_url() .  $file;
			
			/* From Users Details */
			
			$fromuserdetails = $this->model_name->select_data_by_condition("hoo_users", array( 'id' => $user_id ) , 'id,username,first_name,last_name,profile_image' , '', '', '', '', array());
			
			$fromname = $fromuserdetails[0]['first_name'] . " " . $fromuserdetails[0]['last_name'];
			$profile_image = $fromuserdetails[0]['profile_image'];
			//$fromusername = $fromuserdetails[0]['username'];
			
			/* Get Friend List */
			
			$lists = $this->model_name->select_data_by_condition('hoo_friend_request', array('from_user_id' => $user_id,'status' => '1'), 'to_user_id' ,'' , '' ,'', '', array());
			
			foreach( $lists as $list ){
				$friends[] = $list['to_user_id'];
			}
			
			$to_user_ids = implode(',',$friends);
			
			$messagedata = array(
				"user_id" => $user_id,
				"message" => $message,
				"to_user_id" => $to_user_ids,
				"created_at" => date('Y-m-d h:i:s'),
				'image'=>$image,
				'is_offer'=>$offer,
				'from_date'=>$from,
				'to_date'=>$to,
			);
			
			$broadcastid = $this->model_name->insert_data_getid($messagedata, "hoo_broadcast_message");
			
			foreach( $friends as $friend ){
				
				/* Device Details */
			
				$devicedetails = $this->model_name->select_data_by_condition("hoo_devices", array( 'user_id' => $friend )  , 'fcmToken' , '', '', '', '', array());
				$notificationData = array(
					  "title"=> "Broadcast Message",
					  "body" => "You have 1 broadcast message from your friend $fromname",
					  "mutable_content"=> true,
					  "sound"=> "Tri-tone"
					 
				);

				$data = array(
						 "fromuserid" => $user_id,
						 "fromusername" => $fromname,
						 "profile_image" => $profile_image,
						 "messageId" => $broadcastid,
						 "message" => $message,
						 "notification_type" => '1',
						//  'image' => 'https://carboncostume.com/wordpress/wp-content/uploads/2016/08/blackpanther.jpg',
						//  'style' => 'picture',
						//  'picture' =>$image,
				);
				
				
				$notification = $this->model_name->sendNotification(
					$devicedetails[0]['fcmToken'],
					$data,
					$notificationData
				);
				
				
				/* Store notification */
				
				$notification = array(
					"user_id" => $friend,
					"message_id" => $broadcastid,
					"description" => "You have received 1 broadcast message from your friend $fromname",
					"type" => '1',
					"created_at" => date('Y-m-d h:i:s')
				);
				
				$notifications = $this->model_name->insert_data_getid($notification, "hoo_notifications");
				
			}
			$response = array(
				'status' => 'Success', 
				'errorcode' => '0', 
				'msg' => "Send Broadcast Message successfully",
			);

		}else {
		   $response = array(
			'status' => 'Failed', 
			'errorcode' => '2',
			'msg' => 'Access Token is incorrect',
		   );
		}  

        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
  }
}