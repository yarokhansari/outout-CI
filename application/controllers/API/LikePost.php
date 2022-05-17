<?php
/*
User: Yogen Jajal
API Name: Like Post
Parameter: deviceToken, operation
Description: API will register user details and send email to user on registered email address
*/

require 'BaseApi.php';

class LikePost extends BaseApi {

  function index() {
    $response = array();
    if($this->validateAccessToken($this->input->post('accessToken')) || 4 == 4) {
      $user_id = $this->input->post('user_id');
      $media_id = $this->input->post('media_id');
      $is_liked = $this->input->post('is_liked');
	    $type = $this->input->post('type'); //Type = point id where user will get points according to action performed
      $like_where = array('user_id' => $user_id, "media_id" => $media_id);

      $message = "";
      $result = $this->model_name->selectRecordByFields('hoo_likes', $like_where);
      if ($result) {
        $data = array(
          "user_id" => $user_id,
          "media_id"=> $media_id,
          "is_liked" => $is_liked,
          "updated_at" => date('Y-m-d h:i:s')
        );
        $this->model_name->update_data($data, "hoo_likes", "id", $result["id"]);
        $result = $this->model_name->select_data_by_condition('hoo_likes', array('id' => $result["id"]), '*' ,'' , '' ,'', '', array());
		$message = "";
        $message = "Post unliked successfully.";
      }
      else {
        $data = array(
          "user_id" => $user_id,
          "media_id"=> $media_id,
          "is_liked" => $is_liked,
          "created_at" => date('Y-m-d h:i:s')
        );
        $id = $this->model_name->insert_data_getid($data, "hoo_likes");
        $result = $this->model_name->select_data_by_condition('hoo_likes', array('id' => $id), '*' ,'' , '' ,'', '', array());
		$message = "";
        $message = "Post liked successfully.";
		
		$notification = array(
            "user_id" => $user_id,
            "media_id" => $media_id,
            "description" => 'Post liked successfully.',
            "created_at" => date('Y-m-d h:i:s')
        );
		$notifications = $this->model_name->insert_data_getid($notification, "hoo_notifications");

    $getid = $this->model_name->select_data_by_condition('hoo_memory_media', array('id'=>$media_id), 'user_id', '', '','', '' , array());
    $friend_id=$getid[0]['user_id'];
    $userdetails = $this->model_name->select_data_by_condition("hoo_users", array('id' => $friend_id, 'is_delete' => '0'), '*' , '', '', '', '', array());
		$devicedetails = $this->model_name->select_data_by_condition("hoo_devices", array( 'user_id' => $friend_id ), '*' , '', '', '', '', array());
    $fullname = $userdetails[0]['first_name'] . " " . $userdetails[0]['last_name'];
			
    $notificationData = array(
      "title"=> "$fullname Liked Your Post!",
      "body" => "",
      "mutable_content"=> true,
      "sound"=> "Tri-tone"
     
    );

    $data = array(
      'notification_type' => '0',
      'isBroadCaster' => true,
      'hostUserId' => $userid
    );
    
    $notification = $this->model_name->sendNotification(
      $devicedetails[0]['fcmToken'],
      $data,
      $notificationData
    );






		
		//Get point details 
		$pointdetails = $this->model_name->select_data_by_condition('hoo_points', array('is_delete' => '0','id' => $type) , '*', '', '', '', '', array());
		
		$userpoint = array(
			'user_id' => $user_id,
			'media_id' => $media_id,
			'points' => $pointdetails[0]['points'],
			'comments' => 'Like post',
			'created_at' => date('Y-m-d h:i:s')
		);
		
		$points = $this->model_name->insert_data_getid($userpoint, "hoo_user_points");
		
		
		
      }
      //$this->updateLikesCount($media_id);
      $where = array("media_id" => $media_id, "is_delete" => "0", "is_liked" => '1');
      $likes = $this->model_name->select_data_by_condition('hoo_likes', $where, '*' ,'' , '' ,'', '', array());
      $count = count($likes);

      $media_data = array(
        "likes" => $count,
        "updated_at" => date('Y-m-d h:i:s')
      );
      $this->model_name->update_data($media_data, "hoo_memory_media", "id", $media_id);
      $response = array(
        'status' => 'success', 
        'errorcode' => '0', 
        'msg' => $message,
        'data' => $result
      );
    }
    else {
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