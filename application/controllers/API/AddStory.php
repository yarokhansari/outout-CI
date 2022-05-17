<?php
/* API Name: Create Story
   Parameter: accessToken,apiKey,user_id,caption,type,valid_hours
   Description: API create story
 */

require 'BaseApi.php';

class AddStory extends BaseApi {

  function index() {
        $response = array();
        if ($this->validateAccessToken($this->input->post('accessToken')) || 4 == 4) {
			
            $user_id = $this->input->post('user_id');
            $caption = $this->input->post('caption');
			$type = $this->input->post('type');
			$valid_hours = $this->input->post('valid_hours');
			$created_date = date('Y-m-d h:i:s');
			
			/* Add Story */
			
			$actual_file_path = $this->config->item('upload_story_path');
			$allowed_types = $this->config->item('upload_story_allowed_types');
			$uploadData = $this->uploadImageFile($actual_file_path, $allowed_types, uniqid(), "story");
			if ($uploadData) {
			  $media_name = $uploadData["file_name"];
			  $extension = $this->getExtension($uploadData["file_type"]);
			  $image = base_url().$actual_file_path.$media_name;
			 
			}
			
			if( $type == '0' ){
				$valid_hours = 12;
			}else if( $type == '1' ){
				$valid_hours = 9;
			}else{
				$valid_hours = 10;
			}
			
			$start_index = 0;
            if ($offset > 0 && $limit > 0)
                $start_index = ($limit * $offset) - $limit;

            $friends = $this->custom->getMyFriends($user_id, $search_query, $start_index, $limit);

       		$token=array();

			 for($i=0;$i<=count($friends);$i++)
			 {
				 $where=$friends[$i]['user_id'];
        		$gettoken = $this->model_name->select_data_by_condition("hoo_devices", array('user_id'=>$where), '*' , '', '', '', '', array());
				array_push($token,$gettoken[$i]['fcmToken']);
    		 }
     		 $userdetails = $this->model_name->select_data_by_condition("hoo_users", array('id' => $user_id, 'is_delete' => '0'), '*' , '', '', '', '', array());
        	 $fullname = $userdetails[0]['first_name'] . " " . $userdetails[0]['last_name'];

      			$notificationData = array(
        		"title"=> "Story Added!",
        		"body" => "$fullname Added New Story",
        		"mutable_content"=> true,
        		"sound"=> "Tri-tone"
                   );

      $data = array(
                'notification_type' => '4',
                'isBroadCaster' => true,
                'hostUserId' => $user_id
                            );
            
                $notification = $this->model_name->sendMsg(
                $token,
                $data,
                $notificationData
                );




			$storydata = array(
				"user_id" => $user_id,
				"caption" => $caption,
				"story" => $image,
				"type" => $type,
				"valid_hours" => $valid_hours,
				"created_at" => date('Y-m-d h:i:s')
			);
			
			$addstory = $this->model_name->insert_data_getid($storydata, "hoo_user_story");
			
			if( $type == '0' ){
				$storytype = 'Normal';
			}else if( $type == '1' ){
				$storytype = 'Outout Outfit';
			}else {
				$storytype = 'Place';
			}
			
			$response = array(
				'status' => 'Success', 
				'errorcode' => '0', 
				'msg' => "Add " . $storytype .  " story and it will be visible upto " . $valid_hours .  " hours",
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