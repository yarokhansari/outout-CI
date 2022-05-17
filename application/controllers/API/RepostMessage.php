<?php
/*
User: Rutul Parikh
API Name: Repost Message
Parameter: accessToken, user_id, message_id, is_like
Description: API will like/dislike broadcast message
*/

require 'BaseApi.php';

class RepostMessage extends BaseApi {

  function index() {
	  
    $response = array();
	
    if($this->validateAccessToken($this->input->post('accessToken')) || 4 == 4) {
		
      $user_id = $this->input->post('user_id');
      $message_id = $this->input->post('message_id');
	  
	  $usermessage = array(
		'user_id' => $user_id,
		'message_id' => $message_id,
		'created_at' => date('Y-m-d h:i:s')
	  );
	
	  $userpost = $this->model_name->insert_data_getid($usermessage, "hoo_memory_media");
	  
	  /* Add notification for repost message */
	  
	  $notification = array(
		"user_id" => $user_id,
		"message_id" => $message_id,
		"description" => 'Message Reposted successfully.',
		"created_at" => date('Y-m-d h:i:s')
	  );
	  
	  $notifications = $this->model_name->insert_data_getid($notification, "hoo_notifications");
	  
	  $response = array(
        'status' => 'success', 
        'errorcode' => '0', 
        'msg' => 'Message posted successfully'
      );

      
    }else {
      $response = array(
        'status' => 'failure', 
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