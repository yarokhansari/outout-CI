<?php
/*
User: Rutul Parikh
API Name: Like Message
Parameter: accessToken, user_id, message_id, is_like
Description: API will like/dislike broadcast message
*/

require 'BaseApi.php';

class LikeMessage extends BaseApi {

  function index() {
	  
    $response = array();
	
    if($this->validateAccessToken($this->input->post('accessToken')) || 4 == 4) {
		
      $user_id = $this->input->post('user_id');
      $message_id = $this->input->post('message_id');
      $is_liked = $this->input->post('is_liked');
	  
	  $like_where = array('user_id' => $user_id, "message_id" => $message_id);
	 
      
      $result = $this->model_name->selectRecordByFields('hoo_likes', $like_where);
	  
      if ($result) {
        $data = array(
          "user_id" => $user_id,
          "message_id"=> $message_id,
          "is_liked" => $is_liked,
          "updated_at" => date('Y-m-d h:i:s')
        );
        $this->model_name->update_data($data, "hoo_likes", "id", $result["id"]);
        $result = $this->model_name->select_data_by_condition('hoo_likes', array('id' => $result["id"]), '*' ,'' , '' ,'', '', array());
		$message = "";
        $message = "Message Unliked Successfully.";
      } else {
        $data = array(
          "user_id" => $user_id,
          "message_id"=> $message_id,
          "is_liked" => $is_liked,
          "created_at" => date('Y-m-d h:i:s')
        );
        $id = $this->model_name->insert_data_getid($data, "hoo_likes");
        $result = $this->model_name->select_data_by_condition('hoo_likes', array('id' => $id), '*' ,'' , '' ,'', '', array());
		$message = "";
        $message = "Message Liked Successfully.";
		
		$notification = array(
            "user_id" => $user_id,
            "description" => 'Message liked successfully.',
            "created_at" => date('Y-m-d h:i:s')
        );
		$notifications = $this->model_name->insert_data_getid($notification, "hoo_notifications");
		
		
      }
	  
	  $where = array("message_id" => $message_id, "is_delete" => "0", "is_liked" => '1');
      $likes = $this->model_name->select_data_by_condition('hoo_likes', $where, '*' ,'' , '' ,'', '', array());
      $count = count($likes);

      $text_data = array(
        "likes" => $count,
      );
	  
      $this->model_name->update_data($text_data, "hoo_broadcast_message", "id", $message_id);
	  
	  $response = array(
        'status' => 'success', 
        'errorcode' => '0', 
        'msg' => $message,
        'data' => $result
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